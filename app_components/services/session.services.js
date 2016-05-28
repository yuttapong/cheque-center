app.factory('SessionService', function() {
    return {
        set: function(key, value) {
            return localStorage.setItem(key, value);
        },
        get: function(key) {
            return localStorage.getItem(key);
        },
        destroy: function(key) {
            return localStorage.removeItem(key)
        }
    };
});
app.factory('LoginService', function($http, $location, SessionService, $rootScope,Cheque) {
    return {
        checkLogin: function(){
        Cheque.get('user/session').then(function(res) {
            if (res.user == null) {
                 console.log("Please login to siricenter");
                 $location.path("/please-login");
            } else {
                $rootScope.username = res.user;
                $rootScope.userprofile = res.userprofile;
                console.log("user:",res);
            }
        });
        },
        getSiteProfile: function(profileId) {
          Cheque.get('siteprofile/'+ profileId).then(function(res) {
             return res;
          });
        }
    };
})
