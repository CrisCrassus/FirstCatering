<?php

namespace App\Http\Controllers\Api;

use App\Enums\ResponseStatus;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Card;
use App\Traits\HasResponseStatus;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Throwable;

class AuthController extends Controller
{

    use HasResponseStatus;

    public function create(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'mobile' => 'required',
                'company_id' => 'required|integer',
                'role_id' => 'required|integer'
            ]);

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'mobile' => $request->mobile,
                'company_id' => $request->company_id,
                'balance' => isset($request->balance) ? $request->balance : 0,
                'role_id' => $request->role_id,
                'pin' => mt_rand(1000, 9999),
            ]);

            return $this->responseStatus(ResponseStatus::SUCCESS, 'User Created Successfully', ['token' => $user->createToken("API TOKEN")->plainTextToken, 'data' => $user]);
        } catch (\Throwable $error) {
            return $this->responseStatus(ResponseStatus::ERROR, $error->getMessage());
        }
    }

    public function login(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'identifier' => 'required|string',
            ]);

            $card = Card::where('identifier', $request['identifier'])->first();

            if (!$card) {
                return $this->responseStatus(ResponseStatus::FAILED, 'Unable to locate card');
            }

            $user = $card->user()->first('id');

            if (!$user) {
                return $this->responseStatus(ResponseStatus::FAILED, 'Unable to locate user for card ' . $card->identifier);
            }

            if ($user) {
                return $this->responseStatus(ResponseStatus::SUCCESS, 'User located', $user);
                // return ['status' => ResponseStatus::SUCCESS, 'message' => 'User located', 'data' => $user];
            }
        } catch (Throwable $error) {
            return $this->responseStatus(ResponseStatus::ERROR, $error->getMessage(), ['location' => 'App\Http\Controllers\API\AuthController@login']);
        }
    }

    public function pinVerification(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'id' => 'required|integer',
                'pin' => 'required|integer',
            ]);

            $user = User::find($request->id);

            if (!$user) {
                return $this->responseStatus(ResponseStatus::FAILED, 'Unable to locate user');
            }

            $rs = $user->validatePIN($request->pin);

            return $this->responseStatus($rs['status'], $rs['message'], ['token' => isset($rs['token']) ? $rs['token'] : null, 'data' => $rs['data']]);
        } catch (Throwable $error) {
            return $this->responseStatus(ResponseStatus::ERROR, $error->getMessage(), ['location' => 'App\Http\Controllers\API\AuthController@pinVerification']);
        }
    }

    public function logout(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'id' => 'required|integer',
            ]);

            $user = User::find($request->id);

            if (!$user) {
                return $this->responseStatus(ResponseStatus::FAILED, 'Unable to locate user');
            }

            $user->tokens()->delete();

            return $this->responseStatus(ResponseStatus::SUCCESS, 'User\'s tokens removed from system');
        } catch (Throwable $error) {
            return $this->responseStatus(ResponseStatus::ERROR, $error->getMessage(), ['location' => 'App\Http\Controllers\API\AuthController@logout']);
        }
    }

    public function standardLogin(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            if (!Auth::attempt($request->only(['email', 'password']))) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 401);
            }

            $user = User::where('email', $request->email)->first();

            return response()->json([
                'status' => true,
                'message' => 'User Logged In Successfully',
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'status' => false,
                'message' => $th->getMessage()
            ], 500);
        }
    }
}
