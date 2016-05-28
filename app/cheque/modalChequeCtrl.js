
app.controller('ModalPOCtrl', function($scope, $uibModal, $log, SessionService) {
    $scope.selectedPO = [];
    $scope.animationsEnabled = false;
    $scope.openModalPO = function(size) {
        var modalInstance = $uibModal.open({
            animation: $scope.animationsEnabled,
            templateUrl: 'modelSearchPO.html',
            controller: 'ModalPOInstanceCtrl',
            size: size,
            resolve: {
                items: function() {
                    return $scope.items;
                },
                selectedPO: function() {
                    return $scope.selectedPO;
                }
            }
        });
        modalInstance.result.then(function(selectedItem) {
            $scope.selectedPO = selectedItem;
            //$scope.choosedPO = SessionService.get('selectedPO');
        }, function() {
            $log.info('Modal dismissed at: ' + new Date());
        });
    };
    $scope.toggleAnimation = function() {
        $scope.animationsEnabled = !$scope.animationsEnabled;
    };
    ////////////////////////// PO ////////////////////////////////////
    $scope.removePO = function(obj) {
        // console.log('data from remove'+obj);
        //console.log('before'+$scope.employeeList);
        // $scope.employeeList.splice(obj, obj);
        console.log('end' + $scope.details);
        if (obj != -1) {
            $scope.selectedPO.splice(obj, 1);
        }
    }




});

app.controller('ModalPOInstanceCtrl', function($scope, $uibModalInstance, items, SessionService, selectedPO, Cheque) {
    $scope.currentPage = 1;
    $scope.itemsPerPage = 5;
    $scope.maxSize = 5;
    $scope.search = {};
    $scope.dateOptions = {
        startingDay: 1
    };
    $scope.popupDate = {
        opened: false,
        format: 'dd/MM/yyyy'
    };
    $scope.openDatePicker = function() {
        $scope.popupDate.opened = true;
    };
    $scope.search = function(data) {
        if (data) {
            Cheque.get("/cheques/search?q=" + data.q + '&cheque_date=' + data.cheque_date).then(function(rs) {
                console.log("search data: ",rs);
                $scope.items = rs;
                $scope.totalItems = rs.length;
            });
        }
    }

    $scope.selected = selectedPO;
    $scope.selectPO = function(po) {
        console.log("code:", po.code);
        $scope.selected.push(po);
        SessionService.set("selectedPO", $scope.selected);
        $uibModalInstance.close($scope.selected);
    }
    $scope.ok = function() {
        $uibModalInstance.close($scope.selected);
    };
    $scope.cancel = function() {
        $uibModalInstance.dismiss('cancel');
    };
    $scope.search();
});
/*
app.controller('ModalPaymentDocCtrl', function($scope, $uibModal, $log, SessionService) {
    $scope.selectedPO = [];
    $scope.animationsEnabled = false;
    $scope.openModalPayment = function(size) {
        var modalInstance = $uibModal.open({
            animation: $scope.animationsEnabled,
            templateUrl: 'modelSearchPayment.html',
            controller: 'ModalPaymentInstanceCtrl',
            size: size,
            resolve: {
                items: function() {
                    return $scope.items;
                },
                selectedPO: function() {
                    return $scope.selectedPO;
                }
            }
        });
        modalInstance.result.then(function(selectedItem) {
            $scope.selectedPO = selectedItem;
            //$scope.choosedPO = SessionService.get('selectedPO');
        }, function() {
            $log.info('Modal dismissed at: ' + new Date());
        });
    };
    //remove
    $scope.removePO = function(obj) {
        // console.log('data from remove'+obj);
        //console.log('before'+$scope.employeeList);
        // $scope.employeeList.splice(obj, obj);
        console.log('end' + $scope.details);
        if (obj != -1) {
            $scope.selectedPO.splice(obj, 1);
        }
    }
});
app.controller('ModalPaymentInstanceCtrl', function($scope, $uibModalInstance, items, SessionService, selectedPO, Cheque) {
    $scope.currentPage = 1;
    $scope.itemsPerPage = 5;
    $scope.maxSize = 5;
    $scope.search = {};
    $scope.dateOptions = {
        startingDay: 1
    };
    $scope.popupDate = {
        opened: false,
        format: 'dd/MM/yyyy'
    };
    $scope.openDatePicker = function() {
        $scope.popupDate.opened = true;
    };
    $scope.search = function(data) {
        if (data) {
            Cheque.get("/purchase/search?q=" + data.q + '&paydate=' + data.paydate).then(function(rs) {
                console.log(rs);
                $scope.items = rs;
                $scope.totalItems = rs.length;
            });
        }
    }
    $scope.selected = selectedPO;
    $scope.selectPO = function(po) {
        console.log("code:", po.code);
        $scope.selected.push(po);
        SessionService.set("selectedPO", $scope.selected);
        $uibModalInstance.close($scope.selected);
    }
    $scope.ok = function() {
        $uibModalInstance.close($scope.selected);
    };
    $scope.cancel = function() {
        $uibModalInstance.dismiss('cancel');
    };
    $scope.search();
});
*/