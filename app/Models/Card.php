<?php

namespace App\Models;

use App\Traits\HasIdentifier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Card extends Model
{
    use HasFactory, SoftDeletes, HasIdentifier;

    protected $fillable = [
        'identifier',
        'last_used',
    ];

    protected $relations = [
        'user_id',
        'card_type'
    ];

    protected $casts = [
        'identifier' => 'string',
        'last_used' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(CardType::class, 'card_type');
    }
}
