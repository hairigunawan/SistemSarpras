<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Role;
use Illuminate\Support\Facades\DB;

echo "Debugging registration process:\n\n";

// Test exact values that might come from the form
$testValues = ['Dosen', 'Mahasiswa', 'dosen', 'mahasiswa', 'DOSEN', 'MAHASISWA'];

foreach ($testValues as $value) {
    $role = Role::where('nama_role', $value)->first();

    echo "Testing value: '{$value}'\n";
    echo "  Length: " . strlen($value) . " characters\n";
    echo "  Hex: " . bin2hex($value) . "\n";

    if ($role) {
        echo "  ✓ Found: ID {$role->id_role}, Name: '{$role->nama_role}'\n";
        echo "  Stored length: " . strlen($role->nama_role) . " characters\n";
        echo "  Stored hex: " . bin2hex($role->nama_role) . "\n";
    } else {
        echo "  ✗ NOT found!\n";
    }
    echo "\n";
}

// Check database collation
echo "Database collation information:\n";
try {
    $collation = DB::select("SELECT COLLATION('Dosen') as collation");
    echo "  Collation for 'Dosen': " . $collation[0]->collation . "\n";
} catch (Exception $e) {
    echo "  Could not get collation info: " . $e->getMessage() . "\n";
}

// Check if there are any hidden characters in the database
echo "\nChecking for hidden characters in database:\n";
$roles = Role::all();
foreach ($roles as $role) {
    $hex = bin2hex($role->nama_role);
    echo "Role '{$role->nama_role}': HEX = {$hex}\n";
    if (preg_match('/[^a-zA-Z]/', $role->nama_role)) {
        echo "  WARNING: Contains non-alphabet characters!\n";
    }
}
