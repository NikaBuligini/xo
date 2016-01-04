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

var users = [];

io.on('connection', function (socket) {
	socket.emit('welcome', { 
		hello: 'send your name',
		users: users
	});

	socket.on('hello', function (user) {
		if (user) {
			for (var i = 0; i < users.length; i++) {
				if (users[i].id == user.id) {
					return;
				}
			}

			users.push(user);
		}
	console.log(user);
	});

	socket.on('disconnect', function () {
		console.log('user disconnected');
		io.emit('user disconnected');
	});
});