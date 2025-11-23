<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\MasterController\MasterController;
use Illuminate\Http\Request;
use App\Models\Player;
use App\Http\Requests\PlayerRequest;

class PlayerController extends MasterController
{
    public function __construct( )
    {
        $this->type = 'Player';
        parent::__construct(new Player(), PlayerRequest::class);

         // Only these fields will be selected
         $this->columns = ['id', 'name', 'number', 'position', 'goals', 'fouls'];
    }

    public function allPlayers(){
        $players = Player::select(['id','name'])->where('team_id', '=', null)->get();
        // $players = Player::select(['id','name'])->get();
        return $this->successResponse('Players fetched successfully', $players);
    }
    
    
}
