'use strict';

angular.module('app')
	.controller('InfluencerCampaignsCtrl', ['$rootScope', '$scope', '$state', 'localStorageService', function($rootScope, $scope, $state, localStorageService) {
		var currentUser = localStorageService.get('currentUser');
		var accessToken = currentUser?currentUser.access_token:null;
		$scope.campaigns = [];
		if (accessToken) {
			$scope.filter = '';
			$scope.loadCampaign = function(id) {
				//$state.transitionTo('app.influencer', {'id': id});
			};
			$scope.filterByStatus = function(status) {
				$scope.filter = status == 'all'?'':status;
			};
			$http({
				url: Routing.generate('get_influencer_campaigns', {'id': $rootScope.getUserData('id')}),
				method: 'GET'
			}).then(function(resp) {
				console.log(resp.data);
				$scope.campaigns = resp.data;
			});
		}
	}]);