<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Game;

class PublicGameController extends Controller
{
    public function index()
    {
        // dd("Gamesssss");
        $games = Game::with(['teamA', 'teamB', 'league', 'referee'])
            ->orderBy('match_date', 'asc')
            ->get();

        // Transform data for frontend
        $data = $games->map(function ($game) {
            return [
                'id' => $game->id,
                'team_a' => $game->teamA ? ['id' => $game->teamA->id, 'name' => $game->teamA->name] : null,
                'team_b' => $game->teamB ? ['id' => $game->teamB->id, 'name' => $game->teamB->name] : null,
                'league' => $game->league ? ['id' => $game->league->id, 'name' => $game->league->name] : null,
                'referee' => $game->referee ? ['id' => $game->referee->id, 'name' => $game->referee->name] : null,
                'match_date' => $game->match_date,
                'start_time' => $game->start_time,
                'end_time' => $game->end_time,
                'team_a_score' => $game->team_a_score,
                'team_b_score' => $game->team_b_score,
                'status' => $game->status,
            ];
        });

        return response()->json(['data' => $data]);
    }

    public function show($id)
    {
        $game = Game::with([
            'teamA',
            'teamB',
            'teamAPlayers',     // ← Works! No game_id error
            'teamBPlayers',     // ← Works!
            'events.player',
            'events.victimPlayer',
            'league',
            'referee',
        ])->findOrFail($id);
    
        $teamAStats = [
            'goals' => $game->team_a_goals ?? $game->team_a_score ?? 0,
            'fouls' => $game->team_a_fouls ?? 0,
        ];
    
        $teamBStats = [
            'goals' => $game->team_b_goals ?? $game->team_b_score ?? 0,
            'fouls' => $game->team_b_fouls ?? 0,
        ];
    
        return $this->successResponse('Game fetched successfully', [
            'game' => $game,
            'teamAStats' => $teamAStats,
            'teamBStats' => $teamBStats,
        ]);
    }
}
