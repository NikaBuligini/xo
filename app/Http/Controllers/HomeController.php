<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

use Auth;
use App\Board;
use App\Player;
use App\MatchRequest;

use App\Jobs\SendMatchRequestJob;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {
        $user = Auth::user();

        return view('home', compact('user'));
    }

    public function createBoard()
    {
        $table = json_encode([
            [['state' => 0], ['state' => 0], ['state' => 0]],
            [['state' => 0], ['state' => 0], ['state' => 0]],
            [['state' => 0], ['state' => 0], ['state' => 0]]
        ]);

        $board = Board::create([
            'table' => $table,
            'moves' => 0,
            'finished' => false
        ]);

        $player1 = Player::create([
            'user_id' => 1,
            'board_id' => $board->id,
            'winner' => null
        ]);

        $player2 = Player::create([
            'user_id' => 1,
            'board_id' => $board->id,
            'winner' => null
        ]);

        return redirect('/');
    }

    public function sendMatchRequest(Request $request) {
      $this->dispatch(new SendMatchRequestJob(auth()->user(), $request->target_id));

      return ['success' => true];
    }

    public function getRequest(Request $request) {
      $auth = auth()->user();

      $match_request = MatchRequest::find($request->match_request_id);

      if (!$match_request || $auth->id != $match_request->target_id) {
        return ['success' => false];
      }

      return $match_request;
    }

    public function board(Board $board) {
      $user = Auth::user();

      return view('board', compact('user'));
      dd($board);
    }
}
