var app = angular.module('app');

app.config(function(localStorageServiceProvider) {
	localStorageServiceProvider.setStorageType('sessionStorage');
});

app.config(['$httpProvider', function($httpProvider) {
	$httpProvider.interceptors.push(function($rootScope, $timeout, $q, $injector, localStorageService) {
		$timeout(function() {
			$http = $injector.get('$http');
			$state = $injector.get('$state');
		});
		return {
			responseError: function(rejection) {
				if (rejection.status !== 401) {
					return rejection;
				}
				var deferred = $q.defer();
				$state.go('access.login');
	            deferred.reject(rejection);
	            return deferred.promise;
			},
			request: function(config) {
				var currentUser = localStorageService.get('currentUser');
				var accessToken = currentUser?currentUser.access_token:null;
				if (accessToken) {
		            config.headers['X-Stakeholders-Token'] = accessToken;
		            config.headers['X-Stakeholders-Refresh'] = currentUser.refresh_token;
		        }
				config.headers['X-Stakeholders-Client'] = 'default-client';
				return config;
			}
		};
	});
}]);

app.config(['$stateProvider', '$urlRouterProvider', '$httpProvider', '$ocLazyLoadProvider', 
	function($stateProvider, $urlRouterProvider, $httpProvider, $ocLazyLoadProvider) {
	var controllersRoot = (Routing.generate('sh_app_root')).replace('app_dev.php/', '') + 'bundles/stakeholdersclient/js/controllers/';
	$urlRouterProvider.otherwise('/access/login');
	
	$stateProvider
		.state('access', {
	    	url: '/access',
	    	template: '<div class="full-height" ui-view></div>',
	    	data: {
	    		requireLogin: false
	    	}
	    })
		.state('access.login', {
	    	url: '/login',
	    	templateUrl: Routing.generate('sh_block_login'),
	    	resolve: lazyLoad(['security'], [])
	    })
	    .state('access.register', {
	    	url: '/register',
	    	templateUrl: Routing.generate('sh_block_register'),
	    	resolve: lazyLoad(['register'], [])
	    })
	    .state('access.confirmation', {
	    	url: '/confirmation',
	    	templateUrl: Routing.generate('sh_block_confirmation'),
	    })
	    .state('app', {
            abstract: true,
            url: '/app',
            templateUrl: Routing.generate('sh_block_app'),
	    	data: {
	    		requireLogin: true
	    	},
	    	resolve: lazyLoad(['header'], [])
        })
        .state('app.dashboard', {
        	url: '/dashboard',
            templateUrl: Routing.generate('sh_page_dashboard'),
            controller: 'DashboardCtrl',
            resolve: lazyLoad(['dashboard'], [
            	'nvd3',
                'mapplic',
                'rickshaw',
                'metrojs',
                'sparkline',
                'skycons',
                'switchery'
            ])
        })
        .state('app.user_profile', {
        	url: '/profile',
        	templateUrl: Routing.generate('sh_page_user_profile'),
        	resolve: lazyLoad(['user-profile'], [])
        });
	
	function lazyLoad(ctrl, plugins) {
		var controllers = [];
		angular.forEach(ctrl, function(val, key) {
    		controllers.push(controllersRoot + val + '.js');
    	});
		return {
            deps: ['$ocLazyLoad', function($ocLazyLoad) {
                return $ocLazyLoad.load(plugins, {insertBefore: '#lazyload_placeholder'}).then(function() {
                	return $ocLazyLoad.load(controllers);
                });
            }]
        };
	};
}]);