<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class StatusPesananNotification extends Notification
{
    use Queueable;

    protected $order;
    protected $status;

    public function __construct($order, $status)
    {
        $this->order = $order;
        $this->status = $status;
    }

    public function via($notifiable)
    {
        return ['database']; // Simpan notifikasi di database
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'message' => "Pesanan #{$this->order->no_faktur} sekarang: {$this->status}"
        ];
    }
}

