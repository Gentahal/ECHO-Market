<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products = Product::with('user')->latest()->get();
            return ProductResource::collection($products);
        } catch (\Exception $e) {
            Log::error('Failed to fetch products: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to retrieve products',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {

        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'price' => 'required|numeric',
                'description' => 'required|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'categories' => 'required|string|max:255',
                'favourite' => 'required|boolean',
                'user_id' => 'required|exists:users,id',
            ]);

            if ($request->hasFile('image')) {
                $path = $request->file('image')->store('product_images', 'public');
                $validated['image'] = $path;
            } else {
                $validated['image'] = 'default.jpg';
            }

            $product = Product::create($validated);
            DB::commit();

            return response()->json([
                'message' => 'Product created successfully',
                'data' => $product
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal simpan produk: ' . $e->getMessage());
            return response()->json([
                'message' => 'Gagal menyimpan produk',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = Product::with('user')->findOrFail($id);
            return new ProductResource($product);
        } catch (\Exception $e) {
            Log::error('Failed to fetch product: ' . $e->getMessage());
            return response()->json([
                'message' => 'Product not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();

        try {
            $product = Product::findOrFail($id);

            $validated = $request->validate([
                'name' => 'sometimes|string|max:255',
                'price' => 'sometimes|numeric|min:0',
                'description' => 'sometimes|string',
                'image' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
                'categories' => 'sometimes|string|max:255',
                'favourite' => 'sometimes|boolean',
                'user_id' => 'sometimes|exists:users,id',
            ]);

            // Handle image update
            if ($request->hasFile('image')) {
                // Delete old image if it's not the default
                if ($product->image !== 'default.jpg') {
                    Storage::disk('public')->delete($product->image);
                }

                $path = $request->file('image')->store('product_images', 'public');
                $validated['image'] = $path;
            }

            $product->update($validated);
            DB::commit();

            return response()->json([
                'message' => 'Product updated successfully',
                'data' => new ProductResource($product)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to update product: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to update product',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();

        try {
            $product = Product::findOrFail($id);

            // Delete associated image if it's not the default
            if ($product->image !== 'default.jpg') {
                Storage::disk('public')->delete($product->image);
            }

            $product->delete();
            DB::commit();

            return response()->json([
                'message' => 'Product deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Failed to delete product: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to delete product',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
