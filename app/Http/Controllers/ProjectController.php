<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Project;
use App\Models\User;

class ProjectController extends Controller
{
    /**
     * Display a listing of the projects.
     */
    public function index()
    {
        $projects = Project::all();
        return response()->json($projects);
    }

    /**
     * Store a newly created project in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'technology' => 'required|string|max:255',
            'github_link' => 'required|url',
        ]);
    
        $project = Project::create([
            'name' => $request->name,
            'technology' => $request->technology,
            'github_link' => $request->github_link,
            'user_id' => $request->user()->id, // Get the authenticated user's ID
        ]);
    
        return response()->json($project, 201);
    }

    /**
     * Display the specified project.
     */
    public function show(Request $request, $id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        if ($project->user_id != $request->user()->id) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        return response()->json($project);
    }

    /**
     * Update the specified project in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'technology' => 'sometimes|required|string|max:255',
            'github_link' => 'sometimes|required|url',
            'user_id' => 'sometimes|required|exists:users,id',
        ]);

        $project = Project::find($id);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        if ($project->user_id != $request->user()->id) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $project->update($request->all());
        return response()->json($project);
    }

    /**
     * Remove the specified project from storage.
     */
    public function destroy(Request $request, $id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        if ($project->user_id != $request->user()->id) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        $project->delete();
        return response()->json(['message' => 'Project deleted successfully']);
    }

    public function getUserProjects($userId)
    {
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $projects = $user->projects;

        return response()->json($projects);
    }

    public function getAuthenticatedUserProjects(Request $request)
    {
        $user = User::find($request->user()->id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $projects = $user->projects;

        return response()->json($projects);
    }
}
