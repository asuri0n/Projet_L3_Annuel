var mainModule = angular.module("MainModule", ['ngRoute',require('./AuthModule')])
    .factory('config', require('./../factory/Config'))
    .service('AuthService', require("./../services/AuthService"))
    .service('DAOService', require("./../services/DAOService"))
    .controller("mainController",["$routeParams", "config", "$location", "AuthService", require("./../controllers/MainController")])
    .controller("clientsController",["$routeParams",require("./../controllers/ClientsController")])
    .controller("productsController",["$routeParams",require("./../controllers/ProductsController")])
    .controller("inController",["$routeParams",require("./../controllers/InController")])
    .config(['$routeProvider','$locationProvider',require("./../Routing")]);