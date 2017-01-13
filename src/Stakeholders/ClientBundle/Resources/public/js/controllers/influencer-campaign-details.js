'use strict';

angular.module('app')
	.controller('InfluencerCampaignDetailsCtrl', ['$rootScope', '$scope', '$state', '$stateParams', 'localStorageService', function($rootScope, $scope, $state, $stateParams, localStorageService) {
		var currentUser = localStorageService.get('currentUser');
		var accessToken = currentUser?currentUser.access_token:null;
		$scope.campaigns = [];
		if (accessToken) {
			$scope.filter = '';
			$scope.loadCampaign = function(id) {
				//$state.transitionTo('app.influencer', {'id': id});
			};
			$scope.timelineView = false;
			$scope.viewType = 'Timeline View';
			$scope.switchView = function() {
				console.log($scope.timelineView);
				$scope.timelineView = !$scope.timelineView;// == true?false:true;
				$scope.viewType = 'Details View';
			};
			$http({
				url: Routing.generate('get_influencer_campaign_details', {'id': $stateParams.id}),
				method: 'GET'
			}).then(function(resp) {
				console.log(resp.data);
				$scope.campaign = resp.data;
			});
		}
	}]);