<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class BackupDatabaseCommand extends Command
{
    protected $signature = 'app:backup-db {--retention=14 : Number of days to keep backup files}';

    protected $description = 'Create a compressed database backup and prune old files.';

    public function handle(): int
    {
        $driver = (string) env('DB_CONNECTION', 'pgsql');
        $database = (string) env('DB_DATABASE');
        $host = (string) env('DB_HOST', '127.0.0.1');
        $port = (string) env('DB_PORT');
        $username = (string) env('DB_USERNAME');
        $password = (string) env('DB_PASSWORD');

        if (blank($database) || blank($username)) {
            $this->error('Missing DB credentials in environment.');
            return self::FAILURE;
        }

        $backupDir = storage_path('app/backups');
        File::ensureDirectoryExists($backupDir);

        $stamp = now()->format('Ymd_His');
        $extension = $driver === 'mysql' ? 'sql' : 'dump';
        $filename = "{$database}_{$stamp}.{$extension}";
        $fullPath = $backupDir . DIRECTORY_SEPARATOR . $filename;

        if ($driver === 'pgsql') {
            $command = ['pg_dump', '-h', $host, '-p', $port, '-U', $username, '-F', 'c', '-f', $fullPath, $database];
        } elseif ($driver === 'mysql') {
            $command = ['mysqldump', '-h', $host, '-P', $port, '-u', $username, "--password={$password}", $database];
        } else {
            $this->error("Unsupported DB driver '{$driver}' for backup command.");
            return self::FAILURE;
        }

        $process = new Process($command);
        $process->setTimeout(600);
        $env = $driver === 'pgsql' ? ['PGPASSWORD' => $password] : [];
        $process->setEnv(array_merge($_ENV, $_SERVER, $env));

        if ($driver === 'mysql') {
            $process->run();
            if (! $process->isSuccessful()) {
                $this->error('Backup failed: ' . trim($process->getErrorOutput() ?: $process->getOutput()));
                return self::FAILURE;
            }
            File::put($fullPath, $process->getOutput());
        } else {
            $process->run();
            if (! $process->isSuccessful()) {
                $this->error('Backup failed: ' . trim($process->getErrorOutput() ?: $process->getOutput()));
                return self::FAILURE;
            }
        }

        $this->info("Backup created: {$filename}");
        $this->pruneOldBackups($backupDir, (int) $this->option('retention'));

        return self::SUCCESS;
    }

    private function pruneOldBackups(string $backupDir, int $retentionDays): void
    {
        $threshold = now()->subDays(max(1, $retentionDays))->getTimestamp();

        foreach (File::files($backupDir) as $file) {
            if ($file->getMTime() < $threshold) {
                File::delete($file->getPathname());
            }
        }
    }
}
