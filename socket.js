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
		hello: 'hello!',
		users: users
	});

	socket.on('get-users-list', function (user) {
		// check if first call
		if (typeof(user) != 'undefined') {
			users.pushSafe(user, function(e) {
				return e != null && e.id === user.id;
			});
			curr = user;
			console.log('user connected', user);
		}

		var result = users.reduce(function(acc, user) {
            if (curr != null && user.id != curr.id) {
                acc.push(user);
            }

            return acc;
        }, []);

		socket.emit('users-list', result);
	});

	socket.on('disconnect', function () {
		console.log('user disconnected', curr);
		for (var i = 0; i < users.length; i++) {
			if (curr != null && users[i].id == curr.id) {
				users.splice(i, 1);
				break;
			}
		}
	});
});