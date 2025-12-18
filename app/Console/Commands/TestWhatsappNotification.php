<?php

namespace App\Console\Commands;

use App\Services\WhatsappService;
use Illuminate\Console\Command;

class TestWhatsappNotification extends Command
{
  /**
   * The name and signature of the console command.
   *
   * @var string
   */
  protected $signature = 'whatsapp:test {number : Nomor WhatsApp tujuan (dimulai dengan 08)} {--message=Test message}';

  /**
   * The description of the command.
   *
   * @var string
   */
  protected $description = 'Test WhatsApp notification service';

  /**
   * Execute the console command.
   */
  public function handle()
  {
    $number = $this->argument('number');
    $message = $this->option('message');

    $this->info("🚀 Mengirim pesan WhatsApp...");
    $this->info("📱 Nomor: $number");
    $this->info("💬 Pesan: $message");
    $this->newLine();

    try {
      $service = new WhatsappService();
      $response = $service->sendMessage($number, $message);

      $this->info("✅ Pesan berhasil dikirim!");
      $this->info("Response: " . json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

      return self::SUCCESS;
    } catch (\Exception $e) {
      $this->error("❌ Gagal mengirim pesan:");
      $this->error($e->getMessage());

      return self::FAILURE;
    }
  }
}
