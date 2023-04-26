<?php

namespace App\Models;

use App\Enums\ResponseStatus;
use Carbon\Carbon;
use Throwable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'total_price',
        'user_id',
        'completed_at',
    ];

    protected $casts = [
        'total_price' => 'double',
        'user_id' => 'integer',
        'completed_at' => 'datetime',
    ];

    public function transaction(): HasMany
    {
        return $this->hasMany(Transaction::class, 'transaction_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_product', 'order_id', 'product_id');
    }

    public function calculateTotalPrice(): array
    {
        $products = $this->products()->get();
        $runningTotal = 0.0;

        try {
            foreach ($products as $product) {
                $runningTotal += $product->price;
            };
            $this->total_price = $runningTotal;
            $this->save();
            return ['status' => ResponseStatus::SUCCESS, 'message' => $this->name . ' | Total cost calculated: ' . $runningTotal, 'data' => $runningTotal];
        } catch(Throwable $error) {
            return ['status' => ResponseStatus::ERROR, 'location' => 'App/Models/Order@calculateTotalPrice', 'message' => $error->getMessage()];
        }
    }

    public function completeOrder(): array
    {
        try {
            $this->completed_at = Carbon::now();
            $this->save();
            return ['status' => ResponseStatus::SUCCESS, 'message' => 'Order ' . $this->id . ' | Order completed at ' . Carbon::parse($this->completed_at)->format('jS M Y, H:i')];
        } catch(Throwable $error) {
            return ['status' => ResponseStatus::ERROR, 'location' => 'App/Models/Order@completeOrder', 'message' => $error->getMessage()];
        }
    }

    public function quantify(): array {
        try {
            return ['status' => ResponseStatus::SUCCESS, 'message' => 'Products successfully quantified', 'data' => $this->products()->get()->countBy('id')];
        } catch(Throwable $error) {
            return ['status' => ResponseStatus::ERROR, 'location' => 'App/Models/Order@quantify', 'message' => $error->getMessage()];
        }
    }


}
