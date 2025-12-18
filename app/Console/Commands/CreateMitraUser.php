<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateMitraUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-mitra-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Update existing owner user password
        $user = \App\Models\User::where('email', 'owner@livora.com')->first();
        if ($user) {
            $user->update(['password' => bcrypt('password')]);
            $this->info('Owner user password reset successfully!');
        } else {
            $this->error('Owner user not found!');
        }
        
        return 0;
    }
}
