<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::where('parent_id', 0)->with('children')->get();
        $allcategory = Category::all();
        return response()->json(['data'=> $categories,'all'=> $allcategory]);
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

}
