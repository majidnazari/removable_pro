<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

class CommentLogInfoLines extends Command
{
    protected $signature = 'logs:comment';
    protected $description = 'Comment all Log::info lines including multi-line logs';

    public function handle()
    {
        $directory = base_path();
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));

        foreach ($iterator as $file) {
            if ($file->isFile() && $file->getExtension() === 'php') {
                $path = $file->getRealPath();

                // Skip this command file itself
                if (basename($path) === 'CommentLogInfoLines.php') {
                    continue;
                }

                $lines = file($path);
                $modified = false;
                $insideLogBlock = false;

                foreach ($lines as $i => $line) {
                    $trimmed = ltrim($line);

                    // Skip already commented Log::info lines
                    if (preg_match('/^\s*\/\/\s*Log::info\(/', $trimmed)) {
                        continue;
                    }

                    // Detect start of Log::info block
                    if (!$insideLogBlock && preg_match('/Log::info\(/', $line)) {
                        $insideLogBlock = true;
                    }

                    // Comment lines inside Log::info block
                    if ($insideLogBlock) {
                        if (!preg_match('/^\s*\/\//', $line)) {
                            $lines[$i] = '//' . $line;
                            $modified = true;
                        }

                        if (strpos($line, ');') !== false) {
                            $insideLogBlock = false;
                        }
                    }
                }

                if ($modified) {
                    file_put_contents($path, implode('', $lines));
                    $this->info("✏️ Commented Log::info block in: $path");
                }
            }
        }

        $this->info(' All Log::info lines have been commented.');
    }
}
