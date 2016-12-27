'use strict';

angular.module('app')
	.controller('HeaderCtrl', ['$rootScope', '$scope', function($rootScope, $scope) {
		$scope.username = $rootScope.getUserData('username');
	}]);