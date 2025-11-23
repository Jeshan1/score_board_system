<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Team;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\TeamRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Player;

class TeamController extends Controller
{
    public function index()
    {
        try {
            $query = Team::with('players', 'league', 'manager');

            if (auth()->user()->role === 'team_manager') {
                $query->where('id', auth()->user()->team_id);
            }

            $teams = $query->get();

            return $this->successResponse('Teams fetched successfully', $teams);
        } catch (\Throwable $th) {
            Log::error("message: " . $th->getMessage() . " line: " . $th->getLine());
            return $this->errorResponse($th->getMessage(), 500);
        }
    }

    public function store(TeamRequest $request)
    {
        $validated = $request->all();
        $data = [];

        DB::transaction(function() use ($validated, &$team) {
            // Create team
            $team = Team::create([
                'league_id' => $validated['league_id'],
                'name' => $validated['name'],
            ]);

            // Assign manager
            $manager = User::find($validated['manager_id']);
            
            $manager->update([
                'team_id' => $team->id
            ]);

            $data = [
                'team' => $team,
                'manager' => $manager
            ];

        });

        return $this->successResponse('Team created successfully', $data);
    }

    public function update(TeamRequest $request, $id)
    {
        $validated = $request->all();
        $team = null;

        DB::transaction(function() use ($validated, $id, &$team) {
            $team = Team::findOrFail($id);

            $team->update([
                'name' => $validated['name'] ?? $team->name,
                'league_id' => $validated['league_id'] ?? $team->league_id,
            ]);

            // Manager assignment
            if (!empty($validated['manager_id'])) {
                $oldManager = $team->manager;
                if ($oldManager && $oldManager->id != $validated['manager_id']) {
                    $oldManager->update(['team_id' => null]);
                }

                $manager = User::findOrFail($validated['manager_id']);
                $manager->update(['team_id' => $team->id, 'role' => 'team_manager']);
            }

            // Update players
            if (!empty($validated['players']) && is_array($validated['players'])) {
                // Player::where('team_id', $team->id)
                //     ->whereNotIn('id', $validated['players'])
                //     ->update(['team_id' => null]);

                Player::whereIn('id', $validated['players'])
                    ->update(['team_id' => $team->id]);

                // Player::where('team_id', $team->id)
                //     ->whereNotIn('id', $validated['players'])
                //     ->update(['team_id' => null]);
            }
        });

        return $this->successResponse('Team updated successfully', $team->load('league', 'manager', 'players'));
    }

}
