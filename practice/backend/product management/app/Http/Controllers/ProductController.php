<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\Flysystem\StorageAttributes;
use Pest\Plugins\Tia\Storage as TiaStorage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'nullable',
                'string',
                'max:255'
            ],
            'min_price' => [
                'nullable',
                'decimal:0,2',
                'min:0',
                'max:99999999.99'
            ],
            'max_price' => [
                'nullable',
                'decimal:0,2',
                'min:0',
                'max:99999999.99'
            ],
            'category' => [
                'nullable',
                'integer',
                'exists:categories,id'
            ],
            'order-by' => [
                'nullable',
                'string',
                'in:created_at,updated_at,name,price'
            ],
            'order-by-direction' => [
                'nullable',
                'string',
                'in:asc,desc'
            ]
        ],
        [
            'category' => 'Invalid category'
        ]);

        $products = Product::query();

        if(isset($validated['name'])) {
            $products = $products->where('name', 'LIKE', $validated['name'] . '%');
        }

        if(isset($validated['min_price'])) {
            $products = $products->where('price', '>=', $validated['min_price']);
        }

        if(isset($validated['max_price'])) {
            $products = $products->where('price', '<=', $validated['max_price']);
        }

        if(isset($validated['category'])) {
            $products = $products->where('category_id', $validated['category']);
        }

        if(isset($validated['order-by'])) {
            $products = $products->orderBy($validated['order-by'], $validated['order-by-direction'] ?? 'desc');
        }

        $products = $products->get();

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'price' => [
                'required',
                'decimal:0,2',
                'min:0',
                'max:99999999.99'
            ],
            'category' => [
                'required',
                'integer',
                'exists:categories,id'
            ],
            'image' => [
                'required',
                'image'
            ]
        ],
        [
            'category' => 'please choose a valid category'
        ]);

        $imageUrl = $request->image('image')->optimize()->storePublicly('images/products', 'public');

        Product::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'image_url' => $imageUrl,
            'category_id' => $validated['category']
        ]);

        return redirect(route('home'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        return view('products.update', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255'
            ],
            'price' => [
                'required',
                'numeric',
                'decimal:0,2',
                'min:0',
                'max:99999999.99'
            ],
            'category' => [
                'required',
                'integer',
                'exists:categories,id'
            ],
            'image' => [
                'nullable',
                'image'
            ]
        ]);

        if(isset($validated['image'])) {
            Storage::disk('public')->delete($product->image_url);

            $newImageUrl = $request->image('image')->optimize()->storePublicly('/images/products', 'public');

            $validated['image_url'] = $newImageUrl;
        }

        $validated['category_id'] = $validated['category'];

        $product->update($validated);

        return redirect(route('home'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        Storage::disk('public')->delete($product->image_url);

        $product->delete();

        return redirect(route('home'));
    }
}
