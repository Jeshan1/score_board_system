 <?php

use App\Events\SendUpdates;
use Illuminate\Database\Eloquent\Model;

if (!function_exists('send_updates')) {
    function send_updates(string $event_class, Model $model, array $data = null) {
       try {
            resolve($event_class, [
                'game' => $model,
                'data' => $data
            ]);
           $event = new $event_class($model, $data);
           broadcast(new SendUpdates($event))->toOthers();
       } catch (\Throwable $th) {
           Log::error("message: " . $th->getMessage() . " line: " . $th->getLine());
           throw $th;
       }
        
    }
    
}