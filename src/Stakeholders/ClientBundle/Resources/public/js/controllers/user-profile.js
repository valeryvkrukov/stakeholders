'use strict';

angular.module('app')
	.controller('UserProfileCtrl',['$rootScope', '$scope', function($rootScope, $scope) {
		$scope.username = $rootScope.getUserData('username');
		$scope.email = $rootScope.getUserData('email');
		$scope.fullname = $rootScope.getUserData('fullname');
		$scope.getAccessLevel = function() {
			var roles = $rootScope.getUserData('roles');
			return roles[0].replace('ROLE_', '').replace('_', ' ');
		};
	}]);