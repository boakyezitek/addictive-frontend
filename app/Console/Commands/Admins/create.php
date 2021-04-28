<?php

namespace App\Console\Commands\Admins;

use App\Models\Admin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class Create extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create an admin user.';

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
        $input = [];

        $input['first_name'] = $this->ask('What is your first name?');
        $input['last_name'] = $this->ask('What is your last name?');
        $input['email'] = $this->ask('What is your email?');
        $input['password'] = $this->secret('What is your password?');

        $validator = Validator::make($input, [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:admins',
            'password'   => 'required|string',
        ]);

        if ($validator->fails()) {
            throw new \RuntimeException($validator->errors()->first());
        }

        $admin = new Admin;
        $admin->fill($input);
        $admin->password = bcrypt($input['password']);
        $admin->save();

        $this->info('The admin has been successfully created.');
    }
}
