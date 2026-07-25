<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OutboundOrderItem extends Model
{
    protected $fillable = ['outbound_order_id', 'product_id', 'quantity'];

    public function order(): BelongsTo{

    return $this->belongsTo(OutboundOrder::class, 'outbound_order_id');
    }
    public function product() : BelongsTo{

    return $this-> belongsTo(Product::class); 
    }

    }
