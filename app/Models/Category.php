<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // 1. Import Trait SoftDeletes

class Category extends Model
{
    use SoftDeletes; // 2. Gunakan Trait SoftDeletes

    protected $table = 'categories';

    protected $fillable = [
        'category_name'
    ];

    // 3. Beritahu Laravel bahwa kolom deleted_at adalah tipe tanggal
    protected $dates = ['deleted_at'];

    // Jika di tabel SQL kamu tidak ada kolom updated_at, set ini ke false
    // Tapi saran saya, sebaiknya aktifkan true agar sinkron dengan fitur SoftDeletes
    public $timestamps = true; 
}