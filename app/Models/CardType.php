<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CardType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type'
    ];

    protected $casts = [
        'type' => 'string',
    ];

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class, 'card_type');
    }
}
