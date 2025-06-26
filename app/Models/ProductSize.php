<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSize extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'size', 'price', 'is_active', 'unit'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}