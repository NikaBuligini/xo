$.ajaxSetup({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}
});

Vue.config.debug = true;

var user_id = $('#user_id').val();
var user_name = $('#user_name').val();
var user_status = parseInt($('#user_status').val());

var socket = io('localhost:3000');

var users_comp = Vue.extend({
    template: '#users-template',
    props: ['userID'],
    ready: function() {
        var self = this;

        socket.emit('login', { id: user_id, name: user_name, status: user_status });
        socket.on('users-list', function (users) { self.users = users; });
    },
    data: function() {
        return {
            users: []
        };
    }
});

var profile_comp = Vue.extend({
    template: '#profile-template',
    props: ['userStatus'],
    ready: function() {
        console.log('profile component started');

        self = this;

        var statusID = parseInt(self.userStatus);
        self.currStatus = { id: statusID, cls: this.calculateStatusCls(statusID)};

        $('#user_status').val(statusID);
    },
    data: function() {
        return {
            statuses: [
                {name: 'Online', val: 1}, {name: 'Away', val: 2},
                {name: 'Busy', val: 3}, {name: 'Offline', val: 4}
            ],
            currStatus: { id: null, cls: null }
        };
    },
    methods: {
        statusSelected: function(event) {
            var status = $(event.target).find('option:selected').val()

            $.post('/xo/public/api/me/status/update', {status: status}, function(data) {
                if (data.success) {
                    var statusID = parseInt(status);
                    self.currStatus = { id: statusID, cls: self.calculateStatusCls(statusID)};
                } else {
                    alert('status could not change');
                }
            });
        },

        calculateStatusCls: function(statusID) {
            switch(statusID) {
                case userStatusEnum.online:
                    return 'green';
                case userStatusEnum.away:
                    return 'orange';
                case userStatusEnum.busy:
                    return 'red';
                case userStatusEnum.offline:
                    return 'gray';
            }

            return '';
        }
    }
});
