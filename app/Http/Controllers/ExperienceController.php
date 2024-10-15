<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Experience;
use App\Models\User;

class ExperienceController extends Controller
{
    /**
     * Get all experiences for a specific user.
     */
    public function index(Request $request)
    {
        $experiences = Experience::where('user_id', $request->user()->id)->get();

        return response()->json($experiences);
    }

    /**
     * Store a new experience.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'location' => 'required|string|max:255',
            'company_id' => 'required|exists:users,id',
            'from' => 'nullable|date',
            'till' => 'nullable|date|after_or_equal:from',
        ]);

        // Create the experience and associate it with the authenticated user
        $experience = new Experience();
        $experience->location = $request->location;
        $experience->company_id = $request->company_id;
        $experience->user_id = $request->user()->id; // Get the authenticated user ID
        $experience->from = $request->from;
        $experience->till = $request->till;
        $experience->save();

        return response()->json($experience, 201);
    }

    /**
     * Update an existing experience.
     */
    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'location' => 'sometimes|required|string|max:255',
            'company_id' => 'sometimes|required|exists:users,id',
            'from' => 'nullable|date',
            'till' => 'nullable|date|after_or_equal:from',
        ]);

        // Find the experience by ID
        $experience = Experience::find($id);

        if (!$experience) {
            return response()->json(['message' => 'Experience not found'], 404);
        }

        // Update the experience
        $experience->update($request->only('location', 'company_id', 'from', 'till'));

        return response()->json($experience);
    }

    /**
     * Delete an experience.
     */
    public function destroy($id)
    {
        $experience = Experience::find($id);

        if (!$experience) {
            return response()->json(['message' => 'Experience not found'], 404);
        }

        $experience->delete();

        return response()->json(['message' => 'Experience deleted successfully']);
    }

    public function getUserExperiences($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $experiences = $user->experiences;

        return response()->json($experiences);
    }

    public function getAuthenticatedUserExperiences(Request $request)
    {
        $user = User::find($request->user()->id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $experiences = $user->experiences;

        return response()->json($experiences);
    }
}
