<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchased extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public $table = 'product_user';

    /*public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }*/
}
