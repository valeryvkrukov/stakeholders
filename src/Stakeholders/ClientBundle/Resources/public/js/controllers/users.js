'use strict';

angular.module('app')
	.controller('UsersCtrl', ['$rootScope', '$scope', '$state', 'localStorageService', function($rootScope, $scope, $state, localStorageService) {
		var currentUser = localStorageService.get('currentUser');
		var accessToken = currentUser?currentUser.access_token:null;
		if (accessToken) {
			$scope.loadProfile = function(id) {
				$state.transitionTo('app.profile', {'id': id});
			};
			$scope.options = {
				sPaginationType: 'bootstrap',
				iDisplayLength: 10,
				lengthChange: false,
				ajax: {
					url: Routing.generate('get_users_list'),
					type: 'GET',
					dataSrc: '',
					beforeSend: function(req) {
						req.setRequestHeader('Authorization', 'Bearer ' + accessToken);
						req.setRequestHeader('X-Stakeholders-Client', 'default-client');
					},
					error: function(err) {
						if (err.status == 401) {
							$state.go('access.login');
						}
					}
				},
				columns: [
					{data: 'id'},
					{data: 'username'},
					{data: 'email'},
					{data: 'role'},
					{
						data: null,
						mRender: function(data, type, full) {
							return '<a onclick="angular.element(this).scope().loadProfile(' + data.id + ')"><i class="fa fa-pencil-square-o"></i></a>';
						}
					}
				]
			};
		}
	}]);