app.config(['$routeProvider',
    function($routeProvider) {
        $routeProvider.
        when('/:profileId/cheques', {
            title: 'Cheques',
            templateUrl: 'app/cheque/view/index.html',
            controller: 'ChequesCtrl'
        }).when('/:profileId/cheque/:id', {
            title: 'Cheque Detail',
            templateUrl: 'app/cheque/view/detail.html',
            controller: 'ChequeCtrl'
        }).when('/:profileId/cheques/add', {
            templateUrl: 'app/cheque/view/form.html',
            controller: 'ChequeAddCtrl'
        }).when('/:profileId/cheque/edit/:id', {
            templateUrl: 'app/cheque/view/form.html',
            controller: 'ChequeAddCtrl'
        }).when('/:profileId/cheques/today', {
            templateUrl: 'app/cheque/view/cheques-today.html',
            controller: 'ChequeTodayCtrl'
        })
    }
]);
app.run(function($rootScope, $templateCache) {
    $rootScope.inArray = function(item, array) {
        return (-1 !== array.indexOf(item));
    };
    $rootScope.clearCache = function() {
        $templateCache.removeAll();
    }
});
