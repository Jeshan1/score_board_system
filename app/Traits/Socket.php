<?php

namespace App\Traits;

trait Socket
{
    public array $data=[];

    public function getData()
    {
        return $this->data;
    }

    public function toSocket(){

        $data=$this->getData();
        if(count($data)>0){
            $socketService=new \App\Services\SocketService();
            $socketService->set_data($data);
            $socketService->send();
        }
    }
}
