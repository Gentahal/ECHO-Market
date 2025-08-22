<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Comments;
use Illuminate\Http\Request;

class CommentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'thread_id' => 'required|exists:threads,id',
            // 'user_id' => 'required|exists:users,id',
        ]);

        $comment = Comments::create([
            'content' => $validated['content'],
            'thread_id' => $validated['thread_id'],
            'user_id' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Comment created successfully',
            'data'    => $comment
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $comment = Comments::with('user')->find($id);

        if (!$comment) {
            return response()->json([
                'success' => false,
                'message' => 'Comment not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $comment
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $comment = Comments::find($id);
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $comment->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Comment updated successfully',
            'data'    => $comment
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $comment = Comments::find($id);
        if (!$comment) {
            return response()->json(['message' => 'Comment not found'], 404);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully'
        ], 200);
    }
}
