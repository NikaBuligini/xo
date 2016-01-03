@extends('layouts.app')

@section('content')
<div class="container spark-screen">
    <div class="row">
        <!-- <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    You are logged in!
                </div>
            </div>
        </div> -->

        <div id="app">
            <div class="col-md-12">
                <span>@{{ count }} | </span>
                <span>@{{ gameOver }}</span>
            </div>

            <div class="col-md-6">
                <div v-if="board" class="xo">
                    <div v-for="row in board">
                        <div v-for="item in row" v-on:click="onSelect(item, 1, $event)">
                            <i :class="['fa', item.state == 1 ? 'fa-times' :  item.state == 2 ? 'fa-circle-o' : '']"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div v-if="board" class="xo">
                    <div v-for="row in board">
                        <div v-for="item in row" v-on:click="onSelect(item, 2, $event)">
                            <i :class="['fa', item.state == 1 ? 'fa-times' :  item.state == 2 ? 'fa-circle-o' : '']"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <h3 v-if="gameOver > 0">@{{ gameOver == 1 ? "You won" : "You Lose" }}</h3>
            </div>

            <div class="col-md-6">
                <h3 v-if="gameOver > 0">@{{ gameOver == 2 ? "You won" : "You Lose" }}</h3>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script type="text/javascript">
    Vue.config.debug = true;

    checkItem = function(item, acumulator) {
        if (item.state == 1) {
            acumulator.x++;
        } else if (item.state == 2) {
            acumulator.o++;
        }
    };

    checkLine = function(acumulator) {
        return acumulator.x == 3 ? 1 : acumulator.o == 3 ? 2 : 0;
    };

    vlog = function(obj) {
        console.log(JSON.parse(JSON.stringify(obj)));
    }

    var app = new Vue({
        el: '#app',

        ready: function() {
            self = this;

            for (var i = 0; i < 3; i++) {
                var row = [];

                for (var j = 0; j < 3; j++) {
                    row.push({
                        state: 0
                    });
                }

                self.board.push(row);
            }
        },

        computed: {
            gameOver: function() {
                var board = this.board;

                if (board.length == 0) {
                    return -1;
                }

                // vlog(this.board);

                // check row
                for (var i = 0; i < 3; i++) {
                    var acumulator = { x: 0, o: 0 };

                    for (var j = 0; j < 3; j++) {
                        if (board[i][j].state == 0) break;

                        checkItem(board[i][j], acumulator);
                    }

                    var result = checkLine(acumulator);
                    if (result > 0) return result;
                }

                // check column
                for (var i = 0; i < 3; i++) {
                    var acumulator = { x: 0, o: 0 };

                    for (var j = 0; j < 3; j++) {
                        if (board[j][i].state == 0) break;

                        checkItem(board[j][i], acumulator);
                    }

                    var result = checkLine(acumulator);
                    if (result > 0) return result;
                }

                // check diagonal
                var acumulator = { x: 0, o: 0 };
                checkItem(board[0][0], acumulator);
                checkItem(board[1][1], acumulator);
                checkItem(board[2][2], acumulator);

                var result = checkLine(acumulator);
                if (result > 0) return result;

                // check anti diagonal
                acumulator = { x: 0, o: 0 };
                checkItem(board[2][0], acumulator);
                checkItem(board[1][1], acumulator);
                checkItem(board[0][2], acumulator);

                result = checkLine(acumulator);
                if (result > 0) return result;

                if (this.count == 9) {
                    return 0;
                }

                return -1;
            }
        },

        data: {
            board: [],
            count: 0,
            turn: 1
        },

        methods: {
            onSelect: function(item, player, $event) {
                if (self.turn == player && item.state == 0 && self.gameOver == -1) {
                    self.turn = player == 1 ? 2 : 1;
                    item.state = player;
                    self.count++;
                }
            }
        }
    });
</script>
@endsection