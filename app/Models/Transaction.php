<?php

namespace App\Models;

use Carbon\Carbon;
use Throwable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\ResponseStatus;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'completed_at'
    ];

    protected $relations = [
        'user_id',
        'order_id',
        'transaction_type'
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function transactionType(): BelongsTo
    {
        return $this->belongsTo(TransactionType::class, 'transaction_type');
    }

    public function completeTransaction(): array
    {
        try {
            $this->completed_at = Carbon::now();
            $this->save();
            return ['status' => ResponseStatus::SUCCESS, 'message' => 'Transaction ' . $this->id . ' | Transaction completed at ' . Carbon::parse($this->completed_at)->format('jS M Y, H:i')];
        } catch(Throwable $error) {
            return ['status' => ResponseStatus::ERROR, 'location' => 'App\Models\Transaction@completeTransaction', 'message' => $error->getMessage()];
        }
    }
}
