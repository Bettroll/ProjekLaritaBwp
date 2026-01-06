<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // Karena tidak pakai migrasi, kita kasih tahu nama tabelnya manual
    protected $table = 'users';

    // Kolom yang boleh diisi (Mass Assignment)
    protected $fillable = [
        'name', 
        'email', 
        'password', 
        'role', 
        'points', 
        'phone', 
        'status'
    ];

    // Sembunyikan password saat data user dipanggil
    protected $hidden = [
        'password',
    ];
}