<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VideoBlurEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $video_id = '';
    public $blurred = true;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($id, $b)
    {
        $this->video_id = $id;
        $this->blurred = $b;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('video.' . $this->video_id);
    }

    /**
     * Broadcast's event name
     * 
     * @return string
     */
    public function broadcastAs() : string {
        
        return 'video.blur';
    }

    /**
     * Data sending back to client
     * 
     * @return array
     */
    public function broadcastWith() : array {

        return [
            'video_id' => $this->video_id,
            'blurred' => $this->blurred,
        ];
    }
}
