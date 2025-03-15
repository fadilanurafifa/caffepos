<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;

class PesananMasukChefNotification extends Notification
{
    use Queueable;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast']; // Disimpan di database dan dikirimkan secara real-time
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "Pesanan baru telah dibayar! No. Faktur: " . $this->order->invoice_no,
            'order_id' => $this->order->id,
            'invoice_no' => $this->order->invoice_no,
            'status' => 'Siap Dimasak'
        ];
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage([
            'message' => "Pesanan baru telah dibayar! No. Faktur: " . $this->order->invoice_no,
            'order_id' => $this->order->id,
            'invoice_no' => $this->order->invoice_no,
            'status' => 'Siap Dimasak'
        ]);
    }
}
