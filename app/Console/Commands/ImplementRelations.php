<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

use Log;

class ImplementRelations extends Command
{
    protected $signature = 'make:relations';
    protected $description = 'Read migrations and automatically implement relationships in models';

    protected $patterns = [
        '/\$table->foreign\([\'"](.+?)[\'"]\)->references\([\'"]id[\'"]\)->on\([\'"](.+?)[\'"]\)/',
        '/\$table->unsignedBigInteger\([\'"](.*?)[\'"]\)/',
    ];

    protected $except = [
        'cache',
        'jobs',
        'social_providers',
        'oauth_auth_codes',
        'oauth_access_tokens',
        'oauth_refresh_tokens',
        'oauth_clients',
        'oauth_personal_access_clients'
    ];

    public function handle()
    {
        $this->info('Reading migrations...');

        $migrationPath = database_path('migrations');
        $migrationFiles = File::allFiles($migrationPath);

        foreach ($migrationFiles as $file) {
            $migrationContent = File::get($file);
            $tableName = $this->getTableNameFromMigration($file->getFilename());
           // Log::info("the migration file name is:" . $file->getFilename());
           // Log::info("the table names is:" . $tableName);

            // Skip if the table is in the exception list
            if (in_array(strtolower($tableName), $this->except)) {
                $this->info("Skipping table: {$tableName}");
                continue;
            }

            $this->info("Processing table: {$tableName}");

            preg_match_all($this->patterns[0], $migrationContent, $foreignMatches);
            preg_match_all($this->patterns[1], $migrationContent, $columnMatches);

            $relatedModels = [];

            // Process foreign keys to derive relationships
            if (!empty($foreignMatches[1])) {
                foreach ($foreignMatches[1] as $index => $foreignKey) {
                    $relatedTable = $foreignMatches[2][$index];
                    $relatedModel = $this->getModelFromTableName($relatedTable);

                    // Skip if the related model is in the exception list
                    if (in_array(strtolower($relatedModel), $this->except)) {
                        $this->info("Skipping related model: {$relatedModel}");
                        continue;
                    }

                    $relatedModels[$foreignKey] = $relatedModel;
                }
            }

            // Add relationships based on the model names found in the migration
            $this->addModelRelations($migrationContent, $tableName, $relatedModels);
        }

        $this->info('Relationships generated successfully!');
    }

    private function getTableNameFromMigration($migrationFileName)
    {
        // Get the content of the migration file
        $migrationPath = database_path('migrations/' . $migrationFileName);
        $migrationContent = File::get($migrationPath);

        // Match the table name in the Schema::create line
        if (preg_match('/Schema::create\([\'"](.+?)[\'"]/i', $migrationContent, $matches)) {
            return $matches[1]; // Return the table name found in the create statement
        }

        return null; // Return null if no table name is found
    }
    private function getModelFromTableName($tableName)
    {
        // Convert table name to model name using StudlyCase and singularize.
        return Str::studly(Str::singular($tableName));
    }

    private function addModelRelations($migrationContent, $tableName, $relatedModels)
    {
        
        $model = $this->getModelFromTableName($tableName);
        // Log::info("the relatedModels file is:" . json_encode($relatedModels));
        //Log::info("the tableName file is:" . $tableName);
        //Log::info("the model name s is:" . $model);
        $modelPath = app_path("Models/{$model}.php");

        if (File::exists($modelPath)) {
            $content = File::get($modelPath);
            $relationshipMethods = '';

            // Create relationships for each foreign key
            foreach ($relatedModels as $foreignKey => $relatedModel) {
                $methodName = Str::camel($relatedModel);

                // Check if the relationship method already exists
                if (!Str::contains($content, "public function {$methodName}()")) {
                    switch ($foreignKey) {
                        case 'person_id':
                        case 'spouse_id':
                            $relationshipMethods .= "
    public function {$methodName}()
    {
        return \$this->belongsTo({$relatedModel}::class, '{$foreignKey}');
    }
";
                            break;

                        case 'creator_id':
                        case 'editor_id':
                            $relationshipMethods .= "
    public function " . Str::camel(Str::singular($relatedModel)) . "()
    {
        return \$this->belongsTo({$relatedModel}::class, '{$foreignKey}');
    }
";
                            break;
                    }
                }
            }

            // Check and add relationship methods
            if (!empty($relationshipMethods)) {
                $this->info("Adding relationships to {$model}");
                $content = str_replace('}', $relationshipMethods . '}', $content);
                File::put($modelPath, $content);
            } else {
                $this->info("No new relationships to add to {$model}.");
            }
        } else {
            $this->warn("Model file {$modelPath} does not exist.");
        }
    }
}