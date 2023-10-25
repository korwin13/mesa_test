<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\MemoResource;
use App\Http\Resources\MemoCollection;
use App\Models\Memo;
use Auth;

class MemoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        return new MemoCollection(Memo::where('user_id', $user_id)->paginate(4));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'text' => 'nullable',
            'due_date' => 'nullable|date',
        ]);

        $validated['user_id'] = Auth::user()->id;

        $memo = Memo::create($validated);
        return response()->json(['status' => 'Memo created.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        // /TODO: 404 instead of Unauthenticated
        // /TODO: 404 when not found
        // abort(404);
        // try
        return new MemoResource(Memo::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validated = $request->validate([
            'title' => 'required',
            'text' => 'nullable',
            'due_date' => 'nullable|date',
        ]);

        $memo = Memo::findOrFail($id); // 404 sent back if not found
        // check if user is owner of Memo, else return 404
        if( $memo->user_id != Auth::user()->id ) {
            abort(404);
        }

        $memo->update($validated);
        return response()->json(['status' => 'Memo updated.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $memo = Memo::findOrFail($id); // 404 sent back if not found
        if( $memo->user_id != Auth::user()->id ) {
            abort(404);
        }
        $memo->delete();
        return response()->json(['status' => 'Memo deleted.']);        //
    }
}
