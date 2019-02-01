<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Support\Facades\Log;

class News implements ShouldBroadcast {

  use Dispatchable, InteractsWithSockets, SerializesModels;

  public $message;

  /**
   * Create a new event instance.
   *
   * @param $news_message
   */
  public function __construct($news_message) {
    $this->message = $news_message;
  }

  /**
   * Get the channels the event should broadcast on.
   *
   * @return \Illuminate\Broadcasting\Channel|array
   */
  public function broadcastOn() {
    Log::info(1123);
    return new Channel('news');
    //    return new PrivateChannel('channel-name');
  }
}
