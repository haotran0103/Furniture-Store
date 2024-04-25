<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
class SettingController extends Controller
{
    public function index(Request $request)
    {
        $query = Setting::query();

        if ($request->has('search') && !empty($request->search)) {
            $query->where('key', 'like', '%' . $request->search . '%');
        }
    
        $categories = $query->paginate(10);
        return response()->json(['data' => $categories]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'key' => 'required|string|unique:settings',
            'type' => 'required|in:text,images',
        ]);

        $setting = new Setting();
        $setting->key = $request->key;
        $setting->type = $request->type;

        if ($request->type === 'images' && $request->hasFile('value')) {
            $image = $request->file('value'); 
            $imageName = time() . '_' . $image->getClientOriginalName(); 
            $image->move(public_path('uploads/settings'), $imageName);
            $setting->value = 'uploads/settings/' . $imageName; 
        } else {
            $setting->value = $request->input('value'); 
        }

        $setting->save();

        return response()->json($setting, 201);
    }

    public function show($id)
    {
        $setting = Setting::find($id);
        if (!$setting) {
            return response()->json(['message' => 'Setting not found'], 404);
        }

        return response()->json($setting);
    }

    public function update(Request $request, $id)
    {
        $setting = Setting::find($id);
        if (!$setting) {
            return response()->json(['message' => 'Setting not found'], 404);
        }

        $request->validate([
            'key' => 'required|string|unique:settings,key,' . $id,
            'value' => 'required|string',
            'type' => 'required|in:text,images',
        ]);

        $setting->key = $request->key;
        $setting->type = $request->type;

        if ($request->type === 'images' && $request->hasFile('images')) {
            // Xóa các ảnh cũ
            if ($setting->value) {
                foreach (json_decode($setting->value) as $oldImagePath) {
                    $oldImageFullPath = public_path($oldImagePath);
                    if (file_exists($oldImageFullPath)) {
                        @unlink($oldImageFullPath);
                    }
                }
            }
    
            // Lưu các ảnh mới
            $imagesPaths = [];
            foreach ($request->file('images') as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('uploads/settings'), $imageName);
                $imagesPaths[] = 'uploads/settings/' . $imageName;
            }
            $setting->value = json_encode($imagesPaths);
        } else {
            $setting->value = $request->input('value');
        }

        $setting->save();

        return response()->json($setting);
    }

    public function destroy($id)
    {
        $setting = Setting::find($id);
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
