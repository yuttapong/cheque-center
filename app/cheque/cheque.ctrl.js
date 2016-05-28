app.controller('ChequesCtrl', function(
  $scope,
  $http, Cheque, $rootScope,
  SessionService,
  LoginService,
  $routeParams,
  $uibModal,
  $log
  ) {
    $scope.profileId = $routeParams['profileId'];
    Cheque.get('siteprofile/'+ $scope.profileId).then(function(res) {
        $scope.siteProfile = res;
    });
    $scope.dropdownCancel = { 0:'ใช้งาน',1:'ยกเลิก'};

    $scope.currentPage = 1;
    $scope.itemsPerPage =10;
    $scope.maxSize = 5;
    //search form
    $scope.year = '';
    $scope.q = '';
    $scope.cheque_name = '';
    $scope.loadCheque = function(page) {
        var start = (page - 1) * $scope.itemsPerPage;
        var end = $scope.itemsPerPage;
        Cheque.get('cheques?profile_id=' +   $scope.profileId
        + '&is_cancel=' + $scope.is_cancel
        + '&year=' + $scope.year
        + '&q=' + $scope.q
        + '&cheque_name=' + $scope.cheque_name
        + '&page=' + page
        + '&start=' + start
        + '&end=' + end
        ).then(function(rs) {
            $scope.items = rs.data;
            $scope.totalItems = rs.total;
            $scope.year = rs.year;
        });
    }
    $scope.setPage = function(pageNo) {
        $scope.currentPage = pageNo;
    };
    $scope.pageChanged = function() {
        $scope.loadCheque($scope.currentPage);
        console.log('Page changed to: ' + $scope.currentPage);
    };
    $scope.changStatus = function(row) {
        var t = row.cheque_status == 'N' ? 'จ่ายเงินแล้ว' : 'ยังไม่จ่าย';
        var notify = '\nเมื่อคุณเปลี่ยนสถานะเป็น [จ่ายเงินแล้ว] จะไม่สามารถเปลี่ยนกลับมาได้อีก';
        if( row.cheque_status == 'N' ){
          if (confirm('ยืนยันเปลี่ยนสถานะเช็คเป็น :: [' + t + '] ? \nเลขที่  ' + row.cheque_no + '  (' + row.cheque_name + ') '+notify)) {
              Cheque.post("cheque/set-status", row).then(function(res) {
                  row.cheque_status = res.cheque_status;
              });
          }
        }else{
          alert("ไม่สามารถเปลี่ยนสถานะได ้ เนื่องจากจ่ายเงินเรียบร้อยแล้ว");
        }
    }

    $scope.viewCheque = function(row) {

          var modalInstance = $uibModal.open({
              animation: $scope.animationsEnabled,
              templateUrl: 'ModalViewCheque.html',
              controller: 'ModalViewCtrl',
              size: 'lg',
              resolve: {
                items: function () {
                  return row;
                }
              }
            });
    }
    $scope.cancelCheque = function(row) {
        var t = row.cheque_status == 'N' ? 'จ่ายเงินแล้ว' : 'ยังไม่จ่าย';
        var notify = '\nเมื่อคุณเปลี่ยนสถานะเป็น [จ่ายเงินแล้ว] จะไม่สามารถเปลี่ยนกลับมาได้อีก';
        if( row.cheque_status != 'Y' ){
          var modalInstance = $uibModal.open({
              animation: $scope.animationsEnabled,
              templateUrl: 'ModalCancelCheque.html',
              controller: 'ModalCancelCtrl',
              size: 'lg',
              resolve: {
                items: function () {
                  return row;
                }
              }
            });
            modalInstance.result.then(function (selectedItem) {
              $scope.selected = selectedItem;
            }, function () {
              $log.info('Modal dismissed at: ' + new Date());
            });

        }else{
          alert("ไม่สามารถเปลี่ยนสถานะได ้ เนื่องจากจ่ายเงินเรียบร้อยแล้ว");
        }
    }

    $scope.loadCheque(1);
});

