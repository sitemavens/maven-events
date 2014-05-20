'use strict';

angular.module('gfMarketingApp')
	.controller('FormSelectorCtrl', ['$scope', function($scope) {
			$scope.showSelect = false;
			$scope.toggleSelect = function() {
				//console.log('toggle');
				$scope.showSelect = !$scope.showSelect;
			};

		}]);
