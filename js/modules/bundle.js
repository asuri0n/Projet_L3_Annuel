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
module.exports = function($routeParams) {

};
},{}],3:[function(require,module,exports){
module.exports = function($routeParams){

};
},{}],4:[function(require,module,exports){
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
},{}],5:[function(require,module,exports){
arguments[4][2][0].apply(exports,arguments)
},{"dup":2}],6:[function(require,module,exports){
module.exports = function(){
    return {
        nom: "Mutlti-Modules APP",
        version: "1.0",
        paths:
        {
            "/": [{caption: "Accueil", href: "/"}],
            "/about": [{caption: "A propos", href: "about"}],
            "/login": [{caption: "Se connecter", href: "login"}]
        }
    }
};
},{}],7:[function(require,module,exports){
var authModule = angular.module("AuthModule", ['ngRoute']);
module.exports = "AuthModule";
},{}],8:[function(require,module,exports){
var mainModule = angular.module("MainModule", ['ngRoute',require('./AuthModule')])
    .factory('config', require('./../factory/Config'))
    .service('AuthService', require("./../services/AuthService"))
    .service('DAOService', require("./../services/DAOService"))
    .controller("mainController",["$routeParams", "config", "$location", "AuthService", require("./../controllers/MainController")])
    .controller("clientsController",["$routeParams",require("./../controllers/ClientsController")])
    .controller("productsController",["$routeParams",require("./../controllers/ProductsController")])
    .controller("inController",["$routeParams",require("./../controllers/InController")])
    .config(['$routeProvider','$locationProvider',require("./../Routing")]);
},{"./../Routing":1,"./../controllers/ClientsController":2,"./../controllers/InController":3,"./../controllers/MainController":4,"./../controllers/ProductsController":5,"./../factory/Config":6,"./../services/AuthService":9,"./../services/DAOService":10,"./AuthModule":7}],9:[function(require,module,exports){
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
            if(value.pseudo == pseudo && value.password == password) {
                ret = true;
                self.activeUser = {pseudo: value.pseudo, date: new Date()};
            }
        });
        return ret;
    };

    authService.isAuth = function (pseudo) {
        if (activeUser.pseudo == pseudo)
            return true;
        else
            return false;
    };

    return authService;
};
},{}],10:[function(require,module,exports){
module.exports =  function ($http, Session) {

};
},{}]},{},[8]);
