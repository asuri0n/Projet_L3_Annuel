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