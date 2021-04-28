<?php

namespace App\Console\Commands\Users;

use App\Models\User;
use Illuminate\Console\Command;
use Vinkla\Hashids\Facades\Hashids;

class SetHashId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:hash-id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make sure every user have an hash id';

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
     * @return mixed
     */
    public function handle()
    {
        $users = User::whereNull('hash_id')->get();

        foreach ($users as $user) {
            $user->hash_id = Hashids::encode($user->id);
            $user->save();
        }
    }
}
