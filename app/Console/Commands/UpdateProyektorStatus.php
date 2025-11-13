<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Helpers\ProyektorStatusHelper;

class UpdateProyektorStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'proyektor:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perbarui status proyektor berdasarkan peminjaman aktif';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Memperbarui status proyektor...');

        // Panggil helper untuk memperbarui status
        ProyektorStatusHelper::updateProyektorStatus();

        $this->info('Status proyektor berhasil diperbarui!');

        return 0;
    }
}
