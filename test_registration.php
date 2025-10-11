<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Role;

// Simulate the registration process
echo "Testing role lookup:\n";

// Test Dosen role lookup
$dosenRole = Role::where('nama_role', 'Dosen')->first();
if ($dosenRole) {
    echo "✓ Dosen role found: ID {$dosenRole->id_role}\n";
} else {
    echo "✗ Dosen role NOT found!\n";
}

// Test Mahasiswa role lookup
$mahasiswaRole = Role::where('nama_role', 'Mahasiswa')->first();
if ($mahasiswaRole) {
    echo "✓ Mahasiswa role found: ID {$mahasiswaRole->id_role}\n";
} else {
    echo "✗ Mahasiswa role NOT found!\n";
}

// Test case sensitivity
echo "\nTesting case sensitivity:\n";
$dosenLower = Role::where('nama_role', 'dosen')->first();
if ($dosenLower) {
    echo "✓ 'dosen' (lowercase) found: ID {$dosenLower->id_role}\n";
} else {
    echo "✗ 'dosen' (lowercase) NOT found!\n";
}

$dosenUpper = Role::where('nama_role', 'DOSEN')->first();
if ($dosenUpper) {
    echo "✓ 'DOSEN' (uppercase) found: ID {$dosenUpper->id_role}\n";
} else {
    echo "✗ 'DOSEN' (uppercase) NOT found!\n";
}

// Show all roles for reference
echo "\nAll roles in database:\n";
$allRoles = Role::all();
foreach ($allRoles as $role) {
    echo "ID: {$role->id_role}, Name: '{$role->nama_role}'\n";
}
