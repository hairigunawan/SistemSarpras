<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Peminjaman;
use Database\Seeders\AdminSeeder;
use Database\Seeders\StatusSeeder;
use Database\Seeders\LokasiSeeder;
use Database\Seeders\RuanganProyektorSeeder;

class LaporanPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_access_laporan_page()
    {
        // Run seeders to setup roles and initial data
        $this->seed([
            AdminSeeder::class,
            StatusSeeder::class,
            LokasiSeeder::class,
            RuanganProyektorSeeder::class,
        ]);

        $admin = User::where('email', 'admin@gmail.com')->first();

        // Create some peminjaman data to trigger the calculation
        // We need to ensure dates are within the default "perbulan" range (current month)
        // or we can just visit the page, it handles empty data too (should return 0)
        
        $response = $this->actingAs($admin)
                         ->get(route('laporan.index'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.laporan.index');
    }
}
