<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Game;
use App\Events\GameStatusUpdated;
use App\Traits\Socket;


class GameController extends Controller
{
    use Socket;
    public function index()
    {
    
        $query = Game::with(['teamA', 'teamB', 'league', 'referee']);

        if (auth()->user()->role === 'referee') {
            $query->where('referee_id', auth()->id());
        }

        $games = $query->get();
        
        return response()->json([
            'status' => true,
            'data' => $games,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->all();

        $game = Game::create($validated);

        return response()->json([
            'status' => true,
            'message' => 'Game created successfully',
            'data' => $game->load(['teamA', 'teamB', 'league', 'referee']),
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->all();

        $game = Game::findOrFail($id);
        $game->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Game updated successfully',
            'data' => $game->load(['teamA', 'teamB', 'league', 'referee']),
        ]);
    }

    public function destroy($id)
    {
        $game = Game::findOrFail($id);
        $game->delete();

        return response()->json([
            'status' => true,
            'message' => 'Game deleted successfully',
        ]);
    }

    public function updateEvent(Request $request, $gameId)
    {
        $game = Game::findOrFail($gameId);

        // Create event
        $game->events()->create([
            'team_id' => $request->team_id,
            'player_id' => $request->player_id,
            'type' => $request->type,
            'foul_type' => $request->foul_type,
            'victim_team_id' => $request->victim_team_id,
            'victim_player_id' => $request->victim_player_id,
            'minute' => $request->minute,
        ]);
    
        // Update score
        if ($request->type === 'goal') {
            if ($request->team_id == $game->team_a_id) $game->increment('team_a_score');
            else $game->increment('team_b_score');
        }

        if ($request->type === 'foul') {
            if ($request->team_id == $game->team_a_id) $game->increment('team_a_fouls');
            else $game->increment('team_b_fouls');
        }
    
        $game->save();        
    
        broadcast(new GameEventUpdated($game))->toOthers();
    
        return response()->json(['message' => 'Event added', 'game' => $game]);
    }

    public function updateGameStatus(Request $request, Game $game)
    {
        $oldStatus = $game->status;
        $oldScoreA = $game->team_a_score;
        $oldScoreB = $game->team_b_score;

        $game->update($request->only(['status', 'team_a_score', 'team_b_score']));

        // Auto-set end_time when match ends
        if ($game->wasChanged('status') && $game->status === 'completed') {
            $game->end_time = now()->format('H:i:s');
            $game->save();
        }

        $statusChanged = $game->wasChanged('status');
        $scoreChanged  = $game->wasChanged(['team_a_score', 'team_b_score']);

        if ($statusChanged || $scoreChanged) {
            $this->setData($game->fresh());
            $this->toSocket();
        }

        return response()->json($game);
    }

    public function setData(Game $game){
        if(empty($data)){
            $this->data= [];
        }
        $this->data= [
            'broadcast_on'=>'game.'.$game->id,
            'broadcast_as'=>'game-status-updated',
            'broadcast_with'=>[
                'status'=>$game->status,
                'teamAScore' => $game->team_a_score,
                'teamBScore' => $game->team_b_score,
            // 'teamAFouls' => $game->team_a_fouls ?? 0,
            // 'teamBFouls' => $game->team_b_fouls ?? 0,
            // 'newEvent' => $event->load('player.team')->only([
            //     'id', 'type', 'foul_type', 'minute', 'player', 'team_id'
            // ])
            ]
        ];

    }

}
