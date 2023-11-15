<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatBroadcast implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $profile_picture, $message, $username, $user_id, $room;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($profile_picture, $username, $user_id, $message, $room)
    {
        $this->profile_picture = $profile_picture;
        $this->username = $username;
        $this->message = $message;
        $this->user_id = $user_id;
        $this->room = $room;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return [$this->room];
    }

    public function broadcastAs() {
        return 'chat';
    }
}
