<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    protected $fillable = ['name', 'code', 'type', 'parent_id'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Location::class, 'parent_id');
    }

    public function stocks(): HasMany
    {
        return $this->hasMany(Stock::class);
    }

    public function typeLabelTr(): string
    {
        return match ($this->type) {
            'area'  => 'Alan',
            'aisle' => 'Koridor',
            'rack'  => 'Raf',
            'bin'   => 'Göz',
            default => ucfirst($this->type),
        };
    }
}