app.controller('ModalViewCtrl', function ($scope, $uibModalInstance, items,Cheque,$log) {

  $scope.getChequeDetail = function(id_cheque) {
      Cheque.get("/cheque-items/" + id_cheque).then(function(rs) {
          $scope.records = rs;
      });
  }
  $scope.ok = function () {
    $uibModalInstance.close($scope.selected.item);
  };

  $scope.cancel = function () {
    $uibModalInstance.dismiss('cancel');
  };

    $scope.items = items;
    $scope.getChequeDetail(items.id);
    $scope.selected = {
      item: $scope.items[0]
    };


});
app.controller('ModalCancelCtrl', function ($scope, $uibModalInstance, items,Cheque,$log) {
  $scope.msgError = '';
  $scope.items = items;
  $scope.data = {
    cancel_note:''
  };
  $scope.getChequeDetail = function(id_cheque) {
      Cheque.get("/cheque-items/" + id_cheque).then(function(rs) {
          $scope.records = rs;
      });
  }
  $scope.ok = function (data) {
    console.log(data);
    Cheque.post("cheques/cancel/"+items.id,data).then(function(res){
          $uibModalInstance.close($scope.items);
    });

  };

  $scope.cancel = function () {
    $uibModalInstance.dismiss('cancel');

  };


    $scope.getChequeDetail(items.id);
    $scope.selected = {
      item: $scope.items[0]
    };


});

