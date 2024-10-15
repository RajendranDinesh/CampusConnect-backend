<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;

class SkillController extends Controller
{
    /**
     * Get all skills for a specific user.
     */
    public function index(Request $request)
    {
        $skills = Skill::where('user_id', $request->user()->id)->get();

        return response()->json($skills);
    }

    /**
     * Store a new skill.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'name' => 'required|string|max:255',
            'user_id' => 'required|exists:users,id',
            'exp_id' => 'required|exists:experiences,id',
        ]);

        // Create the skill
        $skill = Skill::create([
            'name' => $request->name,
            'user_id' => $request->user_id,
            'exp_id' => $request->exp_id,
        ]);

        return response()->json($skill, 201);
    }

    /**
     * Update an existing skill.
     */
    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
        ]);

        // Find the skill by ID
        $skill = Skill::find($id);

        if (!$skill) {
            return response()->json(['message' => 'Skill not found'], 404);
        }

        // Update the skill
        $skill->update($request->only('name'));

        return response()->json($skill);
    }

    /**
     * Delete a skill.
     */
    public function destroy($id)
    {
        $skill = Skill::find($id);

        if (!$skill) {
            return response()->json(['message' => 'Skill not found'], 404);
        }

        $skill->delete();

        return response()->json(['message' => 'Skill deleted successfully']);
    }

    /**
     * Show a single skill.
     */
    public function show($experienceId)
    {
        $skill = Skill::where('exp_id', $experienceId)->get();

        if (!$skill) {
            return response()->json(['message' => 'Skill not found'], 404);
        }

        return response()->json($skill);
    }
}
