beforeEach(function () {
    this.fixtures = _.extend(this.fixtures || {}, {
        Users: {
            valid: {
                status: 'OK',
                version: '1.0',
                response: {
                    users: [
                        {
                            id: 1,
                            email: 'antonio.rossi@fixture.com',
                            first_name: 'antonio',
                            last_name: 'rossi',
                            points: 0,
                            remaining_points: 1
                        },
                        {
                            id: 2,
                            email: 'antonio.bianchi@fixture.com',
                            first_name: 'antonio',
                            last_name: 'bianchi',
                            points: 10,
                            remaining_points: 1
                        }
                    ]
                }
            }
        }
    });
});
