<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Role;
use Illuminate\Support\Facades\Validator;

echo "Testing exact registration process simulation:\n\n";

// Simulate the exact request data that would come from the form
$requestData = [
    'nama' => 'Test Dosen',
    'email' => 'testdosen@example.com',
    'role' => 'Dosen',
    'password' => 'password123',
    'password_confirmation' => 'password123'
];

echo "Request data:\n";
print_r($requestData);
echo "\n";

// Test the validation rules from LoginController
$validator = Validator::make($requestData, [
    'nama' => 'required|string|max:255',
    'email' => 'required|email|unique:users,email',
    'role' => 'required|in:Dosen,Mahasiswa',
    'password' => 'required|string|min:8|confirmed',
]);

if ($validator->fails()) {
    echo "Validation failed:\n";
    print_r($validator->errors()->toArray());
} else {
    echo "✓ Validation passed\n";

    // Test the role lookup exactly as in the controller
    $role = Role::where('nama_role', $requestData['role'])->first();

    if (!$role) {
        echo "✗ Role lookup failed: Role '{$requestData['role']}' not found\n";

        // Debug: show all roles
        echo "Available roles:\n";
        $allRoles = Role::all();
        foreach ($allRoles as $r) {
            echo "  - ID: {$r->id_role}, Name: '{$r->nama_role}'\n";
        }
    } else {
        echo "✓ Role lookup successful: ID {$role->id_role}, Name: '{$role->nama_role}'\n";

        // Test user creation
        try {
            $userData = [
                'nama' => $requestData['nama'],
                'email' => $requestData['email'],
                'password' => password_hash($requestData['password'], PASSWORD_DEFAULT),
                'role_id' => $role->id_role,
            ];

            echo "✓ User data ready for creation:\n";
            print_r($userData);

        } catch (Exception $e) {
            echo "✗ User creation failed: " . $e->getMessage() . "\n";
        }
    }
}
