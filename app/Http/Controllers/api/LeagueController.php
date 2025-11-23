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
    // /**
    //  * Display a listing of the resource.
    //  */
    // public function index()
    // {
    //     try {
    //         $leagues = League::select('id', 'name', 'start_date', 'end_date')->get();
    //         return $this->successResponse('Leagues fetched successfully', $leagues);
    //     } catch (\Throwable $th) {
    //         return $this->errorResponse($th->getMessage(), 500);
    //     }
        
    // }

    // /**
    //  * Store a newly created resource in storage.
    //  */
    // public function store(LeagueRequest $request)
    // {
    //     $data = $request->validated();
    //     try {
    //         $league = League::create($data);
    //         return $this->successResponse('League created successfully', $league);
    //     } catch (\Throwable $e) {
    //         return $this->errorResponse($e->getMessage(), 500);
    //     }
    // }

    // /**
    //  * Display the specified resource.
    //  */
    // public function show(string $id)
    // {
    //     //
    // }

    // /**
    //  * Update the specified resource in storage.
    //  */
    // public function update(Request $request)
    // {
    //     $data = $request->all();
    //     try {
    //         $league = League::findOrFail($data['id']);
    //         unset($data['id']);
    //         $league->update($data);
    //         return $this->successResponse('League updated successfully', $league);
    //     } catch (\Throwable $e) {
    //         return $this->errorResponse($e->getMessage(), 500);
    //     }
    // }

    // /**
    //  * Remove the specified resource from storage.
    //  */
    // public function destroy(string $id)
    // {
    //     try {
    //         $league = League::findOrFail($id);
    //         $league->delete();
    //         return $this->successResponse('League deleted successfully');
    //     } catch (\Throwable $e) {
    //         return $this->errorResponse($e->getMessage(), 500);
    //     }
    // }
}
