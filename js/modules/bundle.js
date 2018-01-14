(function e(t,n,r){function s(o,u){if(!n[o]){if(!t[o]){var a=typeof require=="function"&&require;if(!u&&a)return a(o,!0);if(i)return i(o,!0);var f=new Error("Cannot find module '"+o+"'");throw f.code="MODULE_NOT_FOUND",f}var l=n[o]={exports:{}};t[o][0].call(l.exports,function(e){var n=t[o][1][e];return s(n?n:e)},l,l.exports,e,t,n,r)}return n[o].exports}var i=typeof require=="function"&&require;for(var o=0;o<r.length;o++)s(r[o]);return s})({1:[function(require,module,exports){
module.exports = function($routeProvider,$locationProvider){
        $routeProvider.
        when('/', {
            templateUrl: 'templates/main.html',
            controller: 'mainController',
            controllerAs: 'mainCtrl'
        }).
        when('/login', {
            templateUrl: 'templates/login.html',
            controller: 'mainController',
            controllerAs: 'mainCtrl'
        }).
        when('/about', {
            templateUrl: 'templates/about.html',
            controller: 'mainController',
            controllerAs: 'mainCtrl'
        }).
        when('/admin', {
            templateUrl: 'templates/admin.html',
            controller: 'mainController',
            controllerAs: 'mainCtrl'
        }).
        when('/in/', {
            templateUrl: 'templates/main.html',
            controller: 'inController',
            controllerAs: 'inCtrl'
        }).
        when('/in/exit', {
            templateUrl: 'templates/in/exit.html',
            controller: 'inController',
            controllerAs: 'inCtrl'
        }).
        when('/in/products', {
            templateUrl: 'templates/in/list.html',
            controller: 'productController',
            controllerAs: 'productCtrl'
        }).
        when('/in/clients', {
            templateUrl: 'templates/in/list.html',
            controller: 'clientsController',
            controllerAs: 'clientCtrl'
        }).
        otherwise({
            redirectTo: '/'
        });
        if(window.history && window.history.pushState){
            $locationProvider.html5Mode(true);
        }
};
},{}],2:[function(require,module,exports){
module.exports = function(AuthService) {
};
},{}],3:[function(require,module,exports){
module.exports = function($routeParams) {

};
},{}],4:[function(require,module,exports){
module.exports = function($routeParams){

};
},{}],5:[function(require,module,exports){
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
},{}],6:[function(require,module,exports){
arguments[4][3][0].apply(exports,arguments)
},{"dup":3}],7:[function(require,module,exports){
module.exports = function(){
    return {
        nom: "Mutlti-Modules APP",
        version: "1.0",
        paths:
        {
            "/": [{caption: "Accueil", href: "/autoevaluation_projetl3/"}],
            "/about": [{caption: "A propos", href: "about"}],
            "/login": [{caption: "Se connecter", href: "login"}],
            "/admin": [{caption: "Panel", href: "admin"}]
        }
    }
};
},{}],8:[function(require,module,exports){
var authModule = angular.module("AuthModule", ['ngRoute']);
module.exports = "AuthModule";
},{}],9:[function(require,module,exports){
var mainModule = angular.module("MainModule", ['ngRoute',require('./AuthModule')])
    .factory('config', require('./../factory/Config'))
    .service('AuthService', require("./../services/AuthService"))
    .service('DAOService', require("./../services/DAOService"))
    .controller("mainController",["$routeParams", "config", "$location", "AuthService", require("./../controllers/MainController")])
    .controller("clientsController",["$routeParams",require("./../controllers/ClientsController")])
    .controller("adminController",["$routeParams", "AuthService", require("./../controllers/AdminController")])
    .controller("productsController",["$routeParams",require("./../controllers/ProductsController")])
    .controller("inController",["$routeParams",require("./../controllers/InController")])
    .config(['$routeProvider','$locationProvider',require("./../Routing")]);
},{"./../Routing":1,"./../controllers/AdminController":2,"./../controllers/ClientsController":3,"./../controllers/InController":4,"./../controllers/MainController":5,"./../controllers/ProductsController":6,"./../factory/Config":7,"./../services/AuthService":10,"./../services/DAOService":11,"./AuthModule":8}],10:[function(require,module,exports){
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
},{}],11:[function(require,module,exports){
module.exports =  function ($http, Session) {

};
},{}]},{},[9]);
