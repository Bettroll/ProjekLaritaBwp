<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    protected $fillable = [
        'user_id', 'location_id', 'invoice_number', 'total_price', 
        'discount_amount', 'final_price', 'points_earned', 
        'shipping_method', 'shipping_details', 'status'
    ];

    public $timestamps = true;

    // Relasi ke User
    public function user() {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Detail
    public function details() {
        return $this->hasMany(TransactionDetail::class);
    }
}