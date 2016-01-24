<?php

namespace App\Events;

use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class OnMatchRequest implements ShouldBroadcast
{
    use SerializesModels;

    public $name = 'request';
    public $content;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($match_request)
    {
      $this->name .= '.'.$match_request->target_id;
      $this->content['match_request_id'] = $match_request->id;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
      return ['xo-channel'];
    }
}
