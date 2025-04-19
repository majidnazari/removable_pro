<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class UncommentLogInfoLines extends Command
{
    protected $signature = 'logs:uncomment';
    protected $description = 'Uncomment only Log::info lines, including multi-line blocks, safely';

    public function handle()
    {
        $directory = base_path();
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $path = $file->getRealPath();

                // Skip this command file itself
                if (basename($path) === 'UncommentLogInfoLines.php') {
                    continue;
                }

                $lines = file($path);
                $modified = false;
                $insideLogBlock = false;

                foreach ($lines as $i => $line) {
                    $trimmed = ltrim($line);

                    // Check if this line begins a commented Log::info block
                    if (preg_match('/^\/\/\s*Log::info\(/', $trimmed)) {
                        $insideLogBlock = true;
                    }

                    // If inside Log::info block, uncomment line if it's part of the block
                    if ($insideLogBlock && preg_match('/^(\s*)\/\/\s?(.*)$/', $line, $matches)) {
                        $lines[$i] = $matches[1] . $matches[2] . PHP_EOL;
                        $modified = true;

                        // If this line ends the block
                        if (strpos($matches[2], ');') !== false) {
                            $insideLogBlock = false;
                        }
                        continue;
                    }

                    // Detect the end of a single-line Log::info, just in case
                    if ($insideLogBlock && strpos($line, ');') !== false) {
                        $insideLogBlock = false;
                    }
                }

                if ($modified) {
                    file_put_contents($path, implode('', $lines));
                    $this->info("✅ Uncommented Log::info block in: $path");
                }
            }
        }

        $this->info('🎉 Done uncommenting Log::info lines (only).');
    }
}
