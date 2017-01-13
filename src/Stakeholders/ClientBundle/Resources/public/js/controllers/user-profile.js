'use strict';

angular.module('app')
	.controller('UserProfileCtrl',['$rootScope', '$scope', '$stateParams', 'userDataService', 'loadFeedService', function($rootScope, $scope, $stateParams, userDataService, loadFeedService) {
		if ($stateParams.id) {
			userDataService($stateParams.id).then(function(resp) {
				$scope.user = resp.data;
			});
		} else {
			$scope.user = {
				username: $rootScope.getUserData('username'),
				email: $rootScope.getUserData('email'),
				first_name: $rootScope.getUserData('first_name'),
				last_name: $rootScope.getUserData('last_name'),
				getAccessLevel: function() {
					return $rootScope.getUserData('role');
				},
				profile_image: $rootScope.getUserData('profile_image'),
				brief: $rootScope.getUserData('brief'),
				socials: $rootScope.getUserData('socials')
			};
		}
		$scope.newAccountFieldValueType = 'text';
		$scope.parseJSONField = function(val) {
			return JSON.parse(val).join(',');
		};
		$scope.parseJSONFieldToArray = function(val) {
			try {
				return JSON.parse(val);
			} catch (e) {
				return '';
			}
		};
		$scope.isArray = function(val) {
			try {
		        var val = JSON.parse(val);
		        return angular.isArray(val)
		    } catch (e) {
		        return false;
		    }
		    return true;
		};
		$scope.addAccountField = function(evt) {
			var fieldName = $scope.newAccountFieldName;
			var fieldType = $scope.newAccountFieldValueType;
			if (fieldType == 'json') {
				var fieldValue = [];
				var values = angular.element('#newAccountFieldValueInput .bootstrap-tagsinput .tag');
				angular.forEach(values, function(val, key) {
					fieldValue.push(val.innerText);
				});
				fieldValue = fieldValue.join(', ');
			} else {
				var fieldValue = angular.element('#newAccountFieldValueInput :input').val();
			}
			 $scope.account.fields.push({
				 id: 0,
				 name: fieldName,
				 value_type: fieldType,
				 value: fieldValue
			 });
			 $scope.newAccountFieldName = null;
			 $scope.newAccountFieldValueType = 'text';
			 $scope.dismiss();
		};
		$scope.loadFeed = function(network) {
			loadFeedService(network).then(function(feed) {
				console.log(feed);
			});
		};
	}]);