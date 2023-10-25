<?php


/**
 * @OA\Tag(
 *     name="Memo",
 *     description="Memo API sample",
 * )
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\MemoResource;
use App\Http\Resources\MemoCollection;
use App\Models\Memo;
use Auth;

class MemoController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/memos",
     *     operationId="memosAll",
     *     tags={"Memo"},
     *     summary="Display a listing of the resource",
     *     security={
     *       {"api_key": {}},
     *     },
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="The page number",
     *         required=false,
     *         @OA\Schema(
     *             type="integer",
     *         )
     *     ),
     *     @OA\Response(
     *         response="200",
     *         description="Everything is fine",
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 type="array",
     *                 @OA\Items(ref="#/components/MemosShowRequest"),
     *             )
     *         )
     *     ),
     * )
    */

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user_id = Auth::user()->id;
        return new MemoCollection(Memo::where('user_id', $user_id)->paginate(4));
    }

    /**
     * @OA\Post(
     *     path="/api/memos",
     *     tags={"Memo"},
     *     summary="Add new Memo",
     *     operationId="createMemo",
     *     @OA\Response(
     *         response=200,
     *         description="Memo created",
     *         @OA\JsonContent(ref="#/components/Memo"),
     *     ),
     *     @OA\RequestBody(
     *         description="Description of new memo",
     *         required=true,
     *         @OA\JsonContent(ref="#/components/Memo")
     *     )
     * )
     */

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
     * @OA\Get(
     *     path="/api/memos/{id}",
     *     tags={"Memo"},
     *     description="For valid response try positive integer IDs. Other values will generated exceptions",
     *     operationId="getMemoById",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID of Memo to be fetched",
     *         required=true,
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="successful operation",
     *         @OA\JsonContent(ref="#/components/Memo"),
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Invalid ID supplied"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Page not found"
     *     )
     * )
     */

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $memo = Memo::findOrFail($id); // 404 sent back if not found
        // check if user is owner of Memo, else return 404
        if( $memo->user_id != Auth::user()->id ) {
            abort(404);
        }

        return new MemoResource(Memo::findOrFail($id));
    }

    /**
     * Update an existing pet.
     *
     * @OA\Put(
     *     path="/api/memo/{id}",
     *     tags={"Memo"},
     *     operationId="updateMemo",
     *     @OA\Response(
     *         response="200",
     *         description="Everything is fine",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Page not found"
     *     ),
     *     @OA\Response(
     *         response=405,
     *         description="Validation exception"
     *     ),
     *     security={
     *       {"api_key": {}},
     *     },
     *     @OA\RequestBody(ref="#/components/requestBodies/Memo")
     * )
     */

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
     * @OA\Delete(
     *     path="/api/memos/{id}",
     *     tags={"Memo"},
     *     summary="Delete Memo by ID",
     *     description="For valid response try integer IDs with positive integer value. Negative or non-integer values will generate API errors",
     *     operationId="deleteMemo",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="ID of Memo to be deleted",
     *         @OA\Schema(
     *             type="integer",
     *             format="int64",
     *             minimum=1
     *         )
     *     ),
     *     @OA\Response(
     *         response="202",
     *         description="Deleted",
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No such page"
     *     )
     * ),
     */

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
