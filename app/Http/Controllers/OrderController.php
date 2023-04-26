<?php

namespace App\Http\Controllers;

use App\Enums\ResponseStatus;
use App\Models\Order;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;
use Throwable;

class OrderController extends Controller
{
    public function create(Request $request): array
    {
        $request->validate([
            'products' => 'required|array',
            'user_id' => 'required|integer',
        ]);

        try {
            $order = new Order();

            $order->user_id = $request['user_id'];
            $order->save();

            $order->products()->sync($request['products']);
            $order->save();


            $order->total_price = $order->calculateTotalPrice()['data'];

            $order->save();

            return ['status' => ResponseStatus::SUCCESS, 'message' => 'Order successfully created', 'data' => $order];
        } catch (Throwable $error) {
            return ['status' => ResponseStatus::ERROR, 'message' => 'Order could not be made at this time', 'error' => $error->getMessage()];
        }
    }

    public function index(): array
    {
        try {
            $orders = Order::get();
            return ['status' => ResponseStatus::SUCCESS, 'message' => 'Orders successfully found', 'data' => $orders];
        } catch (Throwable $error) {
            return ['status' => ResponseStatus::ERROR, 'message' => 'Orders could not be retrieved at this time', 'error' => $error->getMessage()];
        }
    }

    public function indexByUser($id): array
    {
        try {
            $orders = Order::where('user_id', $id)->get();
            return ['status' => ResponseStatus::SUCCESS, 'message' => 'Orders successfully found for user ' . $id, 'data' => $orders];
        } catch (Throwable $error) {
            return ['status' => ResponseStatus::ERROR, 'message' => 'Orders could not be retrieved at this time', 'error' => $error->getMessage()];
        }
    }

    public function show($id): array
    {
        try {
            $order = Order::find($id);
            if($order) {
                return ['status' => ResponseStatus::SUCCESS, 'message' => 'Order successfully found', 'data' => $order];
            } else {
                return ['status' => ResponseStatus::FAILED, 'message' => 'Unable to locate order'];
            }
        } catch (Throwable $error) {
            return ['status' => ResponseStatus::ERROR, 'message' => $error->getMessage()];
        }
    }

    public function delete($id): array
    {
        try {
            $order = Order::find($id)->delete();
            return ['status' => ResponseStatus::SUCCESS, 'message' => 'Order successfully found'];
        } catch (Throwable $error) {
            return ['status' => ResponseStatus::ERROR, 'message' => $error->getMessage()];
        }
    }
}
