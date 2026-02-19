<?php

use App\Models\User;
use Spatie\Permission\Models\Role;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Ensure roles exist
if (Role::count() == 0) {
    echo "Creating super_admin role...\n";
    Role::create(['name' => 'super_admin', 'guard_name' => 'web']);
}

$user = User::where('email', 'admin@example.com')->first();

if ($user) {
    echo 'Found user: '.$user->name."\n";
    if (! $user->hasRole('super_admin')) {
        $user->assignRole('super_admin');
        echo "Assigned super_admin role to user.\n";
    } else {
        echo "User already has super_admin role.\n";
    }
} else {
    echo "User admin@example.com not found.\n";
}
