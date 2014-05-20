'use strict';

angular.module('gfMarketingApp').controller('SettingsCtrl', ['$scope', '$http', function($scope, $http) {

		//$scope.settings = [{name: 'sentEmailTo', label: 'Sent Email To', value: 'ejankowski@gmail.com', type: 'email'}];
		$scope.formFields = {
			updateReferralLabel: 'Update referrals'
		};


		$http({
			method: 'POST',
			url: GFSeoMk.ajaxUrl,
			data: 'action=getSettings'
		}).success(function(data) {
			$scope.settings = data;
		});


		$scope.saveSettings = function(settings) {

			var data = {action: 'saveSettings', settings: settings};
			var serializedSettings = jQuery.param(data);
			$http({
				method: 'POST',
				url: GFSeoMk.ajaxUrl,
				data: serializedSettings
			}).success(function(data) {
				$scope.settings = data;
			});
		};


		$scope.updateReferrals = function() {
			$scope.formFields.updateReferralLabel = 'Updating...';

			var data = {action: 'updateReferrals'};
			var serializedSettings = jQuery.param(data);
			$http({
				method: 'POST',
				url: GFSeoMk.ajaxUrl,
				data: serializedSettings
			}).success(function(data) {
				$scope.formFields.updateReferralLabel = 'Update referrals';
			});

		};
	}]);
