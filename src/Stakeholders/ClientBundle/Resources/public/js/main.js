var app = angular.module('app');

app.controller('AppCtrl', ['$scope', '$rootScope', '$state', '$stateParams', 'logoutService', 'localStorageService', function($scope, $rootScope, $state, $stateParams, logoutService, localStorageService) {
	$scope.app = {
		name: 'Stakeholders',
		description: '',
		layout: {
			menuPin: false,
			menuBehind: false
		},
		author: 'Valery V. Krukov'
	};
	$scope.is = function(name) {
		return $state.is(name);
    };
	$scope.includes = function(name) {
		return $state.includes(name);
    };
    $rootScope.getUserData = function(key) {
    	var userData = localStorageService.get('userData');
    	console.log(userData);
    	if (userData && userData.data[key]) {
    		return userData.data[key];
    	}
    	return 'not set';
    };
	$scope.logout = function() {
		logoutService().then(function() {
			$state.reload();
			$state.transitionTo('access.login', $stateParams, {reload: true, inherit: true, notify: true});
		});
	};
}]);

app.run(['$rootScope', '$state', '$stateParams', 'authService', 'localStorageService', function($rootScope, $state, $stateParams, authService, localStorageService) {
	$rootScope.$on('$stateChangeStart', function (event, toState, toParams) {
		var requireLogin = toState.data.requireLogin;
		var currentUser = localStorageService.get('currentUser');
		if (requireLogin && currentUser === null) {
			event.preventDefault();
			return $state.transitionTo('access.login', $stateParams, {reload: true, inherit: true, notify: true});
		}
	});
}]);

app.run(['$rootScope', '$window', function($rootScope, $window) {
	$rootScope.user = {};
	$window.fbAsyncInit = function() {
		
	};
	(function(d){
		var js,
	    	id = 'facebook-jssdk',
	    	ref = d.getElementsByTagName('script')[0];
		if (d.getElementById(id)) {
			return;
		}
		js = d.createElement('script');
	    js.id = id;
	    js.async = true;
	    js.src = "//connect.facebook.net/en_US/all.js";

	    ref.parentNode.insertBefore(js, ref);
	}(document));
}]);

app.service('authService', ['$rootScope', '$http', 'localStorageService', function($rootScope, $http, localStorageService) {
	var authenticate = function(resp) {
		if (resp.data.access_token) {
			localStorageService.set('currentUser', {
				access_token: resp.data.access_token,
				refresh_token: resp.data.refresh_token
			});
			return resp.data;
		} else {
			return false;
		}
	};
	return function(username, password) {
		var instance = $http({
			method: 'POST',
			url: Routing.generate('sh_block_login'),
			cache: false,
			data: {
				'username': username,
				'password': password
			}
		});
		return instance.then(authenticate);
	};
}]);

app.service('logoutService', ['$http', 'localStorageService', function($http, localStorageService) {
	return function() {
		var instance = $http({
			method: 'POST',
			cache: false,
			url: Routing.generate('sh_block_logout'),
		});
		return instance.then(localStorageService.clearAll);
	};
}]);

app.service('registerService', ['$http', 'localStorageService', function($http, localStorageService) {
	var getResponse = function(data) {
		
	};
	return function(data) {
		var instance = $http({
			method: 'POST',
			cache: false,
			url: Routing.generate('sh_block_register'),
			data: data
		});
		return instance.then(getResponse);
	};
}]);

app.directive('includeReplace', function() {
	return {
        require: 'ngInclude',
        restrict: 'A',
        link: function(scope, el, attrs) {
            el.replaceWith(el.children());
        }
    };
});
