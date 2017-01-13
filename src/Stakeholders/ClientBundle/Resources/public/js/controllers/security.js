'use strict';

angular.module('app')
	.controller('SecurityCtrl', ['$rootScope', '$scope', '$state', '$stateParams', '$http', 'authService', 'socialLoginService', 'localStorageService', function($rootScope, $scope, $state, $stateParams, $http, authService, socialLoginService, localStorageService) {
		$scope.user = {};
		$scope.customer_type;
		$scope.googleUser = {};
		$scope.login_error = false;
		$scope.signIn = function() {
			authService($scope.user.username, $scope.user.password).then(function(user) {
				if (user === false) {
					$scope.login_error = true;
				} else {
					$http({
						method: 'GET',
						url: Routing.generate('sh_me'),
						cache: false
					}).then(function(resp) {
						console.log(resp);
						localStorageService.set('userData', resp);
						$state.transitionTo('app.dashboard', $stateParams, {reload: true, inherit: true, notify: true});
					});
				}
			});
		};
		
		$scope.signInWithSocial = function(network) {
			socialLoginService(network, $scope.customer_type);
		};
	}]);