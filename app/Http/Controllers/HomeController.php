<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;

use Auth;
use App\Board;
use App\Player;

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
        return view('home');
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
}
