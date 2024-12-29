<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FamilyBoard;
use Carbon\Carbon;

class DeleteOldFamilyBoards extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'familyboards:delete-old';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete FamilyBoard entries older than 2 days after creation';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // Find and delete FamilyBoards that are older than 2 days from the created_at timestamp
        $deleted = FamilyBoard::where('created_at', '<', Carbon::now()->subDays(2))
                              ->delete();

        $this->info("$deleted FamilyBoard(s) deleted successfully.");
    }
}
