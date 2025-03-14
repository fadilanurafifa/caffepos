<?php

namespace App\Observers;

use App\Models\Penjualan;
use App\Models\User;
use App\Notifications\StatusPesananNotification;

class PenjualanObserver
{
    /**
     * Handle the Penjualan "created" event.
     */
    public function created(Penjualan $penjualan): void
    {
        //
    }

    /**
     * Handle the Penjualan "updated" event.
     */
    public function updating(Penjualan $penjualan)
    {
        // Cek apakah status pesanan berubah
        if ($penjualan->isDirty('status_pesanan')) {
            $status = $penjualan->status_pesanan;

            // Kirim notifikasi ke kasir
            $kasir = User::where('role', 'kasir')->get();
            foreach ($kasir as $user) {
                $user->notify(new StatusPesananNotification($penjualan, $status));
            }
        }
    }

    /**
     * Handle the Penjualan "deleted" event.
     */
    public function deleted(Penjualan $penjualan): void
    {
        //
    }

    /**
     * Handle the Penjualan "restored" event.
     */
    public function restored(Penjualan $penjualan): void
    {
        //
    }

    /**
     * Handle the Penjualan "force deleted" event.
     */
    public function forceDeleted(Penjualan $penjualan): void
    {
        //
    }
}
