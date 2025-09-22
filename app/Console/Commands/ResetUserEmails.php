<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetUserEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:reset-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ganti email lama @simpeg.com ke @skysimpeg.com dan reset password ke default';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $defaultPassword = env('DEFAULT_USER_PASSWORD');

        $users = User::where('email', 'like', '%@simpeg.com')->get();

        if ($users->isEmpty()) {
            $this->info('Tidak ada user dengan email @simpeg.com');
            return 0;
        }

        foreach ($users as $user) {
            $emailParts = explode('@', $user->email);
            $user->email = $emailParts[0] . '@skysimpeg.com';
            // $user->password = Hash::make($defaultPassword);
            $user->remember_token = null;
            $user->save();

            $this->info("User {$emailParts[0]} berhasil diubah → {$user->email}");
        }

        $this->info('Semua user @simpeg.com berhasil di-reset.');
        return 0;
    }
}
