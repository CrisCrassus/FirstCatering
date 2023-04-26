<?php

namespace App\Http\Controllers;

use App\Enums\ResponseStatus;
use App\Enums\Roles;
use App\Models\Card;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Throwable;

class CardController extends Controller
{
    public function generateNewCard(Request $request): array {
        $request->validate([
            'card_type' => 'required|integer',
            'role' => 'required|string',
        ]);

        try{
           if($request['role'] == Roles::ADMIN->value) {
                $card = new Card();

                $card->identifier = $card->generateIdentifier($card);
                $card->card_type = $request['card_type'];
                $card->last_used = Carbon::now();
                $card->user_id = isset($request['user_id']) ? $request['user_id'] : null;

                $card->save();

                return ['status' => ResponseStatus::SUCCESS, 'message' => 'Card Generated', 'data' => $card];
           } else {
                return ['status' => ResponseStatus::FAILED, 'message' => 'You do not have the required permissions to generate new cards'];
           }
        } catch(Throwable $error) {
            return ['status' => ResponseStatus::ERROR, 'message' => $error->getMessage()];
        }
    }
}
