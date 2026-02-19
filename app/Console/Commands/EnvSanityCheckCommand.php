<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class EnvSanityCheckCommand extends Command
{
    protected $signature = 'app:env-sanity-check {--strict : Return non-zero exit code on warnings}';

    protected $description = 'Validate critical production environment settings.';

    public function handle(): int
    {
        $warnings = [];

        $expectations = [
            'APP_ENV' => fn ($v) => $v === 'production',
            'APP_DEBUG' => fn ($v) => in_array(strtolower((string) $v), ['false', '0', 'off'], true),
            'SESSION_DRIVER' => fn ($v) => in_array($v, ['database', 'redis'], true),
            'QUEUE_CONNECTION' => fn ($v) => in_array($v, ['database', 'redis'], true),
            'MAIL_MAILER' => fn ($v) => in_array($v, ['smtp', 'ses', 'mailgun', 'postmark', 'resend'], true),
            'DB_CONNECTION' => fn ($v) => in_array($v, ['pgsql', 'mysql'], true),
        ];

        foreach ($expectations as $key => $validator) {
            $value = env($key);
            if (! $validator($value)) {
                $warnings[] = "{$key} is set to '{$value}' and may be unsafe for production.";
            }
        }

        foreach (['MAIL_HOST', 'MAIL_PORT', 'MAIL_USERNAME', 'MAIL_PASSWORD', 'MAIL_FROM_ADDRESS'] as $mailKey) {
            if (blank(env($mailKey))) {
                $warnings[] = "{$mailKey} is empty; email delivery may fail.";
            }
        }

        if (empty($warnings)) {
            $this->info('Environment sanity check passed.');
            return self::SUCCESS;
        }

        $this->warn('Environment sanity check found issues:');
        foreach ($warnings as $warning) {
            $this->line("- {$warning}");
        }

        return $this->option('strict') ? self::FAILURE : self::SUCCESS;
    }
}
