<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tag;

class TagController extends Controller
{
    public function store(Request $request)
    {
        $tag = Tag::create($request->all());
        return response()->json($tag);
    }

    public function index(Request $request)
    {
        $pageSize = $request->input('pageSize', 10);

        $query = Tag::query();
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        $tags = $query->paginate($pageSize);
        return response()->json($tags);
    }

    public function update(Request $request, Tag $tag)
    {
        $tag->update($request->all());
        return response()->json($tag);
    }

    public function destroy(Tag $tag)
    {
        $tag->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
