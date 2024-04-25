<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Category::with(['children', 'parent']);

        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
    
        $categories = $query->paginate(10);
        $allcategory = Category::all();
        return response()->json(['data' => $categories, 'all' => $allcategory]);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:categories,slug|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $parent_id = $request->input('parent_id');
        $category = Category::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'parent_id' => $parent_id,

        ]);
        return response()->json(['message' => 'Category created successfully', 'category' => $category], 201);
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories,slug,' . $id,
        ]);

        $category = Category::findOrFail($id);

        $category->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'parent_id' => $request->parent_id == 0 ? 0 : $request->parent_id,
        ]);

        return response()->json([
            'message' => 'Category updated successfully!',
            'category' => $category
        ]);
    }
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Category not found'], 404);
        }

        if ($category->delete()) {
            return response()->json(['message' => 'Category deleted successfully']);
        } else {
            return response()->json(['message' => 'Category could not be deleted'], 500);
        }
    }
}
