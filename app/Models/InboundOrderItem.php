<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InboundOrderItem extends Model
{
    protected $fillable = ['inbound_order_id', 'product_id', 'expected_quantity', 'received_quantity'];

    public function order(): BelongsTo{

    return $this-> belongsTo(InboundOrder::class,'inbound_order_id');
    }

    public function product() : BelongsTo {

    return $this-> belongsTo(Product::class);
    }

    }
