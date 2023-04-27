<?php

namespace App\Http\Controllers;

use App\Enums\ResponseStatus;
use App\Enums\TransactionTypes;
use App\Models\Order;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Throwable;

class TransactionController extends Controller
{
    public function purchase(Request $request) : array {
        $request->validate([
            'user_id' => 'required|integer',
            'order_id' => 'required|integer',
        ]);

        $transaction = new Transaction();

        try {

            $user = User::find($request['user_id']);
            $order = Order::find($request['order_id']);

            if($user->balance >= $order->total_price) {
                $transaction->transaction_type = TransactionTypes::PURCHASE->value;
                $transaction->amount = $order->total_price;
                $transaction->order_id = $request['order_id'];
                $transaction->user_id = $request['user_id'];

                $transaction->save();

                $transactionProcess = $user->processTransaction($transaction, $order);

                if($transactionProcess['status'] == ResponseStatus::SUCCESS) {
                    return ['status' => ResponseStatus::SUCCESS, 'message' => 'Transaction Complete | ' . $transactionProcess['message'], 'data' => $transaction];
                }

                if($transactionProcess['status'] == ResponseStatus::FAILED) {
                    return ['status' => ResponseStatus::FAILED, 'message' => 'Transaction Failed | ' . $transactionProcess['message']];
                }

                if($transactionProcess['status'] == ResponseStatus::ERROR) {
                    return ['status' => ResponseStatus::ERROR, 'location' => $transactionProcess['location'], 'message' => 'Transaction Failed | ' . $transactionProcess['message']];
                }
            } else {
                return ['status' => ResponseStatus::FAILED, 'message' => 'Transaction Failed | You do not have sufficient balance to make this transactions'];
            }

        } catch(Throwable $error) {
            return ['status' => ResponseStatus::ERROR, 'location' => 'App\Http|Controllers\TransactionController@purchase', 'message' => $error->getMessage()];
        }
    }

    public function topup(Request $request) {
        $request->validate([
            'user_id' => 'required|integer',
            'amount' => 'required',
        ]);

        $transaction = new Transaction();

        try {

            $user = User::find($request['user_id']);

            $transaction->transaction_type = TransactionTypes::TOPUP->value;
            $transaction->amount = $request['amount'];
            $transaction->user_id = $request['user_id'];

            $transaction->save();

            $transactionProcess = $user->processTransaction($transaction);

            if($transactionProcess['status'] == ResponseStatus::SUCCESS) {
                return ['status' => ResponseStatus::SUCCESS, 'message' => 'Transaction Complete | ' . $transactionProcess['message'], 'data' => $transaction];
            }

            if($transactionProcess['status'] == ResponseStatus::FAILED) {
                return ['status' => ResponseStatus::FAILED, 'message' => 'Transaction Failed | ' . $transactionProcess['message']];
            }

            if($transactionProcess['status'] == ResponseStatus::ERROR) {
                return ['status' => ResponseStatus::ERROR, 'location' => $transactionProcess['location'], 'message' => 'Transaction Failed | ' . $transactionProcess['message']];
            }

        } catch(Throwable $error) {
            return ['status' => ResponseStatus::ERROR, 'location' => 'App\Http|Controllers\TransactionController@topup', 'message' => $error->getMessage()];
        }
    }

    public function index() : array {
        try {
            return [];
        } catch(Throwable $error) {
            return [];
        }
    }

    public function show($id) : array {
        try {
            return [];
        } catch(Throwable $error) {
            return [];
        }
    }

    public function delete($id) : array {
        try {
            return [];
        } catch(Throwable $error) {
            return [];
        }
    }
}
