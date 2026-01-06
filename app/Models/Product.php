<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'products';
    protected $fillable = ['category_id', 'product_name', 'description', 'image'];
    protected $dates = ['deleted_at'];

    // Relasi ke Kategori (Roti ini punya satu kategori)
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function locations() {
        return $this->belongsToMany(Location::class, 'location_product')
                    ->withPivot('price', 'stock') // Supaya data harga & stok bisa diambil
                    ->withTimestamps();
    }

        // Tambahkan ini di dalam class Product
    public function likes()
    {
        return $this->hasMany(ProductLike::class, 'product_id');
    }
}