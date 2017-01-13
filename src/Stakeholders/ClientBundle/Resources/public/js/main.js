var app = angular.module('app');

app.controller('AppCtrl', ['$scope', '$rootScope', '$state', '$stateParams', 'logoutService', 'localStorageService', function($scope, $rootScope, $state, $stateParams, logoutService, localStorageService) {
	$scope.app = {
		name: 'Influence',
		description: 'Influence by insydo',
		layout: {
			menuPin: false,
			menuBehind: false
		},
		author: ''
	};
	$scope.is = function(name) {
		return $state.is(name);
    };
	$scope.includes = function(name) {
		return $state.includes(name);
    };
    $rootScope.getUserData = function(key) {
    	var userData = localStorageService.get('userData');
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
			if (window.location.href.indexOf('access/connect') == -1) {
				return $state.transitionTo('access.login', $stateParams, {reload: true, inherit: true, notify: true});
			}
		}
	});
}]);

app.run(['$rootScope', '$window', function($rootScope, $window) {
	$rootScope.user = {};
	$rootScope.googleUser = {};
	$rootScope.auth2;
	$window.fbAsyncInit = function() {
		FB.init({
			appId: fbAppId,
			channelUrl: Routing.generate('sh_app_root') + '/channel.html',
			status: true,
			cookie: true,
			xfbml: true,
			version: 'v2.4'
		});
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
	    
	    gapi.load('client:auth2', function() {
	    	gapi.auth2.init({
                client_id: googleAppId
            });
        });
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

app.service('logoutService', ['$rootScope', '$http', 'localStorageService', function($rootScope, $http, localStorageService) {
	return function() {
		var instance = $http({
			method: 'POST',
			cache: false,
			url: Routing.generate('sh_block_logout'),
		});
		if ($rootScope.getUserData('network')) {
			if ($rootScope.getUserData('network') == 'facebook') {
				FB.logout(function(response) {
					console.log(response);
				});
			}
			if ($rootScope.getUserData('network') == 'google') {
				gapi.auth2.getAuthInstance().signOut();
			}
		}
		return instance.then(localStorageService.clearAll);
	};
}]);

app.service('registerService', ['$http', 'localStorageService', function($http, localStorageService) {
	var getResponse = function(data) {
		
	};
	return function(data, userType) {
		var instance = $http({
			method: 'POST',
			cache: false,
			url: Routing.generate('sh_block_register', {'type': userType}),
			headers: {
				'Content-Type': 'multipart/form-data'
			},
			data: data
		});
		return instance.then(getResponse);
	};
}]);

app.service('socialConnect', ['$rootScope', '$http', function($rootScope, $http) {
	var userData = {};
	return function(network) {
		if (network == 'facebook') {
			FB.getLoginStatus(function(response) {
				if (response.status !== 'connected') {
					FB.login(function(resp){
						if (resp.authResponse) {
							userData = FB.getAuthResponse();
						}
					}, {scope: 'public_profile,email'});
				} else {
					userData = FB.getAuthResponse();
				}
			});
		}
		if (network == 'google') {
			if (gapi.auth2.getAuthInstance().isSignedIn.get()) {
				var authInfo = gapi.auth2.getAuthInstance().currentUser.get().getAuthResponse();
				userData = {
					accessToken: authInfo['id_token'],
					expiresIn: authInfo['expires_in'],
					userID: gapi.auth2.getAuthInstance().currentUser.get().getBasicProfile().getId(),
					email: gapi.auth2.getAuthInstance().currentUser.get().getBasicProfile().getEmail()
				};
			} else {
				gapi.auth2.getAuthInstance().signIn().then(function(user) {
					var authInfo = gapi.auth2.getAuthInstance().currentUser.get().getAuthResponse();
					userData = {
						accessToken: authInfo['id_token'],
						userID: user.getBasicProfile().getId(),
						email: user.getBasicProfile().getEmail()
					};
				});
			}
		}
		return userData;
	};
}]);

app.service('socialLoginService', ['$rootScope', '$http', '$state', '$stateParams', 'localStorageService', function($rootScope, $http, $state, $stateParams, localStorageService) {
	var authProcess = function(network, res) {
		$http({
			method: 'POST',
			cache: false,
			url: Routing.generate('sh_block_social_auth', {'network': network}),
			data: res
		}).then(function(resp) {
			localStorageService.set('currentUser', {
				access_token: resp.data.tokens.access_token,
				refresh_token: resp.data.tokens.refresh_token
			});
			localStorageService.set('userData', {data: resp.data.userData});
			$state.transitionTo('app.dashboard', $stateParams, {reload: true, inherit: true, notify: true});
		});
	};
	var getFBUserInfo = function(network, customerType) {
		FB.api('/me', {fields: 'id,email,first_name,last_name'}, function(res) {
			angular.extend(res, {role: customerType});
		    authProcess(network, res);
		});
	};
	return function(network, customerType) {
		if (network == 'facebook') {
			FB.getLoginStatus(function(response) {
				if (response.status !== 'connected') {
					FB.login(function(){
						getFBUserInfo(network, customerType);
					}, {scope: 'public_profile,email'});
				} else {
					getFBUserInfo(network, customerType);
				}
			});
		}
		if (network == 'google') {
			gapi.auth2.getAuthInstance().signIn().then(function(user) {
				var profile = {
					id: user.getBasicProfile().getId(),
					first_name: user.getBasicProfile().getGivenName(),
					last_name: user.getBasicProfile().getFamilyName(),
					image: user.getBasicProfile().getImageUrl(),
					email: user.getBasicProfile().getEmail(),
					role: customerType
				};
				authProcess(network, profile);
			});
		}
	}
}]);

app.service('userDataService', ['$http', function($http) {
	return function(uid) {
		var instance = $http({
			url: Routing.generate('get_profile', {'id': uid}),
			type: 'GET'
		});
		return instance;
	};
}]);

app.service('loadFeedService', ['$http', function($http) {
	return function(network) {
		var instance = $http({
			url: Routing.generate('get_feed', {'network': network}),
			method: 'GET'
		});
		return instance;
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

app.directive('bsModal', function() {
	return {
		restrict: 'A',
		link: function(scope, element, attrs) {
			scope.dismiss = function() {
				element.modal('hide');
			};
		}
	};
});

app.directive('fileinput', [function() {
	return {
		scope: {
			fileinput: '=',
	        filepreview: '='
		},
		link: function(scope, element, attributes) {
			element.bind('change', function(changeEvent) {
				scope.fileinput = changeEvent.target.files[0];
				var reader = new FileReader();
				reader.onload = function(loadEvent) {
					scope.$apply(function() {
						scope.filepreview = loadEvent.target.result;
					});
				};
				reader.readAsDataURL(scope.fileinput);
			});
		}
	};
}]);

app.filter('capitalize', function() {
    return function(input) {
      return (!!input) ? input.charAt(0).toUpperCase() + input.substr(1).toLowerCase() : '';
    }
});
