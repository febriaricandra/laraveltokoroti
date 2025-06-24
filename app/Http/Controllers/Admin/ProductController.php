<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductSize;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Helpers\UnitHelper;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'sizes'])->latest()->paginate(10);
        return view('admin.product.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        $units = UnitHelper::getUnits();
        $sizes = ['Small', 'Medium', 'Large'];
        return view('admin.product.create', compact('categories', 'units', 'sizes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'description' => 'nullable|string',
            'unit' => 'required|string|in:' . implode(',', UnitHelper::getUnits()),
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sizes' => 'required|array|min:1',
            'sizes.*.size' => 'required|string',
            'sizes.*.price' => 'required|numeric|min:0',
            'sizes.*.is_active' => 'sometimes|boolean',
        ]);

        DB::beginTransaction();
        try {
            $productData = [
                'name' => $validated['name'],
                'category_id' => $validated['category_id'],
                'description' => $validated['description'] ?? null,
                'unit' => $validated['unit'],
            ];

            if ($request->hasFile('image')) {
                $productData['image'] = $request->file('image')->store('products', 'public');
            }

            $product = Product::create($productData);

            // Add product sizes
            foreach ($request->sizes as $sizeData) {
                $product->sizes()->create([
                    'size' => $sizeData['size'],
                    'price' => $sizeData['price'],
                    'is_active' => $sizeData['is_active'] ?? true,
                ]);
            }

            DB::commit();
            return redirect()->route('admin.product.index')->with('success', 'Produk berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        $units = UnitHelper::getUnits();
        $sizes = ['Small', 'Medium', 'Large'];
        $product->load('sizes');
        return view('admin.product.edit', compact('product', 'categories', 'units', 'sizes'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|integer|exists:categories,id',
            'description' => 'nullable|string',
            'unit' => 'required|string|in:' . implode(',', UnitHelper::getUnits()),
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sizes' => 'required|array|min:1',
            'sizes.*.id' => 'sometimes|exists:product_sizes,id',
            'sizes.*.size' => 'required|string',
            'sizes.*.price' => 'required|numeric|min:0',
            'sizes.*.is_active' => 'sometimes|boolean',
        ]);

        DB::beginTransaction();
        try {
            $productData = [
                'name' => $validated['name'],
                'category_id' => $validated['category_id'],
                'description' => $validated['description'] ?? null,
                'unit' => $validated['unit'],
            ];

            if ($request->hasFile('image')) {
                if ($product->image) {
                    Storage::disk('public')->delete($product->image);
                }
                $productData['image'] = $request->file('image')->store('products', 'public');
            }

            $product->update($productData);

            // Update or create product sizes
            $existingSizeIds = $product->sizes->pluck('id')->toArray();
            $updatedSizeIds = [];

            foreach ($request->sizes as $sizeData) {
                if (isset($sizeData['id'])) {
                    // Update existing size
                    $size = ProductSize::find($sizeData['id']);
                    $size->update([
                        'size' => $sizeData['size'],
                        'price' => $sizeData['price'],
                        'is_active' => $sizeData['is_active'] ?? true,
                    ]);
                    $updatedSizeIds[] = $size->id;
                } else {
                    // Create new size
                    $size = $product->sizes()->create([
                        'size' => $sizeData['size'],
                        'price' => $sizeData['price'],
                        'is_active' => $sizeData['is_active'] ?? true,
                    ]);
                    $updatedSizeIds[] = $size->id;
                }
            }

            // Remove sizes that weren't included in the update
            $sizesToDelete = array_diff($existingSizeIds, $updatedSizeIds);
            if (!empty($sizesToDelete)) {
                ProductSize::whereIn('id', $sizesToDelete)->delete();
            }

            DB::commit();
            return redirect()->route('admin.product.index')->with('success', 'Produk berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }
        $product->delete(); // This will cascade delete sizes due to foreign key constraint
        return redirect()->route('admin.product.index')->with('success', 'Produk berhasil dihapus.');
    }
};