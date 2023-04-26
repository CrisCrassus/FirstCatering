<?php

namespace App\Models;

use App\Traits\HasIdentifier;
use Throwable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Enums\ResponseStatus;

class Product extends Model
{
    use HasFactory, SoftDeletes, HasIdentifier;

    protected $fillable = [
        'name',
        'price',
        'quantity',
        'identifier'
    ];

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_product', 'product_id', 'order_id');
    }

    public function increaseQuantity(int $amount = 1): array
    {
        $this->quantity += $amount;
        try {
            $this->quantity += $amount;
            return ['status' => ResponseStatus::SUCCESS, 'message' => $this->product_name . ' | Quantity : ' . $this->quantity . ' | Increased by : ' . $this->amount];
        } catch (Throwable $error) {
            return ['status' => ResponseStatus::ERROR, 'location' => 'App\Models\Product@increaseQuantity', 'message' => $error->getMessage()];
        }
    }

    public function decreaseQuantity(int $amount = 1): array
    {
        try {
            if ($this->quantity > 0) {
                $this->quantity -= $amount;
                return ['status' => ResponseStatus::SUCCESS, 'message' => $this->product_name . ' | Quantity : ' . $this->quantity . ' | Reduced by : ' . $this->amount];
            } else {
                return ['status' => ResponseStatus::FAILED, 'message' => $this->product_name . ' | Quantity already at 0'];
            }
        } catch (Throwable $error) {
            return ['status' => ResponseStatus::ERROR, 'location' => 'App\Models\Product@decreaseQuantity', 'message' => $error->getMessage()];
        }
    }


}
