<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Menu;
class menuController extends Controller
{
    public function index(Request $request)
    {
        $query = Menu::query();

        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
    
        $menu = $query->paginate(10);
        return response()->json(['data' => $menu]);
    }

    public function store(Request $request)
    {

        $menus = new Menu();
        if($request->name){
            $menus->name = $request->name;
        }else{
            $menus->name = '';
        }
        $menus->name = $request->name;
        $menus->parent_id = $request->parent_id;
        $menus->type = $request->type;
        $menus->category = $request->category;
        
        $menus->save();

        return response()->json($menus, 201);
    }

    public function show($id)
    {
        $menus = Menu::find($id);
        if (!$menus) {
            return response()->json(['message' => 'menus not found'], 404);
        }

        return response()->json($menus);
    }

    public function update(Request $request, $id)
    {
        $menus = Menu::find($id);
        if (!$menus) {
            return response()->json(['message' => 'menus not found'], 404);
        }

        $menus->name = $request->name;
        $menus->parent_id = $request->parent_id;
        $menus->type = $request->type;
        $menus->category = $request->category;


        $menus->save();

        return response()->json($menus);
    }

    public function destroy($id)
    {
        $menus = Menu::find($id);
        if (!$menus) {
            return response()->json(['message' => 'menus not found'], 404);
        }
        $menus->delete();

        return response()->json(['message' => 'menus deleted successfully']);
    }
    public function getAllMenu(){
        $menus = Menu::all();
        return response()->json($menus);
    }
}
