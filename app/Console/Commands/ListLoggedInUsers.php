<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class ListLoggedInUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:logged-in';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Menampilkan daftar email user yang pernah login';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::whereNotNull('last_login_at')
            ->orderBy('last_login_at', 'desc')
            ->get(['id_akun', 'nama', 'email', 'last_login_at']);

        if ($users->isEmpty()) {
            $this->info('Belum ada data user yang login.');
            return;
        }

        $this->table(
            ['ID', 'Nama', 'Email', 'Login Terakhir'],
            $users->toArray()
        );
    }
}