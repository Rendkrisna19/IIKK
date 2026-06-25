<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Permit;
use Carbon\Carbon;

class ExpirePermitsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permits:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set status to expired for unused permits from previous days';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $count = Permit::whereIn('status', ['pending', 'approved'])
            ->whereDate('permit_date', '<', Carbon::today())
            ->update([
                'status' => 'expired',
                'cancel_message' => 'Otomatis kadaluarsa karena pergantian hari.'
            ]);

        $this->info("Successfully expired {$count} permits.");
    }
}
