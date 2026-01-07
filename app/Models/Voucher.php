<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use SoftDeletes;

    protected $table = 'vouchers';
    protected $fillable = [
        'voucher_name', 
        'discount_percent', 
        'min_purchase', 
        'points_needed', 
        'quota'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'user_vouchers');
    }
}