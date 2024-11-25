<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class DeleteModelFiles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:model-files {model : The name of the model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all files related to a model including model, controllers, requests, resources, policies, factories, and migrations';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $model = $this->argument('model');
        $baseName = ucfirst($model);
        $pathsToDelete = [
            "app/Models/$baseName.php",
            "app/Http/Controllers/{$baseName}Controller.php",
            "app/Http/Requests/Store{$baseName}Request.php",
            "app/Http/Requests/Update{$baseName}Request.php",
            "app/Policies/{$baseName}Policy.php",
            "app/Http/Resources/{$baseName}Resource.php",
            "database/factories/{$baseName}Factory.php",
        ];

        // Find and delete migration file(s)
        $migrationFiles = glob("database/migrations/*_create_" . strtolower($model) . "s_table.php");
        $pathsToDelete = array_merge($pathsToDelete, $migrationFiles);

        // Delete files
        foreach ($pathsToDelete as $file) {
            if (File::exists($file)) {
                File::delete($file);
                $this->info("Deleted: $file");
            } else {
                $this->warn("Not found: $file");
            }
        }

        // Rollback migrations if necessary
        $this->callSilent('migrate:rollback', ['--step' => 1]);
        $this->info("Rolled back the last migration step.");

        $this->info("Cleanup for model '$baseName' completed.");
        return 0;
    }
}
