<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Exception;
use Illuminate\Http\Request;
use App\Enums\ResponseStatus;
use Throwable;

class ProductController extends Controller
{
    public function create(Request $request): array {
        $request->validate([
            'name' => 'required|max:255',
            'price' => 'required',
            'quantity' => 'required',
        ]);

        try {
            $product = new Product();

            $product->name = $request['name'];
            $product->price = $request['price'];
            $product->quantity = $request['quantity'];
            $product->identifier = $product->generateIdentifier($product, 6, true);

            $product->save();

            return ['status' => ResponseStatus::SUCCESS, 'message' => 'Product successfully created', 'data' => $product];
        } catch (Throwable $error) {
            return ['status' => ResponseStatus::ERROR, 'location' => 'App\Http\Controllers\ProductController@create', 'message' => $error->getMessage()];
        }
    }

    public function show($id): array {
        try {
            $product = Product::find($id);
            if($product) {
                return ['status' => ResponseStatus::SUCCESS, 'message' => 'Found product ' . $product->id, 'data' => $product];
            } else {
                return ['status' => ResponseStatus::FAILED, 'message' => 'Could not find product ' . $id];
            }
        } catch(Throwable $error) {
            return ['status' => ResponseStatus::ERROR, 'location' => 'App\Http\Controllers\ProductController@show', 'message' => $error->getMessage()];
        }
    }

    public function index(): array {
        try {
            $products = Product::get();
            return ['status' => ResponseStatus::SUCCESS, 'message' => 'Found all products', 'data' => $products];
        } catch(Throwable $error) {
            return ['status' => ResponseStatus::ERROR, 'location' => 'App\Http\Controllers\ProductController@index', 'message' => $error->getMessage()];
        }
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name' => 'required|max:255',
            'price' => 'required',
            'quantity' => 'required',
        ]);

        try {
            $product = Product::find($id);

            if($product) {
                $product->name = $request['name'];
                $product->price = $request['price'];
                $product->quantity = $request['quantity'];
                $product->identifier = $product->generateIdentifier($product, 6, true);

                $product->save();

                return ['status' => ResponseStatus::SUCCESS, 'message' => 'Product successfully updated', 'data' => $product];
            } else {
                return ['status' => ResponseStatus::FAILED, 'message' => 'Product could not be found'];
            }
        } catch (Throwable $error) {
            return ['status' => ResponseStatus::ERROR, 'location' => 'App\Http\Controllers\ProductController@update', 'message' => $error->getMessage()];
        }
    }

    public function delete($id) {
        try {
            $product = Product::find($id);

            if($product) {
                $product->delete();
                return ['status' => ResponseStatus::SUCCESS, 'message' => 'Product ' . $id . ' deleted'];
            } else {
                return ['status' => ResponseStatus::FAILED, 'message' => 'Product ' . $id . ' was not found'];
            }
        } catch(Throwable $error) {
            return ['status' => ResponseStatus::ERROR, 'location' => 'App\Http\Controllers\ProductController@delete', 'message' => $error->getMessage()];
        }
    }
}
