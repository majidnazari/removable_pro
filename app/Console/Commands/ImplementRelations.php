<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ImplementRelations extends Command
{
    protected $signature = 'make:relations';
    protected $description = 'Read migrations and automatically implement relationships in models';

    protected $patterns = [
        '/\$table->foreign\([\'"](.+?)[\'"]\)->references\([\'"]id[\'"]\)->on\([\'"](.+?)[\'"]\)/',
        '/\$table->unsignedBigInteger\([\'"](.*?)[\'"]\)/',
    ];

    protected $except = [
        'cache', 'jobs', 'social_providers', 'oauth_auth_codes', 'oauth_access_tokens',
        'oauth_refresh_tokens', 'oauth_clients', 'oauth_personal_access_clients'
    ];

    public function handle()
    {
        $this->info('Reading migrations...');

        $migrationPath = database_path('migrations');
        $migrationFiles = File::allFiles($migrationPath);

        foreach ($migrationFiles as $file) {
            $migrationContent = File::get($file);

            foreach ($this->patterns as $pattern) {
                preg_match_all($pattern, $migrationContent, $matches);

                if (!empty($matches[1])) {
                    foreach ($matches[1] as $index => $foreignKey) {
                        $relatedTable = $matches[2][$index] ?? null;

                        if (!$relatedTable) {
                            continue;
                        }

                        $model = $this->getModelFromMigration($file->getFilename());
                        $relatedModel = $this->getModelFromTableName($relatedTable);

                        // Skip if the model or related table is in the exception list
                        if (in_array(strtolower($model), $this->except) || in_array(strtolower($relatedModel), $this->except)) {
                            $this->info("Skipping relationships for: {$model} or {$relatedModel}");
                            continue;
                        }

                        $this->createRelationshipMethod($model, $relatedModel, $foreignKey);
                        $this->createInverseRelationshipMethod($relatedModel, $model, $foreignKey);
                    }
                }
            }
        }

        $this->info('Relationships generated successfully!');
    }

    private function getModelFromMigration($migrationFileName)
    {
        // Extract the table name from the migration filename and convert it to a model name.
        return Str::studly(Str::singular(str_replace(['create_', '_table'], '', $migrationFileName)));
    }

    private function getModelFromTableName($tableName)
    {
        // Convert table name to model name using StudlyCase and singularize.
        return Str::studly(Str::singular($tableName));
    }

    private function createRelationshipMethod($model, $relatedModel, $foreignKey)
    {
        $modelPath = app_path("Models/{$model}.php");
        if (File::exists($modelPath)) {
            $content = File::get($modelPath);
            $relationshipMethod = "
    public function " . Str::camel($relatedModel) . "()
    {
        return \$this->belongsTo({$relatedModel}::class, '{$foreignKey}');
    }
";
            if (!Str::contains($content, "public function " . Str::camel($relatedModel) . "()")) {
                $this->info("Adding relationship to {$model}: {$relatedModel}");
                $content = str_replace('}', $relationshipMethod . '}', $content);
                File::put($modelPath, $content);
            }
        }
    }

    private function createInverseRelationshipMethod($relatedModel, $model, $foreignKey)
    {
        $relatedModelPath = app_path("Models/{$relatedModel}.php");
        if (File::exists($relatedModelPath)) {
            $content = File::get($relatedModelPath);
            $relationshipMethod = "
    public function " . Str::camel(Str::plural($model)) . "()
    {
        return \$this->hasMany({$model}::class, '{$foreignKey}');
    }
";
            if (!Str::contains($content, "public function " . Str::camel(Str::plural($model)) . "()")) {
                $this->info("Adding inverse relationship to {$relatedModel}: {$model}s");
                $content = str_replace('}', $relationshipMethod . '}', $content);
                File::put($relatedModelPath, $content);
            }
        }
    }
}