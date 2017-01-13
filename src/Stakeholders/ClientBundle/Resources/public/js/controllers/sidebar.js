'use strict';

angular.module('app')
	.controller('SidebarCtrl', ['$rootScope', '$scope', 'localStorageService', function($rootScope, $scope, localStorageService) {
		$scope.user = localStorageService.get('userData');
	}]);