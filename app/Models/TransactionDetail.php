<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransactionDetail extends Model
{
    protected $table = 'transaction_details';
    public $timestamps = false; // Karena biasanya detail tidak butuh created_at sendiri
    protected $fillable = [
        'transaction_id', 'product_id', 'price_at_purchase', 'quantity', 'subtotal'
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }
}