<?php

require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

echo "=== Order Table Columns ===\n";
echo implode(', ', Schema::getColumnListing('orders')) . "\n\n";

echo "=== Delivery Table Columns ===\n";
echo implode(', ', Schema::getColumnListing('deliveries')) . "\n\n";

echo "=== Order Statuses ===\n";
$statuses = DB::select('select status, count(*) as total from orders group by status');
foreach ($statuses as $s) {
    echo "  {$s->status}: {$s->total}\n";
}

echo "\n=== Delivery Statuses ===\n";
$dstatuses = DB::select('select delivery_status, count(*) as total from deliveries group by delivery_status');
foreach ($dstatuses as $s) {
    echo "  {$s->delivery_status}: {$s->total}\n";
}

echo "\n=== Sample Order (with delivery & status) ===\n";
$sample = DB::table('orders')
    ->join('deliveries', 'orders.id_order', '=', 'deliveries.order_id')
    ->select('orders.id_order', 'orders.status', 'orders.created_at', 'deliveries.delivery_status')
    ->limit(5)
    ->get();
foreach ($sample as $s) {
    echo "  Order#{$s->id_order} | status={$s->status} | delivery_status={$s->delivery_status} | created_at={$s->created_at}\n";
}

echo "\n=== Orders with paid status AND delivered ===\n";
$count = DB::table('orders')
    ->join('deliveries', 'orders.id_order', '=', 'deliveries.order_id')
    ->where('orders.status', 'paid')
    ->where('deliveries.delivery_status', 'delivered')
    ->count();
echo "  Count: $count\n";

echo "\nDone.\n";
