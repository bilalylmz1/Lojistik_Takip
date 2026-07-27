<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OutboundOrder extends Model
{
    protected $fillable = ['order_number', 'status'];

    public function items(): HasMany
    {
        return $this->hasMany(OutboundOrderItem::class);
    }

    public function statusLabelTr(): string
    {
        return match ($this->status) {
            'pending' => 'Bekliyor',
            'picking' => 'Toplanıyor',
            'ready'   => 'Hazır',
            'shipped' => 'Sevk Edildi',
            default   => ucfirst($this->status),
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300',
            'picking' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300',
            'ready'   => 'bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300',
            'shipped' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300',
            default   => 'bg-zinc-100 text-zinc-700',
        };
    }
}
