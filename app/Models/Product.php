<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'category_id', 'unit', 'description', 'price', 'image'];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    public function sizes()
    {
        return $this->hasMany(ProductSize::class);
    }
}
