/*global define*/
define([
    'fos_routing',
    'json!fos_routing_data'
], function (router, routes) {
    'use strict';
    fos.Router.setData(routes);

    return router;
});
