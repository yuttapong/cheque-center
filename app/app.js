var app = angular.module('cheque', ['ngRoute', 'ui.bootstrap', 'ngAnimate', 'ngTouch', 'ui.bootstrap.tpls', 'jkuri.datepicker', 'autocomplete']);
app.config(['$routeProvider',
    function($routeProvider) {
        $routeProvider.
        when('/please-login', {
            title: 'Products',
            templateUrl: 'app/layout/please-login.html',
        }).
        when('/home', {
            templateUrl: 'app/layout/home.html',
            controller: 'HomeCtrl'
        })
        .otherwise({
            redirectTo: '/home'
        });

    }
]);
app.run(function($rootScope, $location, LoginService, SessionService, $http, $templateCache, Cheque) {

    $rootScope.$on('$routeChangeStart', function(ev, next, curr) {
        LoginService.checkLogin();
    });


});
app.controller('HomeCtrl', function($scope, $http, $routeParams, SessionService, $rootScope, $location, LoginService, Cheque) {
    var sites = [];
    Cheque.get('siteprofile/list').then(function(res) {
        $scope.sites = res;
    });
    $scope.menus =[
     {name:'โปร์ไฟล์การใช้งาน',url:'profile/index.php',icon:'fa fa-flag fa-2x'},
     {name:'ธนาคาร',url:'bank/index.php',icon:'fa fa-credit-card fa-2x'},
    ]
});
app.controller('NavCtrl', function($rootScope, $scope,LoginService, Cheque,$routeParams) {
    $scope.profileId = $routeParams['profileId'];
    Cheque.get('siteprofile/'+ $scope.profileId).then(function(res) {
        $scope.siteProfile = res;
    });

});
