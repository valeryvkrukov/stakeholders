'use strict';

angular.module('app')
	.controller('SearchCtrl', ['$scope', function($scope) {
		$scope.showSearchOverlay = function() {
			$scope.$broadcast('toggleSearchOverlay', {
				show: true
			});
		};
	}]);