<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ProductLike extends Model {
    protected $table = 'product_likes';
    protected $fillable = ['user_id', 'product_id', 'location_id'];
    public $timestamps = false;
}