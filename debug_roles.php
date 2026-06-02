<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$roles = \App\Models\User::distinct()->pluck('role')->toArray();
file_put_contents('roles_debug.txt', implode(', ', $roles));
echo "Roles: " . implode(', ', $roles);
