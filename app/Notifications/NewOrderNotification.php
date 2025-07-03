<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class NewOrderNotification extends Notification
{
    use Queueable;

    protected $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database']; // Hanya menggunakan database
    }

    public function toDatabase($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'title' => 'Order Baru',
            'message' => 'Order baru dari ' . $this->order->name,
            'customer_name' => $this->order->name,
            'total_price' => $this->order->total_price,
            'order_date' => $this->order->created_at->format('d M Y H:i'),
            'status' => $this->order->status,
        ];
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'title' => 'Order Baru',
            'message' => 'Order baru dari ' . $this->order->name,
            'customer_name' => $this->order->name,
            'total_price' => $this->order->total_price,
        ];
    }
}