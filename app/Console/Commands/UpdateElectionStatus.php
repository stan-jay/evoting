<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Election;
use Carbon\Carbon;

class UpdateElectionStatus extends Command
{
    protected $signature = 'elections:update-status';

    protected $description = 'Automatically update election statuses based on time';

    public function handle()
    {
        $now = Carbon::now();

        // Activate elections
        Election::where('status', 'pending')
            ->where('start_time', '<=', $now)
            ->update(['status' => 'active']);

        // Close elections
        Election::where('status', 'active')
            ->where('end_time', '<=', $now)
            ->update(['status' => 'closed']);

        $this->info('Election statuses updated successfully.');
    }
}
