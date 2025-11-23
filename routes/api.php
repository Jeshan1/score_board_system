<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\api\LeagueController;
use App\Http\Controllers\api\PlayerController;
use App\Http\Controllers\api\TeamController;
use App\Http\Controllers\api\GameController;
use App\Http\Controllers\api\PublicGameController;
use App\Http\Controllers\EventController;



Route::post('/user/create', [AuthController::class, 'register']);
Route::post('/user/login', [AuthController::class, 'login']);
Route::get('/get-roles', [AuthController::class, 'getRoles']);
Route::get('/all-leagues', [LeagueController::class, 'allLeagues']);
Route::get('/all-managers', [AuthController::class, 'getManagers']);
Route::get('/all-players', [PlayerController::class, 'allPlayers']);
Route::middleware(['auth:api'])->group(function () {
    Route::post('/user/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::get('/users', [AuthController::class, 'getUsers']);
    Route::get('/users-by-role', [AuthController::class, 'getByRole']);

    // for league
    Route::apiResource('leagues', LeagueController::class);
    Route::apiResource('players', PlayerController::class)->middleware('role:admin,team_manager');
});

Route::get('/teams', [TeamController::class, 'index']);
Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::post('/team/create', [TeamController::class, 'store']);
    Route::put('/team/update/{id}', [TeamController::class, 'update']);

    // for match create
    
    Route::post('/games', [GameController::class, 'store']);
    Route::put('/games/{game}', [GameController::class, 'update']);
    Route::delete('/games/{game}', [GameController::class, 'destroy']);

    
    

});

Route::get('/games', [GameController::class, 'index'])->middleware(['auth:api', 'role:admin,referee']);
//for match event
Route::get('/matches/{id}/events', [EventController::class, 'matchEvents'])->middleware(['auth:api', 'role:admin,referee']);

//update match status
Route::put('/match/{game}/status', [GameController::class, 'updateGameStatus'])->middleware(['auth:api', 'role:admin,referee']);


//public routes
Route::get('/public/games', [PublicGameController::class, 'index']);
Route::get('/public/games/{id}', [PublicGameController::class, 'show']);

// One endpoint to rule them all
Route::post('/match/{game}/events', [EventController::class, 'upsertEvent'])->middleware(['auth:api', 'role:admin,referee']);