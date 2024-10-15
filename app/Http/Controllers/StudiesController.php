<?php

namespace App\Http\Controllers;

use App\Models\Studies;
use App\Models\User;
use Illuminate\Http\Request;

class StudiesController extends Controller
{
    /**
     * Get all studies for current user.
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        $studies = Studies::where('user_id', $userId)->get();

        return response()->json($studies);
    }

    /**
     * Store a new study.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'college_id' => 'required|exists:users,id',
            'branch' => 'required|string',
            'degree' => 'required|string',
            'start' => 'nullable|date',
            'end' => 'nullable|date',
        ]);

        // Create the study
        $study = Studies::create([
            'user_id' => $request->user()->id,
            'college_id' => $request->college_id,
            'branch' => $request->branch,
            'degree' => $request->degree,
            'start' => $request->start,
            'end' => $request->end,
        ]);

        return response()->json($study, 201);
    }

    /**
     * Show all studies for a user.
     */
    public function show($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $study = Studies::where('user_id', $userId)->get();

        return response()->json($study);
    }

    /**
     * Update a study.
     */
    public function update(Request $request, $id)
    {
        $study = Studies::find($id);

        if (!$study) {
            return response()->json(['message' => 'Study not found'], 404);
        }

        // Validate the incoming request data
        $request->validate([
            'college_id' => 'sometimes|exists:users,id',
            'branch' => 'sometimes|string',
            'degree' => 'sometimes|string',
            'start' => 'nullable|date',
            'end' => 'nullable|date',
        ]);

        $request->user_id = $request->user()->id;

        // Update the study
        $study->update($request->all());

        return response()->json($study);
    }

    /**
     * Delete a study.
     */
    public function destroy($id)
    {
        $study = Studies::find($id);

        if (!$study) {
            return response()->json(['message' => 'Study not found'], 404);
        }

        $study->delete();

        return response()->json(['message' => 'Study deleted successfully']);
    }
}
