<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class FillAllFillableModels extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fill-all-fillable-models';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $modelsPath = app_path('Models');
        $modelFiles = File::allFiles($modelsPath);

        foreach ($modelFiles as $modelFile) {
            $modelName = pathinfo($modelFile, PATHINFO_FILENAME);
            $modelClass = 'App\\Models\\' . $modelName;

            if (class_exists($modelClass)) {
                $this->updateModelFillable($modelClass);
            }
        }

        $this->info('All models updated successfully.');
        return 0;
    }

    protected function updateModelFillable($modelClass)
    {
        $model = new $modelClass;
        $table = $model->getTable();

        // Fetch all column names from the database table
        $columns = DB::getSchemaBuilder()->getColumnListing($table);

        // Exclude certain columns
        $excludedColumns = ['id', 'deleted_at', 'created_at', 'updated_at'];
        $columns = array_diff($columns, $excludedColumns);

        // Generate the fillable array
        $fillableArray = array_map(function ($column) {
            return "'$column'";
        }, $columns);

        $fillableString = implode(', ', $fillableArray);

        // Update the model file
        $modelPath = app_path("Models/" . class_basename($modelClass) . ".php");
        $content = File::get($modelPath);



        if (preg_match('/protected \$fillable\s*=\s*\[.*?\];/s', $content, $matches)) {
            $newContent = preg_replace('/protected \$fillable\s*=\s*\[.*?\];/s', "protected \$fillable = [$fillableString];", $content);

        } else {
            // $newContent = str_replace(
            //     'class ' . class_basename($modelClass),
            //     "class " . class_basename($modelClass) . "\n \n    protected \$fillable = [$fillableString];",
            //     $content
            // );

            // Add $fillable property after the class declaration and before the first method or property
            $newContent = preg_replace('/class ' . class_basename($modelClass) . '[^{]*\{/', "$0\n    protected \$fillable = [$fillableString];", $content);
        }

        File::put($modelPath, $newContent);
        $this->info("Model " . class_basename($modelClass) . " updated successfully.");
    }
}
