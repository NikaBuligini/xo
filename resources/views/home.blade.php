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
        <input type="hidden" id="user_id" value="{{ $user->id }}">
        <input type="hidden" id="user_name" value="{{ $user->name }}">

        <div id="app">
            <div class="col-md-8">
                <div class="active_users_container md_card">
                    <users user-id="{{ $user->id }}"></users>
                </div>
            </div>

            <div class="col-md-4">
                <div class="profile_container md_card">
                    <profile user-status="{{ $user->status }}"></profile>
                </div>
            </div>

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

            <ul>
                <li v-for="user in users">
                    <span>@{{ user.name }}</span>
                </li>
            </ul>

            <div class="col-md-6 result">
                <h3 v-if="gameOver > 0">@{{ gameOver == 1 ? "You won" : "You Lose" }}</h3>
            </div>

            <div class="col-md-6 result">
                <h3 v-if="gameOver > 0">@{{ gameOver == 2 ? "You won" : "You Lose" }}</h3>
            </div>
        </div>
    </div>
</div>

<!-- Templates -->
<div>
    <template id="xo-board-template">
        <div v-if="board" class="xo">
            <div v-for="row in board">
                <div v-for="item in row" v-on:click="onSelect(item, 2, $event)">
                    <i :class="['fa', item.state == 1 ? 'fa-times' :  item.state == 2 ? 'fa-circle-o' : '']"></i>
                </div>
            </div>
        </div>
    </template>

    <template id="users-template">
        <ul v-if="users.length > 0" class="active_users_list">
            <li v-for="user in users">
                @{{ user.name }}
            </li>
        </ul>
    </template>

    <template id="profile-template">
        <div class="line"><label for="name">Name:</label><span>{{ $user->name }}</span><i :class="['fa', 'fa-circle', 'user_status_icon', currStatus.cls]"></i></div>
        <div class="line"><label for="rank">Rank:</label><span>Bronze</span></div>
        <div class="line"><label for="matches">Matches:</label><span>0</span></div>
        <div class="line"><label for="won">Won:</label><span>0</span></div>

        <div class="line">
            <select name="status" id="user_status" v-on:change="statusSelected">
                <option v-for="status in statuses" :value="status.val">@{{ status.name }}</option>
            </select>
        </div>
    </template>
</div>
<!-- End of Templates -->
@endsection

@section('scripts')
<script src="https://cdn.socket.io/socket.io-1.3.7.js"></script>
<script src="{{ asset('/js/enums.js') }}"></script>
<script src="{{ asset('/js/templates.js') }}"></script>
<script type="text/javascript">
    $.post('/xo/public/api/me', {}, function(data, textStatus, xhr) {
        console.log(data);
        console.log(textStatus);
        console.log(xhr);
    });

    initializeBoard = function(board) {
        board = [];

        for (var i = 0; i < 3; i++) {
            var row = [];

            for (var j = 0; j < 3; j++) {
                row.push({
                    state: 0
                });
            }

            board.push(row);
        }

        return board;
    }

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
            this.board = initializeBoard(this.board);
        },

        computed: {
            gameOver: function() {
                var board = this.board;

                if (board == null) {
                    return -1;
                }

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
            },

            onReset: function() {
                this.board = initializeBoard(this.board);
                this.count = 0;
            }
        },

        data: {
            board: null,
            count: 0,
            turn: 1,
            users: []
        },

        components: {
            'users': users_comp,
            'profile': profile_comp
        },

        methods: {
            onSelect: function(item, player, $event) {
                if (this.turn == player && item.state == 0 && this.gameOver == -1) {
                    this.turn = player == 1 ? 2 : 1;
                    item.state = player;
                    this.count++;
                }
            }
        }
    });
</script>
@endsection
