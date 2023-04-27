<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Throwable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Enums\ResponseStatus;
use App\Enums\TransactionTypes;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'company_id',
        'role_id',
        'email',
        'balance',
        'password',
        'mobile',
        'pin'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'balance' => 'double',
        'first_name' => 'string',
        'last_name' => 'string',
    ];

    public function cards(): HasMany
    {
        return $this->hasMany(Card::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function name(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function validatePIN(int $pin): array
    {
        try {
            if ($pin === $this->pin) {
                return ['status' => ResponseStatus::SUCCESS, 'message' => 'PIN Approved', 'token' => $this->createToken($this->name())->plainTextToken, 'data' => true];
            } else {
                return ['status' => ResponseStatus::FAILED, 'message' => 'PIN Incorrect', 'data' => false];
            }
        } catch (Throwable $error) {
            return ['status' => ResponseStatus::ERROR, 'location' => 'App\Models\User@validatePIN', 'message' => $error->getMessage()];
        }
    }

    public function processTransaction(Transaction $transaction, Order $order = null): array
    {
        try {
            if ($transaction->transactionType()->first()->type == TransactionTypes::TOPUP->value) {
                $this->balance += $transaction->amount;
                $this->save();
                $transaction->completeTransaction();
                return ['status' => ResponseStatus::SUCCESS, 'message' => $this->name() . ' | Your balance has been updated - you have added £' . $transaction->amount];
            }

            if ($transaction->transactionType()->first()->type == TransactionTypes::PURCHASE->value) {
                if ($order) {
                    $quantities = $order->quantify()['data'];

                    $this->balance -= $transaction->amount;
                    $this->save();
                    foreach ($quantities as $prod => $quantity) {
                        Product::find($prod)->decreaseQuantity($quantity);
                    }
                    $transaction->completeTransaction();
                    $order->completeOrder();
                    return ['status' => ResponseStatus::SUCCESS, 'message' => 'Your balance has been updated - you have spent £' . $transaction->amount];
                } else {
                    return ['status' => ResponseStatus::FAILED, 'message' => 'An order has not been provided. Please provide an order'];
                }
            }
        } catch (Throwable $error) {
            return ['status' => ResponseStatus::ERROR, 'location' => 'App\Models\User@processTransaction', 'message' => $error->getMessage()];
        }
    }
}
