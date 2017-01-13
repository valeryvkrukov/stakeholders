'use strict';

angular.module('app')
	.controller('MeCtrl',['$rootScope', '$scope', '$stateParams', function($rootScope, $scope, $stateParams) {
		$scope.user = {
			username: $rootScope.getUserData('username'),
			email: $rootScope.getUserData('email'),
			first_name: $rootScope.getUserData('first_name'),
			last_name: $rootScope.getUserData('last_name'),
			getAccessLevel: function() {
				return $rootScope.getUserData('role');
			},
			profile_image: $rootScope.getUserData('profile_image'),
			brief: $rootScope.getUserData('brief')
		};
	}]);