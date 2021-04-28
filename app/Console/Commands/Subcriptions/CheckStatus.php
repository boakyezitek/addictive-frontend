<?php

namespace App\Console\Commands\Subcriptions;

use Carbon\Carbon;
use App\Models\Subscription;
use Illuminate\Console\Command;

class CheckStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set the status of expired subscriptions to Paused';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Subscription::where('status', Subscription::STATUS_IN_PROGRESS)->where('expiration_at', '<', Carbon::now())->update(['status' => Subscription::STATUS_PAUSED]);
    }
}
