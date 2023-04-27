<?php

namespace App\Http\Controllers;

use App\Enums\ResponseStatus;
use App\Enums\Roles;
use App\Models\Card;
use App\Traits\HasResponseStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Throwable;

class CardController extends Controller
{

    use HasResponseStatus;

    public function generateNewCard(Request $request)
    {
        $request->validate([
            'card_type' => 'required|integer',
            'role' => 'required|string',
        ]);

        try {
            if ($request['role'] == Roles::ADMIN->value) {
                $card = new Card();

                $card->identifier = $card->generateIdentifier($card);
                $card->card_type = $request['card_type'];
                $card->last_used = Carbon::now();
                $card->user_id = isset($request['user_id']) ? $request['user_id'] : null;

                $card->save();

                return $this->responseStatus(ResponseStatus::SUCCESS, 'Card Generated', $card);
            } else {
                return $this->responseStatus(ResponseStatus::FAILED, 'You do not have the required permissions to generate new cards');
            }
        } catch (Throwable $error) {
            return $this->responseStatus(ResponseStatus::ERROR, $error->getMessage(), ['location' => 'App\Http\Controller\CardController@generateNewCard']);
        }
    }
}
