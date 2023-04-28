<?php

namespace App\Http\Controllers;

use App\Enums\ResponseStatus;
use App\Enums\TransactionTypes;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\TransactionType;
use App\Models\User;
use App\Traits\HasResponseStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class TransactionController extends Controller
{

    use HasResponseStatus;

    public function purchase(Request $request): JsonResponse
    {
        $request->validate([
            'order_id' => 'required|integer',
        ]);


        try {
            $order = Order::find($request['order_id']);

            if (!$order) {
                return $this->responseStatus(ResponseStatus::FAILED, 'Unable to locate Order');
            }

            $user = $order->user()->first();

            if ($user->balance >= $order->total_price) {

                $transaction = new Transaction();

                $transaction->transaction_type = TransactionType::where('type', TransactionTypes::PURCHASE->value)->first()->id;
                $transaction->amount = $order->total_price;
                $transaction->order_id = $request['order_id'];
                $transaction->user_id = $request['user_id'];

                $transaction->save();

                $transactionProcess = $user->processTransaction($transaction, $order);

                if ($transactionProcess['status'] == ResponseStatus::SUCCESS) {
                    return $this->responseStatus(ResponseStatus::SUCCESS, 'Transaction Complete | ' . $transactionProcess['message'], $transaction);
                }

                if ($transactionProcess['status'] == ResponseStatus::FAILED) {
                    return $this->responseStatus(ResponseStatus::FAILED, 'Transaction Failed | ' . $transactionProcess['message']);
                }

                if ($transactionProcess['status'] == ResponseStatus::ERROR) {
                    return $this->responseStatus(ResponseStatus::ERROR, 'Transaction Failed | ' . $transactionProcess['message'], ['location' => $transactionProcess['location']]);
                }
            } else {
                return $this->responseStatus(ResponseStatus::FAILED, 'Transaction Failed | You do not have sufficient balance to make this transactions');
            }
        } catch (Throwable $error) {
            return $this->responseStatus(ResponseStatus::ERROR, $error->getMessage(), ['location' => 'App\Http|Controllers\TransactionController@purchase']);
        }
    }

    public function topup(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer',
            'amount' => 'required',
        ]);

        $transaction = new Transaction();

        try {

            $user = User::find($request['user_id']);

            if(!$user) {
                return $this->responseStatus(ResponseStatus::FAILED, 'Unable to locate user');
            }

            $transaction->transaction_type = TransactionType::where('type', TransactionTypes::TOPUP->value)->first()->id;
            $transaction->amount = $request['amount'];
            $transaction->user_id = $request['user_id'];

            $transaction->save();

            $transactionProcess = $user->processTransaction($transaction);

            if ($transactionProcess['status'] == ResponseStatus::SUCCESS) {
                return ['status' => ResponseStatus::SUCCESS, 'message' => 'Transaction Complete | ' . $transactionProcess['message'], 'data' => $transaction];
            }

            if ($transactionProcess['status'] == ResponseStatus::FAILED) {
                return ['status' => ResponseStatus::FAILED, 'message' => 'Transaction Failed | ' . $transactionProcess['message']];
            }

            if ($transactionProcess['status'] == ResponseStatus::ERROR) {
                return ['status' => ResponseStatus::ERROR, 'location' => $transactionProcess['location'], 'message' => 'Transaction Failed | ' . $transactionProcess['message']];
            }
        } catch (Throwable $error) {
            return ['status' => ResponseStatus::ERROR, 'location' => 'App\Http|Controllers\TransactionController@topup', 'message' => $error->getMessage()];
        }
    }

    public function index(): array
    {
        try {
            return [];
        } catch (Throwable $error) {
            return [];
        }
    }

    public function show($id): array
    {
        try {
            return [];
        } catch (Throwable $error) {
            return [];
        }
    }

    public function delete($id): array
    {
        try {
            return [];
        } catch (Throwable $error) {
            return [];
        }
    }
}
