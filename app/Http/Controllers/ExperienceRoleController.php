<?php

namespace App\Http\Controllers;

use App\Models\ExperienceRole;
use Illuminate\Http\Request;

class ExperienceRoleController extends Controller
{
    /**
     * Get all roles for a specific experience.
     */
    public function index($experienceId)
    {
        $roles = ExperienceRole::where('exp_id', $experienceId)->get();

        return response()->json($roles);
    }

    /**
     * Store a new experience role.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'duration' => 'required|string|max:255',
            'exp_id' => 'required|exists:experiences,id',
        ]);

        // Create the experience role
        $experienceRole = ExperienceRole::create([
            'name' => $request->name,
            'duration' => $request->duration,
            'exp_id' => $request->exp_id,
        ]);

        return response()->json($experienceRole, 201);
    }

    /**
     * Update an existing experience role.
     */
    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'duration' => 'sometimes|required|string|max:255',
        ]);

        // Find the experience role by ID
        $experienceRole = ExperienceRole::find($id);

        if (!$experienceRole) {
            return response()->json(['message' => 'Experience role not found'], 404);
        }

        // Update the experience role
        $experienceRole->update($request->only('name', 'duration'));

        return response()->json($experienceRole);
    }

    /**
     * Delete an experience role.
     */
    public function destroy($id)
    {
        $experienceRole = ExperienceRole::find($id);

        if (!$experienceRole) {
            return response()->json(['message' => 'Experience role not found'], 404);
        }

        $experienceRole->delete();

        return response()->json(['message' => 'Experience role deleted successfully']);
    }

    /**
     * Show a single experience role.
     */
    public function show($id)
    {
        $experienceRole = ExperienceRole::find($id);

        if (!$experienceRole) {
            return response()->json(['message' => 'Experience role not found'], 404);
        }

        return response()->json($experienceRole);
    }
}
