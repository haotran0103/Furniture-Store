<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
class SlidersController extends Controller
{
    public function index(Request $request)
    {
        $query = Slider::query();

        if ($request->has('search') && !empty($request->search)) {
            $query->where('key', 'like', '%' . $request->search . '%');
        }
    
        $categories = $query->paginate(10);
        return response()->json(['data' => $categories]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
        ]);

        $slider = new Slider();
        $slider->name = $request->name;
        $slider->description = $request->description;

        if ($request->hasFile('image')) {
            $image = $request->file('image'); 
            $imageName = time() . '_' . $image->getClientOriginalName(); 
            $image->move(public_path('uploads/slider'), $imageName);
            $slider->image = 'uploads/slider/' . $imageName; 
        }

        $slider->save();

        return response()->json($slider, 201);
    }

    public function show($id)
    {
        $setting = Slider::find($id);
        if (!$setting) {
            return response()->json(['message' => 'Setting not found'], 404);
        }

        return response()->json($setting);
    }

    public function update(Request $request, $id)
    {
        $slider = Slider::find($id);
        if (!$slider) {
            return response()->json(['message' => 'slider not found'], 404);
        }

        $request->validate([
            'name' => 'required|string',
            'description' => 'required|string',
        ]);

        $slider->name = $request->name;
        $slider->description = $request->description;

        if ($request->hasFile('image')) {
            if ($slider->image) {
                    $oldImageFullPath = public_path($slider->image);
                    if (file_exists($oldImageFullPath)) {
                        @unlink($oldImageFullPath);
                }
            }
    
            if ($request->hasFile('image')) {
                $image = $request->file('image'); 
                $imageName = time() . '_' . $image->getClientOriginalName(); 
                $image->move(public_path('uploads/slider'), $imageName);
                $slider->image = 'uploads/slider/' . $imageName; 
            }
        } 

        $slider->save();

        return response()->json($slider);
    }

    public function destroy($id)
    {
        $setting = Slider::find($id);
        if (!$setting) {
            return response()->json(['message' => 'Setting not found'], 404);
        }

        if ($setting->type === 'images') {
            if ($setting->value) {
                foreach (json_decode($setting->value) as $oldImagePath) {
                    $oldImageFullPath = public_path($oldImagePath);
                    if (file_exists($oldImageFullPath)) {
                        @unlink($oldImageFullPath);
                    }
                }
            }
        }
        
        $setting->delete();

        return response()->json(['message' => 'Setting deleted successfully']);
    }
}
