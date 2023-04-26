<?php

namespace App\Http\Controllers;

use App\Enums\ResponseStatus;
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
            'transaction_type' => 'required|string'
        ]);

        $transaction = new Transaction();

        try {

            $user = User::find($request['user_id']);

            $transaction->transaction_type = $request['transaction_type'];
            $transaction->amount = Order::find($request['order_id'])->total_price;
            $transaction->order_id = $request['order_id'];
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
            return ['status' => ResponseStatus::ERROR, 'location' => 'App\Http|Controllers\TransactionController@purchase', 'message' => $error->getMessage()];
        }
    }

    public function topup(Request $request) {
        $request->validate([
            'user_id' => 'required|integer',
            'amount' => 'required',
            'transaction_type' => 'required|string'
        ]);

        $transaction = new Transaction();

        try {

            $user = User::find($request['user_id']);

            $transaction->transaction_type = $request['transaction_type'];
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
