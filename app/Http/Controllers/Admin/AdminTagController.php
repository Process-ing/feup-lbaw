<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class AdminTagController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'query' => 'nullable|string|max:255',
        ]);

        $tags = Tag::query();

        if (! empty($request->input('query'))) {
            $pattern = '%'.str_replace(['\\', '%', '_'], ['\\\\', '\\%', '\\_'], $request->input('query')).'%';

            $tags = $tags->where('name', 'ILIKE', $pattern);

            if (is_numeric($request->input('query'))) {
                $tags = $tags->orWhere('id', $request->input('query'));
            }
        }

        $tags = $tags->orderBy('id')->paginate(20);

        return view('admin.pages.tag', ['tags' => $tags]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'tag' => 'required|string|max:255|unique:tag,name',
        ]);
        $tag = new Tag;
        $tag->name = $request->input('tag');
        $tag->save();

        return redirect()->route('admin.tag.index')->withSuccess('Tag created successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, int $id)
    {
        $tag = Tag::findOrFail($id);
        $tag->delete();

        return redirect()->route('admin.tag.index')->withSuccess('Tag deleted successfully.');
    }
}
