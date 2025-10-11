<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Role;

echo "Checking roles table contents:\n";
$roles = Role::all();

if ($roles->count() > 0) {
    foreach ($roles as $role) {
        echo "ID: {$role->id_role}, Name: {$role->nama_role}\n";
    }
} else {
    echo "No roles found in the database!\n";
}
