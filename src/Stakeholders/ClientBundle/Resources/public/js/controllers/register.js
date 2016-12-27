'use strict';

angular.module('app')
	.controller('RegisterCtrl', ['$rootScope', '$scope', '$state', '$stateParams', 'registerService', function($rootScope, $scope, $state, $stateParams, registerService) {
		$scope.user = {
			username: null,
			password: null
		};
		$scope.signUp = function() {
			registerService($scope.user).then(function(user) {
				$state.transitionTo('access.confirmation', $stateParams, {reload: true, inherit: true, notify: true});
			});
		};
	}]);