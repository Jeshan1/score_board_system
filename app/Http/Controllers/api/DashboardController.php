<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;

class DashboardController extends Controller
{
    public function getTeamsStatistics()
    {
        $team_statistics = DB::select("select 
                                            g.team_a_fouls,
                                            g.team_b_fouls,
                                            g.team_a_score as team_a_goals,
                                            g.team_b_score as team_b_goals,
                                            t.name as team_a,
                                            t1.name as team_b
                                        from games g
                                        left join teams t on t.id=g.team_a_id
                                        left join teams t1 on t1.id = g.team_b_id;");

        return $this->successResponse('Teams statistics fetched successfully', $team_statistics);
    }

    public function getTotalEntity(){
        $totalTeams = DB::select("select count(t.id) as total_teams from teams as t")[0]->total_teams;
        $totalPlayers = DB::select("select count(p.id) as total_players from players as p")[0]->total_players;

        //fetch total refereee
        $role_referee = 'referee';
        $referees = DB::select("select count(u.id) as total_referees from users u where u.role= ?",[$role_referee])[0]->total_referees;

        $data = [
            'total_teams' => $totalTeams,
            'total_players' => $totalPlayers,
            'total_referees' => $referees
        ];

        return $this->successResponse('Total entities fetched successfully', $data);

    }

    public function fetchMatches(){
        $data = DB::select("select g.id,
                                t.name as home_team,
                                t1.name as away_team, 
                                g.team_a_score as home_score, 
                                g.team_b_score as away_score,
                                g.match_date as date,
                                concat(g.start_time, ' - ', g.end_time) as time,
                                g.status,
                                u.name as referee
                             from games g
                             left join teams t on t.id=g.team_a_id
                             left join teams t1 on t1.id = g.team_b_id
                             left join users u on u.id = g.referee_id
                             order by g.id desc;");
        return $this->successResponse('Matches fetched successfully', $data);
    }
}
