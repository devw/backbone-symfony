/*global beforeEach*/
beforeEach(function () {
    'use strict';
    this.fixtures = _.extend(this.fixtures || {}, {
        Notifications: {
            valid: {
                status: 'OK',
                version: '1.0',
                response: {
                    notifications: [
                        {
                            recipient_id: 235,
                            id: 1,
                            message: "notification.message.replied",
                            parameters: {
                                first_name: "Veda",
                                last_name: "Fadel",
                                points: 32
                            },
                            read: false
                        }
                    ]
                }
            }
        }
    });
});
