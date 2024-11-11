<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\GraphQL\Enums\Status;


class GenerateRelationshipEnum extends Command
{
    protected $signature = 'generate:relationship-enum';
    protected $description = 'Generate Relationship enum based on database records';

   

    public function handle()
    {
    // Fetch titles from the `naslan_relationships` table
    $titles = DB::table('naslan_relationships')->where('status',Status::Active)->pluck('title')->toArray();

    // Convert titles into a formatted string for GraphQL enum
    $enumValues = implode("\n    ", $titles);

    // Define the path to your GraphQL schema file
    $schemaPath = base_path('graphql/types/NaslanRelationship/type.graphql');

    // Construct the enum definition string
    $enumDefinition = <<<GRAPHQL
    enum NaslanDanamicRelationshipEnum {
        $enumValues
    }
    GRAPHQL;

    // Read the existing schema file
    $schema = file_get_contents($schemaPath);

    // Replace the content of `NaslanDanamicRelationshipEnum` in the schema file
    $updatedSchema = preg_replace(
        '/enum NaslanDanamicRelationshipEnum \{.*?\}/s',
        $enumDefinition,
        $schema
    );

    // Write the updated schema back to the file
    file_put_contents($schemaPath, $updatedSchema);

    $this->info('NaslanDanamicRelationshipEnum updated successfully.');
}
}