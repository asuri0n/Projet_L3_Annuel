module.exports = function($routeParams, config, $location, AuthService){
    var self = this;
    this.config = config;
    this.curRoute = $location.$$path;
    this.message;

    this.inputEmail;
    this.inputPassword;

    this.checkFormLogin = function(){
        var bool = AuthService.checkLogin(self.inputEmail,self.inputPassword);
        if(!bool)
            self.message = "Mauvais identifiants.";
        else
            self.message = "Connection réussie.";
        console.log(AuthService.getActiveUser());
    };

    this.getActiveUser = function(){
        return AuthService.getActiveUser()
    };
};