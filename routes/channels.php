<?php

use Illuminate\Support\Facades\Broadcast;


Broadcast::channel('game.{id}', function ($game, $id) {
    return true;
});
