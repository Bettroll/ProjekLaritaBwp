<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // <--- Tambahkan ini

class Location extends Model {
    use SoftDeletes; // <--- Gunakan ini

    protected $table = 'locations';
    protected $fillable = ['location_name', 'address', 'is_active'];
    protected $dates = ['deleted_at']; // <--- Beritahu Laravel ini kolom tanggal
    public $timestamps = true; // Aktifkan timestamps agar created_at & updated_at otomatis
}