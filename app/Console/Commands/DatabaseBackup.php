<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class DatabaseBackup extends Command
{
    protected $signature = 'db:backup {--name= : Optional backup file name}';

    protected $description = 'Backup the SQLite database';

    public function handle(): int
    {
        $dbPath = database_path('database.sqlite');

        if (! file_exists($dbPath)) {
            $this->error('Database file not found: '.$dbPath);
            return Command::FAILURE;
        }

        $name = $this->option('name') ?: 'backup-'.now()->format('Y-m-d_H-i-s').'.sqlite';
        $backupPath = 'backups/'.$name;

        Storage::disk('local')->makeDirectory('backups');

        if (copy($dbPath, Storage::disk('local')->path($backupPath))) {
            $this->info('Backup created: '.$backupPath);
            return Command::SUCCESS;
        }

        $this->error('Failed to create backup.');
        return Command::FAILURE;
    }
}
