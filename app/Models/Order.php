<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'address',
        'phone',
        'status',
        'tracking_number',
        'delivery_proof',
        'delivered_at',
        'payment_proof',
        'discount_percentage',
        'total_discount',
        'total_price',
        'payment_method',
        'is_down_payment',
        'down_payment_amount',
        'remaining_amount',
        'customer_province_id',
        'customer_city_id',
        'shipping_cost',
        'shipping_courier',
        'shipping_service',
        'shipping_weight'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function details()
    {
        return $this->hasMany(OrderDetail::class);
    }
    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';

    // Helper methods for status
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isConfirmed()
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    public function isShipped()
    {
        return $this->status === self::STATUS_SHIPPED;
    }

    public function isDelivered()
    {
        return $this->status === self::STATUS_DELIVERED;
    }

    public function isCompleted()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function canUploadDeliveryProof()
    {
        return $this->isShipped() && !$this->delivery_proof;
    }

    public function hasDeliveryProof()
    {
        return !empty($this->delivery_proof);
    }

    // Cast dates
    protected $casts = [
        'delivered_at' => 'datetime'
    ];
}
