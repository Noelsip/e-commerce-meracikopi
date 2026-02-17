<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Payments;
use App\Models\Orders;
use App\Enums\StatusPayments;
use App\Enums\OrderStatus;

// Gunakan pembayaran terakhir yang dibuat
$latestPayment = Payments::latest()->first();

if (!$latestPayment) {
    die("Belum ada pembayaran yang ditemukan.\n");
}

$invoiceNumber = $latestPayment->transaction_id;

echo "Mengupdate status pembayaran untuk: $invoiceNumber\n";
echo "Metode: " . $latestPayment->payment_method . "\n";
echo "Status Awal: " . $latestPayment->status->value . "\n";

try {
    DB::transaction(function () use ($latestPayment, $invoiceNumber) {
        // Update Payment ke PAID
        $latestPayment->status = StatusPayments::PAID;
        $latestPayment->paid_at = now();
        $latestPayment->payload = array_merge($latestPayment->payload ?? [], [
            'manual_update' => true,
            'updated_at_manual' => now()->toIso8601String()
        ]);
        $latestPayment->save();

        echo "Payment status berhasil diubah ke PAID.\n";

        // Update Order ke PAID juga
        $order = Orders::find($latestPayment->order_id);
        if ($order) {
            $order->status = OrderStatus::PAID;
            $order->save();
            echo "Order berhasil diubah ke PAID (Order ID: {$order->id}).\n";
        } else {
            echo "Order tidak ditemukan.\n";
        }
    });

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}