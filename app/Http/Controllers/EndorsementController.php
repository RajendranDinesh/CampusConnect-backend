<?php

namespace App\Http\Controllers;

use App\Models\Endorsement;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EndorsementController extends Controller
{
    /**
     * Get all endorsements of current user.
     */
    public function index(Request $request)
    {
        $userId = $request->user()->id;
        
        $endorsements = DB::table('endorsements')
        ->join('skills', 'endorsements.skill_id', '=', 'skills.id')
        ->where('skills.user_id', '=', $userId)
        ->select('endorsements.*')
        ->get();

        return response()->json($endorsements);
    }

    /**
     * Store a new endorsement.
     */
    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'skill_id' => 'required|exists:skills,id'
        ]);

        // Create the endorsement
        $endorsement = Endorsement::create([
            'skill_id' => $request->skill_id,
            'endorsed_by' => $request->user()->id,
        ]);

        return response()->json($endorsement, 201);
    }

    /**
     * Delete an endorsement.
     */
    public function destroy($id)
    {
        $endorsement = Endorsement::find($id);

        if (!$endorsement) {
            return response()->json(['message' => 'Endorsement not found'], 404);
        }

        $endorsement->delete();

        return response()->json(['message' => 'Endorsement deleted successfully']);
    }

    /**
     * Show all endorsement for an user.
     */
    public function show($userId)
    {
        $endorsements = DB::table('endorsements')
        ->join('skills', 'endorsements.skill_id', '=', 'skills.id')
        ->where('skills.user_id', '=', $userId)
        ->select('endorsements.*')
        ->get();

        return response()->json($endorsements);
    }
}
