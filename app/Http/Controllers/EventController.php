<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Game;
use App\Events\GameEventUpdated;
use Illuminate\Support\Facades\Schema;
use App\Traits\Socket;
class EventController extends Controller
{
    use Socket;
    public function matchEvents($gameId)
    {
        $game = Game::with([
            'events.player.team',
            'events.victimPlayer.team',
            'events.victimTeam',
            'teamAPlayers',
            'teamBPlayers',
            'teamA',
            'teamB'
        ])->findOrFail($gameId);
    
        return response()->json([
            'game' => $game,
            'events' => $game->events,
            'teamAPlayers' => $game->teamAPlayers,
            'teamBPlayers' => $game->teamBPlayers,
            'teamA' => $game->teamA,
            'teamB' => $game->teamB,
        ]);
    }


    public function upsertEvent(Request $request, Game $game)
    {
        $validated = $request->validate([
            'id'               => 'nullable|exists:events,id', // if exists → update
            'team_id'          => 'required|exists:teams,id',
            'player_id'        => 'required|exists:players,id',
            'type'             => 'required|in:goal,foul',
            'foul_type'        => 'nullable|in:yellow,red,technical',
            'victim_team_id'   => 'nullable|exists:teams,id',
            'victim_player_id' => 'nullable|exists:players,id',
            'minute'           => 'nullable|integer|min:0|max:120',
        ]);

        // Determine if this is an UPDATE or CREATE
        if ($request->filled('id')) {
            // UPDATE existing event
            $event = Event::where('match_id', $game->id)
                        ->where('id', $request->id)
                        ->firstOrFail();

            $event->update($validated);
            $message = 'Event updated successfully';
        } else {
            // CREATE new event
            $event = $game->events()->create($validated);
            $message = 'Event created successfully';
        }

        // === Recalculate Everything from Scratch (Safest & Most Accurate) ===
        $this->recalculateMatchStats($game);

        // $data = [
        //         'teamAScore' => $game->team_a_score,
        //         'teamBScore' => $game->team_b_score,
        //         'teamAFouls' => $game->team_a_fouls ?? 0,
        //         'teamBFouls' => $game->team_b_fouls ?? 0,
        //         'newEvent' => $event->load('player.team')->only([
        //             'id', 'type', 'foul_type', 'minute', 'player', 'team_id'
        //         ])
        //     ];
    // Add logging here
    \Log::info('Broadcasting GameEventUpdated for game: ' . $game->id);
    //     broadcast(new GameEventUpdated(
    //     gameId: $game->id,
    //     teamAScore: $game->team_a_score,
    //     teamBScore: $game->team_b_score,
    //     teamAFouls: $game->team_a_fouls ?? 0,
    //     teamBFouls: $game->team_b_fouls ?? 0,
    //     newEvent: $event->load('player.team')->only([
    //         'id', 'type', 'foul_type', 'minute', 'player', 'team_id'
    //     ])
    // ));
    //  send_updates(GameEventUpdated::class, $game,$data);

    $this->setData($game,$event);
    $this->toSocket();
        \Log::info('Broadcast completed');
        return response()->json([
            'message' => $message,
            'game'    => $game->fresh(['teamA', 'teamB']),
            'event'   => $event->load('player.team', 'victimPlayer'),
        ]);
    }

    private function recalculateMatchStats(Game $game)
    {
        // Reset match stats
        $game->team_a_score = 0;
        $game->team_b_score = 0;
        $game->team_a_fouls = 0;
        $game->team_b_fouls = 0;

        // Reload events to make sure we have fresh data
        $game->load('events');

        foreach ($game->events as $event) {
            if ($event->type === 'goal') {
                if ($event->team_id == $game->team_a_id) {
                    $game->team_a_score++;
                } else {
                    $game->team_b_score++;
                }

                // Update player's career goals
                if ($event->player_id) {
                    $event->player->increment('goals');
                }
            }

            if ($event->type === 'foul') {
                if ($event->team_id == $game->team_a_id) {
                    $game->team_a_fouls++;
                } else {
                    $game->team_b_fouls++;
                }

                if ($event->player_id && $event->player) {
                    $player = $event->player;

                    // Always increment fouls
                    $player->increment('fouls');

                    // Only increment cards 
                    if (Schema::hasColumn('players', 'yellow_cards') && $event->foul_type === 'yellow') {
                        $player->increment('yellow_cards');
                    }
                    if (Schema::hasColumn('players', 'red_cards') && $event->foul_type === 'red') {
                        $player->increment('red_cards');
                    }
                }
            }
        }

        $game->save();
    }

    public function setData(Game $game,Event $event){
        if(empty($data)){
            $this->data= [];
        }
        $this->data= [
            'broadcast_on'=>'game.'.$game->id,
            'broadcast_as'=>'game.updated',
            'broadcast_with'=>[
            'teamAScore' => $game->team_a_score,
            'teamBScore' => $game->team_b_score,
            'teamAFouls' => $game->team_a_fouls ?? 0,
            'teamBFouls' => $game->team_b_fouls ?? 0,
            'newEvent' => $event->load('player.team')->only([
            ])
            ]
        ];

    }


}
