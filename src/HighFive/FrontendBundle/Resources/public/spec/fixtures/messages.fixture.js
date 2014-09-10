beforeEach(function () {
    this.fixtures = _.extend(this.fixtures || {}, {
        Messages: {
            valid: {
                status: 'OK',
                version: '1.0',
                response: {
                    messages: [
                        {
                            sender_id: 1,
                            recipient_id: 2,
                            id: 1,
                            message: "Est inventore aliquam ipsa",
                            points: 0,
                            created_at: "1996-11-27T05:05:39+0100",
                            recognition: {
                                id: 2,
                                points: 10,
                                created_at: "1996-11-27T05:05:39+0100"
                            }
                        },
                        {
                            sender_id: 3,
                            recipient_id: 2,
                            id: 2,
                            message: "Et omnis quo perspiciatis qui",
                            points: 0,
                            created_at: "1996-11-27T05:05:39+0100",
                            recognition: {
                                id: 1,
                                points: 20,
                                created_at: "1996-11-27T05:05:39+0100"
                            }
                        }
                    ]
                }
            }
        }
    });
});
