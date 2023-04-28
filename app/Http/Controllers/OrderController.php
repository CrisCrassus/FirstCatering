<?php

namespace App\Http\Controllers;

use App\Enums\ResponseStatus;
use App\Models\Order;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\HasResponseStatus;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class OrderController extends Controller
{

    use HasResponseStatus;

    public function create(Request $request): JsonResponse
    {
        $request->validate([
            'products' => 'required|array',
            'user_id' => 'required|integer',
        ]);

        try {
            $order = new Order();

            if (!User::find($request['user_id'])) {
                return $this->responseStatus(ResponseStatus::FAILED, 'Unable to locate user');
            }

            $order->user_id = $request['user_id'];
            $order->save();

            foreach ($request['products'] as $product) {
                if (!Product::find($product)) {
                    return $this->responseStatus(ResponseStatus::FAILED, 'Unable to locate product');
                }
            }

            $order->products()->sync($request['products']);
            $order->save();


            $order->total_price = $order->calculateTotalPrice()['data'];

            $order->save();

            return $this->responseStatus(ResponseStatus::SUCCESS, 'Order ' . $order->id . ' successfully created', $order);
        } catch (Throwable $error) {
            return $this->responseStatus(ResponseStatus::ERROR, $error->getMessage(), ['location' => 'App\Models\Controllers\OrderController@create']);
        }
    }

    public function index(): JsonResponse
    {
        try {
            $orders = Order::get();
            return $this->responseStatus(ResponseStatus::SUCCESS, 'Orders successfully found', $orders);
        } catch (Throwable $error) {
            return $this->responseStatus(ResponseStatus::ERROR, $error->getMessage());
        }
    }

    public function indexByUser($id): JsonResponse
    {
        try {
            $orders = Order::where('user_id', $id)->get();
            return $this->responseStatus(ResponseStatus::SUCCESS, 'Orders successfully found for user ' . $id, $orders);
        } catch (Throwable $error) {
            return $this->responseStatus(ResponseStatus::ERROR, $error->getMessage());
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $order = Order::find($id);
            if ($order) {
                return $this->responseStatus(ResponseStatus::SUCCESS, 'Order found', $order);
            } else {
                return $this->responseStatus(ResponseStatus::FAILED, 'Unable to locate order');
            }
        } catch (Throwable $error) {
            return $this->responseStatus(ResponseStatus::ERROR, $error->getMessage());
        }
    }

    public function delete($id): JsonResponse
    {
        try {
            Order::find($id)->delete();
            return $this->responseStatus(ResponseStatus::SUCCESS, 'Order successfully found');
        } catch (Throwable $error) {
            return $this->responseStatus(ResponseStatus::ERROR, $error->getMessage());
        }
    }
}
