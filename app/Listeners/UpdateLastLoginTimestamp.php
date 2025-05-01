<?php

namespace App\Listeners;

use App\Events\UserLoggedIn;

class UpdateLastLoginTimestamp
{
    /**
     * Handle the event.
     */
    public function handle(UserLoggedIn $event): void
    {
        // Kalau mau simpan last_login_at di tabel users
        // Ingat: Tambahin dulu kolom ini ke tabel users

        // $event->user->update([
        //     'last_login_at' => now(),
        //     'last_login_ip' => request()->ip(),
        // ]);

        // Sekarang, catat aja dulu soalnya belum ada kolomnya
        // Ini cuma contoh buat nanti kalau mau diimplementasiin
    }
}