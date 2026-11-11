<?php

namespace App\Http\Controllers;

use App\Http\Resources\ErrorResource;
use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Gate;
use Throwable;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new ProductCollection(Product::paginate(1));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string'
            ],
            'price' => [
                'required',
                'numeric',
                'decimal:0,2',
                'min:0',
                'max:99999999.99'
            ]
        ]);

        $product = $request->user()->products()->create([
            'name' => $validated['name'],
            'price' => $validated['price']
        ]);

        return new ProductResource($product)
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $product = Product::Find($id);

        if (!$product) {
            return new ErrorResource(['message' => 'Product not found.'])
                ->response()
                ->setStatusCode(404);
        }

        return new ProductResource($product)
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $product = Product::Find($id);

        if(!$product) {
            return new ErrorResource(['message' => 'Product not found.'])
                ->response()
                ->setStatusCode(404);
        }

        try {
            Gate::authorize('update', $product);
        } catch (Throwable $e) {
            return new ErrorResource(['message' => 'You are not authorised to edit this product'])
                ->response()
                ->setStatusCode(401);
        }

        $validated = $request->validate([
            'name' => [
                'sometimes',
                'string'
            ],
            'price' => [
                'sometimes',
                'numeric',
                'decimal:0,2',
                'min:0',
                'max:99999999.99'
            ]
        ]);

        $product->update($validated);

        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $product = Product::Find($id);

        if(!$product) {
            return new ErrorResource(['message' => 'Product not found.'])
                ->response()
                ->setStatusCode(404);
        }

        try {
            Gate::authorize('delete', $product);
        } catch (Throwable $e) {
            return new ErrorResource(['message' => 'You are not authorised to delete this product'])
                ->response()
                ->setStatusCode(401);
        }

        $product->delete();

        return response(status: 204);
    }
}
