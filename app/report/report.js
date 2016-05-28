app.config(['$routeProvider',
    function($routeProvider) {
        $routeProvider.
        when('/:profileId/report', {
            title: 'Report',
            templateUrl: 'app/report/view/index.html',
            controller: 'ReportCtrl'
        })
    }
]);
