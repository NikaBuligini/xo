var app = require('express')();
var http = require('http').Server(app);
var io = require('socket.io')(http);
var Redis = require('ioredis');
var redis = new Redis();

redis.subscribe('test-channel', function (err, count) {
	//
});

redis.on('message', function (channel, message) {
	console.log('Message Received!');

	message = JSON.parse(message);
	console.log(message);
	//message.event
	console.log(message.data.event);
	io.emit(channel + ':' + message.data.event, message.data);
});

http.listen(3000, function () {
	console.log('Listening on *: 3000');
});


Array.prototype.inArray = function(comparer) {
	for (var i = 0; i < this.length; i++) {
		if (comparer(this[i])) return true;
	}

	return false;
};

Array.prototype.pushSafe = function(element, comparer) {
	if (!this.inArray(comparer)) {
		this.push(element);
	}
};


var users = [];

io.on('connection', function (socket) {
	var curr = null;

	socket.on('login', function (user) {
		if (typeof(user) != 'undefined') {
			users.pushSafe(user, function(e) { return e != null && e.id === user.id; });
			curr = user;
			socket.emit('users-list', get_active_users());
			console.log('user connected', user);
		}
	});
	socket.on('disconnect', function () {
		for (var i = 0; i < users.length; i++) {
			if (curr != null && users[i].id == curr.id) {
				users.splice(i, 1);
				curr = null;
				break;
			}
		}
	});

	var intervalID = setInterval(function() {
		if (curr == null) { return clearInterval(intervalID); }

		var result = users.reduce(function(acc, user) {
      if (user.id != curr.id) { acc.push(user); }
      return acc;
    }, []);

		socket.emit('users-list', get_active_users());
	}, 5000);

	function get_active_users() {
		return users.reduce(function(acc, user) {
      if (user.id != curr.id && user.status != 4) { acc.push(user); }
      return acc;
    }, []);
	}
});
