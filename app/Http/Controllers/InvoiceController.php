<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf; // Import DomPDF Facade

class InvoiceController extends Controller
{
    public function generateInvoicePdf($orderId)
    {
        $order = Order::with('orderDetails.product')
            ->where('user_id', Auth::id())
            ->where('id', $orderId)
            ->firstOrFail();

        $overallOriginalSubtotal = 0;
        foreach ($order->orderDetails as $item) {
            $overallOriginalSubtotal += $item->size->price * $item->quantity;
        }

        $data = [
            'order' => $order,
            'overallOriginalSubtotal' => $overallOriginalSubtotal,
        ];

        $pdf = Pdf::loadView('user.invoice-pdf', $data);

        return $pdf->download('invoice_order_' . $order->id . '.pdf');
    }
}
