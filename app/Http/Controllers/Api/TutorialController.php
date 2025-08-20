<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tutorial;
use Illuminate\Http\Request;

class TutorialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Tutorial::all(), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'link' => 'nullable|url',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
        ]);

        $tutorial = Tutorial::create([
            'title' => $validated['title'],
            'link' => $validated['link'],
            'description' => $validated['description'],
            'thumbnail' => $validated['thumbnail'],
            'user_id' => $validated['user_id'],
        ]);

        return response()->json($tutorial, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tutorial = Tutorial::findOrFail($id);
        if (!$tutorial) {
            return response()->json(['message' => 'Tutorial not found'], 404);
        }
        return response()->json($tutorial, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tutorial = Tutorial::findOrFail($id);
        if (!$tutorial) {
            return response()->json(['message' => 'Tutorial not found'], 404);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'link' => 'nullable|url',
            'description' => 'nullable|string',
            'thumbnail' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
        ]);

        $tutorial->update($validated);

        return response()->json($tutorial, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tutorial = Tutorial::findOrFail($id);
        if (!$tutorial) {
            return response()->json(['message' => 'Tutorial not found'], 404);
        }

        $tutorial->delete();

        return response()->json(['message' => 'Tutorial deleted successfully'], 200);
    }
}
