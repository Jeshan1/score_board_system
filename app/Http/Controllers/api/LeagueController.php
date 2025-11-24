<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\League;
use App\Http\Requests\LeagueRequest;
use App\Http\Controllers\MasterController\MasterController;


class LeagueController extends MasterController
{

    public function __construct( )
    {
        $this->type = 'League';
        parent::__construct(new League(), LeagueRequest::class);

        $this->columns = ['id', 'name', 'start_date', 'end_date'];
    }

    public function allLeagues(){
        try {
            $leagues = League::select(['id','name'])->get();
            return $this->successResponse('Leagues fetched successfully', $leagues);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 500);
        }
    }
    
}