app.controller('ChequeCtrl', function($routeParams, Cheque, $scope, $rootScope, SessionService, LoginService) {

  $scope.profileId = $routeParams['profileId'];
  Cheque.get('siteprofile/'+ $scope.profileId).then(function(res) {
      $scope.siteProfile = res;
  });


    var id_cheque = $routeParams.id;
    $scope.records = [];
    $scope.getChequeDetail = function(id_cheque) {
        Cheque.get("/cheque-items/" + id_cheque).then(function(rs) {
            $scope.records = rs;
            console.log(rs);
        }, function(rs) {});
    }
    Cheque.get('cheque/' + id_cheque).then(function(res) {
        $scope.data = res;
        $scope.getChequeDetail(id_cheque);
    });
    // Total prices
    $scope.totalsDetail = function() {
        var priceTotal = 0;
        var taxTotal = 0;
        angular.forEach($scope.records, function(record) {
            if (record.active) {
                priceTotal += record.price;
            }
        });
        // Return aggregate data
        return {
            price: priceTotal
        };
    };
});
////////////////////////// Form /////////////////////////////////
app.controller("ChequeAddCtrl", function($scope, $http, Cheque, $rootScope, SessionService, LoginService, $routeParams, $location) {

  $scope.profileId = $routeParams['profileId'];
  Cheque.get('siteprofile/'+ $scope.profileId).then(function(res) {
      $scope.siteProfile = res;
  });

    var id_cheque = $routeParams.id;
    $scope.id_cheque = id_cheque;
    $scope.alldata = [];
    $scope.allrecords = [];
    $scope.dataform = {};
    $scope.records = [];
    $scope.dataform.cheque_price = 0;
    $scope.dataform.amount = 0;
    $scope.dataform.feeVat = 0;
    $scope.dataform.feeGuarantee = 0;
    $scope.dataform.feeTax = 0;
    $scope.dataform.cheque_ac = 'Y';
    $scope.dataform.cheque_status = 'N';

    function setDefaultBank(id_bank) {
        Cheque.get('bank/' + id_bank).then(function(res) {
            $scope.dataform.bank_id = res.id_bank;
            $scope.dataform.bank_name = res.name;
            $scope.dataform.bank_branch = res.branch;
            $scope.dataform.bank_code = res.code;
            $scope.dataform.bank_no = res.number;
        });
    }
    $scope.getCheque = function(id) {
        Cheque.get("cheque/" + id_cheque).then(function(rs) {
            $scope.dataform = rs;
            $scope.setBank(rs.bank_id);
        });
    }
    $scope.getChequeDetail = function(id_cheque) {
        Cheque.get("/cheque-items/" + id_cheque).then(function(rs) {
            $scope.records = rs;
        });
    }
    $scope.getBanks = function() {
        Cheque.get("/bank/list").then(function(rs) {
            //console.log(rs);
            $scope.banks = rs;
        });
    }
    $scope.getBank = function(id) {
            if (id != '') {
                Cheque.get("/bank/" + id).then(function(rs) {
                    // console.log(rs);
                    $scope.banks = rs;
                });
            }
        }
        //set bank to data to input
    $scope.setBank = function(bank_id) {
        var id = bank_id;
        if (!id) return;
        Cheque.get("/bank/" + id).then(function(rs) {
            $scope.dataform.bank_name = rs.name,
                $scope.dataform.bank_no = rs.number,
                $scope.dataform.bank_branch = rs.branch,
                $scope.dataform.bank_code = rs.code,
                $scope.dataform.bank_id = rs.id_bank
        });
    }
    $scope.setPayCash = function() {
            $scope.dataform.cheque_name = 'เงินสด';
        }
        //build json for save to cheque_cheque_itmes  table
    function buildJsonRecord() {
        var temp = [];
        angular.forEach($scope.records, function(record) {
            temp.push({
                _id: record._id,
                title: record.title,
                price: record.price,
                active: record.active,
                _act: record._act,
                id_cheque: record.id_cheque,
                id_site: record.site_id,
                id_site_profile: record.profile_id
            });
        });
        return temp;
    }
    // save and update cheque
    $scope.saveChuque = function(data) {
        var id_cheque = $scope.id_cheque;
        var data = {
            id: id_cheque,
            bank_id: data.bank_id,
            bank_code: data.bank_code,
            bank_name: data.bank_name,
            bank_branch: data.bank_branch,
            bank_no: data.bank_no,
            cheque_no: data.cheque_no,
            cheque_date: data.cheque_date,
            cheque_name: data.cheque_name,
            cheque_price: data.cheque_price,
            cheque_status: data.cheque_status,
            cheque_ac: data.cheque_ac,
            amount: data.amount,
            records: buildJsonRecord(),
            created_at: new Date,
            id_site: $scope.siteProfile.id_site,
            id_site_profile: $scope.siteProfile.id,
            username: $rootScope.username,
            created_name: $rootScope.userprofile.firstname,
            updated_name:$rootScope.userprofile.firstname
        };
        $scope.alldata.push(data);
        if (id_cheque) {
           // console.log("data", data);
            Cheque.post("cheques/" + id_cheque, data).then(function(res) {
                $scope.getCheque(id_cheque);
                $scope.getChequeDetail(id_cheque);
                $location.path($scope.siteProfile.id+'/cheque/edit/' + id_cheque);
                alert('อัพเดทข้อมูลเรียบร้อย');
            });
        } else {
           //console.log("data", data);
            Cheque.post("cheques", data).then(function(res) {
                 $location.path($scope.siteProfile.id+"/cheques");
            });

        }
    }

    $scope.showDebug = function(data){
        var id_cheque = $scope.id_cheque;
        var data = {
            id: id_cheque,
            bank_id: data.bank_id,
            bank_code: data.bank_code,
            bank_name: data.bank_name,
            bank_branch: data.bank_branch,
            bank_no: data.bank_no,
            cheque_no: data.cheque_no,
            cheque_date: data.cheque_date,
            cheque_name: data.cheque_name,
            cheque_price: data.cheque_price,
            cheque_status: data.cheque_status,
            cheque_ac: data.cheque_ac,
            amount: data.amount,
            records: buildJsonRecord(),
            created_at: new Date,
            id_site: $scope.siteProfile.id_site,
            id_site_profile: $scope.siteProfile.id,
            username: $rootScope.username,
            created_name: $rootScope.userprofile.firstname
        };
        $scope.showxx = data;
    }

    $scope.range = function(min, max, step) {
        step = step || 1;
        var input = [];
        for (var i = min; i < max; i += step) input.push(i);
        return input;
    }
    $scope.calPrice = function(dataform) {
        // console.log(dataform);
        var price = dataform.cheque_price;
        // var vat = (price * dataform.feeVat) / 100;
        //var guarantee = (price * dataform.feeGuarantee) / 100;
        //var tax = (price * dataform.feeTax) / 100;
        var total = price;
        /*
        $scope.dataform.totalVat = vat;
        $scope.dataform.totalGuarantee = guarantee;
        $scope.dataform.totalTax = tax;
        */
        $scope.dataform.amount = total;
    }

    $scope.resetForm = function() {
        if ($scope.id_cheque) {
            $scope.getCheque($scope.id_cheque);
        } else {
            $scope.dataform = {};
            $scope.records = [];
            if($scope.bank_id){
                 setDefaultBank($scope.bank_id);
            }
        $scope.dataform.cheque_ac = 'Y';
        $scope.dataform.cheque_status = 'N';
        }
        //$scope.alldata = [];
    }
    $scope.getBanks();
    $scope.records = [];
    // if edit data
    if (id_cheque) {
        $scope.getCheque(id_cheque);
        $scope.getChequeDetail(id_cheque);
        $scope.act = 'edit';
    } else {
        $scope.act = 'add'
        Cheque.get('siteprofile/' + $scope.profileId).then(function(res) {
            console.log("set default bank : ",res);
            $scope.bank_id = res.id_bank;
            setDefaultBank(res.id_bank);
        });
    }
    ///////////////////// Detail ///////////////////////

    // Historical data
    $scope.history = [];
    // Default data
    $scope.pay = {};
    // Total prices
    $scope.TotalsDetail = function() {
        var priceTotal = 0;
        var taxTotal = 0;
        angular.forEach($scope.records, function(record) {
            if (record.active) {
                priceTotal += record.price;
            }
        });
        $scope.dataform.cheque_price = priceTotal;
        $scope.calPrice($scope.dataform);
        // Return aggregate data
        return {
            price: priceTotal
        };
    }
    // Delete data
    $scope.deleteDetail = function(index, record) {
        // Remove first / oldest element from history if it reaches maximum capacity of 10 records
        //if ($scope.history.length === 10) $scope.history.shift();
        // Add deleted record to historical records
        //$scope.history.push($scope.records[index]);
        // Remove from main records (using index)
        if (confirm('ยืนยันลบ ::  ' + record.title + ' มูลค่า : ' + record.price + ' บาท ? ')) {
            if (record._id > 0) {
                Cheque.delete("cheque/delete/" + record._id).then(function(rs) {});
            }
            $scope.records.splice(index, 1);
        }
    }
    // Reset new data model
    $scope.resetDetail = function() {
        $scope.pay.title = '';
        $scope.pay.price = 0;
        $("#search-pay_value").val('');
        $("#search-pay-price").val(0);
        $scope.searchStr = '';
        $scope.pay = {};
    }
    $scope.resetDetail();
    // Add new data
    $scope.addDetail = function(pay) {
            if (!pay.title || !pay.price) return;
            $scope.records.push({
                title: pay.title,
                price: pay.price,
                active: true,
                id_cheque: $scope.id_cheque,
                id_site: $scope.siteProfile.id_site,
                id_site_profile: $scope.siteProfile.id,
                _act: 'add'
            });
            $scope.resetDetail();
        }
        // Undo action (delete)
    $scope.undoDeleteDetail = function() {
        // Add last / most recent historical record to the main records
        $scope.records.push($scope.history[$scope.history.length - 1]);
        // Remove last / most recent historical record
        $scope.history.pop();
    }
    $scope.groupPay = [];
    $scope.getGroupPay = function(pay) {
        // console.log("q: ", pay.title);
        Cheque.get("autocomplete/cheque-item/" + pay.title).then(function(res) {
            $scope.groupPay = res.items;
        });
    }
    $scope.groupChequeName = [];
    $scope.getGroupChequeName = function(data) {
        //console.log("q: ", data.cheque_name);
        Cheque.get("autocomplete/cheque-name/" + data.cheque_name).then(function(res) {
            $scope.groupChequeName = res.items;
        });
    };
    $('.datepicker').datepicker({
        todayBtn: "linked",
        clearBtn: true,
        language: "th",
        autoclose: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd'
    });
});
app.directive('ngEnter', function() {
    return function(scope, element, attrs) {
        element.bind("keydown keypress", function(event) {
            if (event.which === 13) {
                scope.$apply(function() {
                    scope.$eval(attrs.ngEnter);
                });
                event.preventDefault();
            }
        });
    };
});
app.filter('range', function() {
    return function(input, min, max) {
        min = parseInt(min);
        max = parseInt(max);
        for (var i = min; i <= max; i++) input.push(i);
        return input;
    };
});

