'use strict';

angular.module('app')
	.controller('SecurityCtrl', ['$rootScope', '$scope', '$state', '$stateParams', '$http', 'authService', 'localStorageService', function($rootScope, $scope, $state, $stateParams, $http, authService, localStorageService) {
		$scope.user = {
			username: null,
			password: null
		};
		$scope.signIn = function() {
			authService($scope.user.username, $scope.user.password).then(function(user) {
				$http({
					method: 'GET',
					url: Routing.generate('sh_me'),
					cache: false
				}).then(function(resp) {
					localStorageService.set('userData', resp);
					$state.transitionTo('app.dashboard', $stateParams, {reload: true, inherit: true, notify: true});
				});
			});
		};
	}]);