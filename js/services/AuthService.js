module.exports =  function () {
    var self = this;
    var authService = {};
    var activeUser = {};
    var users = [
        {pseudo: "asurion", password: "password"},
        {pseudo: "hiredream", password: "password"}
    ];

    authService.checkLogin = function (pseudo, password) {
        var ret = false;
        angular.forEach(users, function(value) {
            if(value.pseudo === pseudo && value.password === password) {
                ret = true;
                self.activeUser = {pseudo: value.pseudo, date: new Date()};
            }
        });
        return ret;
    };

    authService.isAuth = function (pseudo) {
        return activeUser.pseudo === pseudo;
    };

    authService.getActiveUser = function(){
        return self.activeUser;
    };

    return authService;
};