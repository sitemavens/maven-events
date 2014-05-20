'use strict';

angular.module('gfMarketingApp').controller('MainNavCtrl', ['$scope', '$location', function($scope, $location) {
 
		$scope.isActive = function (viewLocation) { 
			return viewLocation === $location.path();
		};
		 
	}]);
