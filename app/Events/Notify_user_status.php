<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class Notify_user_status implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    private $userId;
    private $status;
    public $data;
    public function __construct($userId,$data = null,$status)
    {
        $this->userId = $userId;
        $this->data = $data;
        $this->status = $status;
    }
      public function broadcastAs()
    {
        switch ($this->status) {
            case 'Waiting_payment':
                return 'Waiting_payment';
                break;
            
        }
        
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('user_notify-'.$this->userId);
    }
}