app.controller('ChequeTodayCtrl', function($routeParams, Cheque, $scope, $rootScope, SessionService, LoginService) {

  $scope.profileId = $routeParams['profileId'];
  Cheque.get('siteprofile/'+ $scope.profileId).then(function(res) {
      $scope.siteProfile = res;
  });

    $scope.getChequeToday = function() {
        Cheque.get('cheques/today?profile_id=' + $scope.profileId).then(function(rs) {
            $scope.items = rs.data;
        });
    }
    $scope.getChequeToday();
});

    app.directive('datepickerLocaldate', ['$parse', function ($parse) {
        var directive = {
            restrict: 'A',
            require: ['ngModel'],
            link: link
        };
        return directive;

        function link(scope, element, attr, ctrls) {
            var ngModelController = ctrls[0];

            // called with a JavaScript Date object when picked from the datepicker
            ngModelController.$parsers.push(function (viewValue) {
                // undo the timezone adjustment we did during the formatting
                viewValue.setMinutes(viewValue.getMinutes() - viewValue.getTimezoneOffset());
                // we just want a local date in ISO format
                return viewValue.toISOString().substring(0, 10);
            });

            // called with a 'yyyy-mm-dd' string to format
            ngModelController.$formatters.push(function (modelValue) {
                if (!modelValue) {
                    return undefined;
                }
                // date constructor will apply timezone deviations from UTC (i.e. if locale is behind UTC 'dt' will be one day behind)
                var dt = new Date(modelValue);
                // 'undo' the timezone offset again (so we end up on the original date again)
                dt.setMinutes(dt.getMinutes() + dt.getTimezoneOffset());
                return dt;
            });
        }
    }]);
