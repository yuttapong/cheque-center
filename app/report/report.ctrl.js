app.controller('ReportCtrl', function($scope, $rootScope, SessionService, Cheque, LoginService,$routeParams) {

    $scope.profileId = $routeParams['profileId'];
    Cheque.get('siteprofile/'+ $scope.profileId).then(function(res) {
        $scope.siteProfile = res;
    });
    $scope.dateError = false;

    //get report by date
    $scope.getReport = function(data) {
            $scope.report = {};
            if (!data.date_start || !data.date_end) {
                 $scope.dateError = true;
            }else{
                 $scope.dateError = false;
                Cheque.get('report?profile_id='+$scope.profileId+'&start=' + data.date_start + '&end=' + data.date_end).then(function(res) {
                    console.log(data);
                    $scope.report = res.record;
                });
            }
        }

        //get report all of data
    $scope.getReportAll = function() {
        $scope.dateError = false;
        $scope.data = {
            date_start: '',
            date_end: ''
        };
        $scope.report = {};
        Cheque.get('report?profile_id='+$scope.profileId).then(function(res) {
            $scope.report = res.record;
        });
    }
    $scope.getReportAll();
    $('.datepicker').datepicker({
        todayBtn: "linked",
        clearBtn: true,
        language: "th",
        autoclose: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd'
    });
});
