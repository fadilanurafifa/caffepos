<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;

class StatusPesananNotification extends Notification
{
    use Queueable; // Memungkinkan notifikasi untuk dimasukkan ke dalam antrean (queue)

    protected $order; // Variabel untuk menyimpan data pesanan
    protected $status; // Variabel untuk menyimpan status pesanan

    // Konstruktor untuk menerima data pesanan dan statusnya
    public function __construct($order, $status)
    {
        $this->order = $order; // Menyimpan data pesanan ke variabel class
        $this->status = $status; // Menyimpan status pesanan ke variabel class
    }

    // Menentukan metode pengiriman notifikasi
    public function via($notifiable)
    {
        return ['database']; // Notifikasi akan disimpan di database
    }

    // Menentukan data yang akan disimpan dalam notifikasi di database
    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id, // Menyimpan ID pesanan
            'message' => "Pesanan #{$this->order->no_faktur} sekarang: {$this->status}" // Pesan notifikasi
        ];
    }
}
