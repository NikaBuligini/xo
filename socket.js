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

	socket.emit('welcome', { 
		hello: 'send your name',
		users: users
	});

	socket.on('hello', function (user) {
		users.pushSafe(user, function(e) {
			return e.id === user.id;
		});
		curr = user;

		console.log(user);
	});

	socket.on('get-users-list', function (data) {
		socket.emit('users-list', users);
	});

	socket.on('disconnect', function () {
		console.log('user disconnected', curr);
		for (var i = 0; i < users.length; i++) {
			if (users[i].id == curr.id) {
				users.splice(i, 1);
				break;
			}
		}
		console.log(users);
	});
});