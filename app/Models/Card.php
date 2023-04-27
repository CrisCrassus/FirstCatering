<?php

namespace App\Models;

use App\Enums\ResponseStatus;
use App\Traits\HasIdentifier;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Throwable;

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

    public function assignCard(User $user) : array
    {
        try {
            if($user) {
                $this->user_id = $user->id;
                return ['status' => ResponseStatus::SUCCESS, 'message' => 'Card ' . $this->identifier . ' assign to user ' . $user->id, 'data' => $this];
            } else {
                return ['status' => ResponseStatus::FAILED, 'message' => 'Unable to locate user'];
            }
        } catch(Throwable $error) {
            return ['status' => ResponseStatus::ERROR, 'location' => 'App\Models\Card@assignCard', 'message' => $error->getMessage()];
        }
    }
}
