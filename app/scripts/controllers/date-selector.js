'use strict';

angular.module('gfMarketingApp')
	.controller('DateSelectorCtrl', ['$rootScope', '$scope', function($rootScope, $scope) {
			var from = new Date()
			var to = new Date();
			from.setMonth(from.getMonth() - 1);
			$scope.filterService.from = from;
			$scope.filterService.to = to;
			$scope.maxDate = new Date();
			$rootScope.$broadcast('filter:update', null);
			$scope.showWeeks = false;

			$scope.fromOpened = false;
			$scope.toOpened = false;

			$scope.openFrom = function($event) {
				$event.preventDefault();
				$event.stopPropagation();

				$scope.fromOpened = true;
			};
			$scope.openTo = function($event) {
				$event.preventDefault();
				$event.stopPropagation();

				$scope.toOpened = true;
			};

			$scope.dateOptions = {
				'year-format': "'yy'",
				'starting-day': 1
			};

			$scope.ranges = {
				'today': {'startDate': new Date(), 'endDate': new Date()}
			};

			$scope.selectTo = function() {
				//$rootScope.$broadcast('filter:update', null);
			};

			$scope.selectFrom = function() {
				//$rootScope.$broadcast('filter:update', null);
			}
		}]);
