module.exports = function($routeParams, config, $location, AuthService){
    var self = this;
    this.config = config;
    this.curRoute = $location.$$path;
    this.logged = false;
    this.message;

    this.inputEmail;
    this.inputPassword;

    this.checkFormLogin = function(){
        var bool = AuthService.checkLogin(self.inputEmail,self.inputPassword);
        self.logged = bool;
        if(!bool)
            self.message = "Mauvais identifiants";
        else
            self.message = "Connecté";
    }
};