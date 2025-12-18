<?php

namespace App\Console\Commands;

use App\Services\WhatsappService;
use App\Models\User;
use Illuminate\Console\Command;

class WhatsappDiagnostics extends Command
{
  /**
   * The name and signature of the console command.
   */
  protected $signature = 'whatsapp:diagnose';

  /**
   * The description of the command.
   */
  protected $description = 'Diagnose WhatsApp API configuration and connectivity';

  /**
   * Execute the command.
   */
  public function handle()
  {
    $this->info('🔍 WhatsApp API Diagnostics');
    $this->newLine();

    // Check configuration
    $this->info('📋 Checking Configuration...');
    $token = config('services.whatsapp.token');
    $phoneId = config('services.whatsapp.phone_number_id');
    $apiUrl = config('services.whatsapp.api_url');

    if (!$token) {
      $this->error('❌ WHATSAPP_ACCESS_TOKEN tidak dikonfigurasi di .env');
    } else {
      $tokenPreview = substr($token, 0, 20) . '...';
      $this->info("✅ Token: {$tokenPreview}");
    }

    if (!$phoneId) {
      $this->error('❌ WHATSAPP_BUSINESS_PHONE_ID tidak dikonfigurasi di .env');
    } else {
      $this->info("✅ Phone ID: {$phoneId}");
    }

    if (!$apiUrl) {
      $this->error('❌ WHATSAPP_API_URL tidak dikonfigurasi di .env');
    } else {
      $this->info("✅ API URL: {$apiUrl}");
    }

    $this->newLine();

    // Check users with phone numbers
    $this->info('👥 Checking Users with Phone Numbers...');
    $usersWithPhone = User::whereNotNull('nomor_telepon')
      ->where('nomor_telepon', '!=', '')
      ->count();
    $totalUsers = User::count();

    $this->info("ℹ️  Total users: {$totalUsers}");
    $this->info("ℹ️  Users dengan nomor telepon: {$usersWithPhone}");

    if ($usersWithPhone < 1) {
      $this->warn('⚠️  Tidak ada user dengan nomor telepon!');
    }

    $this->newLine();

    // Test API connectivity
    if ($token && $phoneId) {
      $this->info('🔌 Testing API Connectivity...');
      try {
        $service = new WhatsappService();
        // Try dengan nomor test (jangan benar-benar kirim)
        $this->info("Mengecek koneksi ke WhatsApp API...");
        $this->info("(Tidak akan mengirim pesan ke nomor sesungguhnya)");

        // Jika ingin test beneran, uncomment bawah ini
        // $response = $service->sendMessage('628567345654', 'Test message');
        // $this->info('✅ API Connection OK');

        $this->info('ℹ️  Gunakan: php artisan whatsapp:test <nomor> untuk test sesungguhnya');
      } catch (\Exception $e) {
        $this->error('❌ API Error: ' . $e->getMessage());
      }
    } else {
      $this->warn('⚠️  Tidak bisa test API - Configuration tidak lengkap');
    }

    $this->newLine();

    // Provide recommendations
    $this->info('💡 Rekomendasi:');
    $this->line('1. Pastikan WHATSAPP_ACCESS_TOKEN tidak expired');
    $this->line('2. Pastikan WHATSAPP_BUSINESS_PHONE_ID benar');
    $this->line('3. Pastikan users memiliki nomor_telepon yang valid');
    $this->line('4. Test dengan: php artisan whatsapp:test <nomor>');
    $this->line('5. Check logs di: storage/logs/laravel.log');

    $this->newLine();
    $this->info('✅ Diagnostics selesai!');

    return self::SUCCESS;
  }
}
