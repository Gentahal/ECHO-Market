<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Threads;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Http\Request;

class ThreadsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(
            Threads::with('user', 'comments.user')->latest()->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title'   => 'required|string|max:255',
                'content' => 'required|string',
                'image'   => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                // 'user_id' => 'required|exists:users,id',
            ]);

            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('threads', 'public');
                $validated['image'] = $imagePath;
            }

            // Buat thread
            $thread = Threads::create([
                'title'   => $validated['title'],
                'content' => $validated['content'],
                'image'   => $validated['image'] ?? null,
                'user_id' => auth()->id(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thread created successfully',
                'data'    => $thread
            ], 201);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors'  => $e->errors()
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create thread',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $thread = Threads::with('user', 'comments.user')->find($id);

        if (!$thread) {
            return response()->json([
                'success' => false,
                'message' => 'Thread not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data'    => $thread
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $thread = Threads::find($id);

        if (!$thread) {
            return response()->json([
                'success' => false,
                'message' => 'Thread not found'
            ], 404);
        }

        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
            'image'   => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $thread->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Thread updated successfully',
            'data'    => $thread
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $thread = Threads::find($id);

        if (!$thread) {
            return response()->json([
                'success' => false,
                'message' => 'Thread not found'
            ], 404);
        }

        $thread->delete();

        return response()->json([
            'success' => true,
            'message' => 'Thread deleted successfully'
        ], 200);
    }
}
