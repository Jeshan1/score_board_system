<?php

namespace App\Services;

use App\Events\SocketEvent;
class SocketService
{

    public $data=[];
    public function send(){
        broadcast(new SocketEvent($this->data));
    }

    public function set_data(array $data){
        $this->data = $data;
        return $this;
    }




}
