<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Tag;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Category;

class productsController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->paginate(10);

        return response()->json($products);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $product = new Product;
        $product->name = $request->name;
        $product->price = $request->price;
        $product->category_id = $request->category_id;
        $product->quantity = $request->quantity;
        $product->slug = Str::slug($request->name) . '-' . Str::random(5);

        if ($request->type === 'images' && $request->hasFile('images')) {
            $imagesPaths = [];
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/settings'), $imageName);
                $imagesPaths[] = 'uploads/settings/' . $imageName;
            }
            $product->value = json_encode($imagesPaths);
        } else {
            $product->value = $request->input('value');
        }
        $product->user_id = Auth::id();
        $product->content = $request->input('content', '');
        $product->save();
        $product->tags()->sync($request->tags);

        return response()->json([
            'message' => 'Product added successfully',
            'product' => $product
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        $product->name = $request->name;
        $product->price = $request->price;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->content = $request->content;

        if ($request->has('tags')) {
            $product->tags()->sync($request->tags);
        }
        if ($request->hasFile('images')) {
            $currentImages = json_decode($product->feature_image);
            if ($currentImages) {
                foreach ($currentImages as $image) {
                    $imagePath = public_path($image);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }
            }
            $imagesPaths = [];
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/product'), $imageName);
                $imagesPaths[] = 'uploads/product/' . $imageName;
            }
            $product->feature_image = json_encode($imagesPaths);
        }

        $product->save();

        return response()->json(['message' => 'Sản phẩm đã được cập nhật thành công']);
    }

    public function show($id)
    {
        $product = Product::with('tags', 'category')->findOrFail($id);
        return response()->json($product);
    }
    public function detail($id)
    {
        $product = Product::where('id', $id)
            ->with('category', 'tags')
            ->firstOrFail();

        return response()->json($product);
    }
    public function destroy($id)
    {
        $product = Product::find($id);
        if ($product->images) {
            foreach ($product->images as $image) {
                Storage::delete($image->path);
            }
        }

        $product->tags()->detach();

        $product->delete();

        return response()->json(['message' => 'Sản phẩm đã được xóa thành công']);
    }
    public function featuredProducts()
    {
        $featuredProducts = Product::orderBy('sold', 'desc')->take(9)->get();

        return response()->json($featuredProducts);
    }

    public function popularTags()
    {
        $popularTags = Tag::withCount('products')
            ->orderBy('products_count', 'desc')
            ->take(10)
            ->get();

        return response()->json($popularTags);
    }
    public function productsWithCategory($categoryID)
    {
        $category = Category::with('allChildren')->findOrFail($categoryID);

        $categoryIds = $this->getAllCategoryIds($category);

        $products = Product::whereIn('category_id', $categoryIds)->get();

        return response()->json($products);
    }
    public function productsRandom()
    {
        $products = Product::inRandomOrder()->take(9)->get();
        return response()->json($products);
    }
    protected function getAllCategoryIds($category)
    {
        $ids = [$category->id];

        foreach ($category->allChildren as $child) {
            $ids = array_merge($ids, $this->getAllCategoryIds($child));
        }

        return $ids;
    }
    public function suggestions($productId)
    {
        $product = Product::with('category', 'tags')->findOrFail($productId);

        $categoryProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id) 
            ->take(6)
            ->get();

        if ($categoryProducts->count() >= 6) {
            return response()->json($categoryProducts);
        }

        $tagIds = $product->tags->pluck('id');
        $tagProducts = Product::whereHas('tags', function ($query) use ($tagIds) {
            $query->whereIn('tags.id', $tagIds);
        })
            ->where('id', '!=', $product->id) 
            ->take(6 - $categoryProducts->count())
            ->get();

        $suggestions = $categoryProducts->merge($tagProducts);

        return response()->json($suggestions);
    }

    public function productsByCategory($id)
    {
        $category = Category::where('id', $id)->firstOrFail();

        $subCategories = Category::where('parent_id', $category->id)->pluck('id');

        $allCategories = $subCategories->push($category->id)->toArray();

        $products = Product::whereIn('category_id', $allCategories)->get();

        return response()->json($products);
    }
    public function getAll(){
        $products = Product::with('category', 'tags')->get();
        return response()->json($products);
    }
}
