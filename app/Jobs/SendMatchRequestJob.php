<?php

namespace App\Jobs;

use Event;
use App\Events\OnMatchRequest;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

use App\MatchRequest;

class SendMatchRequestJob extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    private $auth;
    private $target_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($auth, $target_id)
    {
        $this->auth = $auth;
        $this->target_id = intval($target_id);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
      $match_request = new MatchRequest([
        'author_id' => $this->auth->id,
        'target_id' => $this->target_id
      ]);

      $match_request->save();

      Event::fire(new OnMatchRequest($match_request));
    }
}
