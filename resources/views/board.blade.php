@extends('layouts.app')

@section('content')
<div class="container spark-screen">
  <div class="row">
    <input type="hidden" id="user_id" value="{{ $user->id }}">
    <input type="hidden" id="user_name" value="{{ $user->name }}">
    <input type="hidden" id="user_status" value="{{ $user->status }}">

    <div id="app">
      <div class="col-md-12 md_card">
        <div id="board"><div>
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
</div>
<!-- End of Templates -->
@endsection

@section('scripts')
<script src="https://cdn.socket.io/socket.io-1.3.7.js"></script>
<script src="{{ asset('/js/enums.js') }}"></script>
<script src="{{ asset('/js/templates.js') }}"></script>
<script type="text/javascript">
  var board = new Board({
    el: '#board',
    table: ''
  });

  console.log(board);

  $.post('/xo/public/api/me', {}, function(data, textStatus, xhr) {
    console.log(data);
    // console.log(textStatus);
    // console.log(xhr);
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
    }
  });
</script>
@endsection
