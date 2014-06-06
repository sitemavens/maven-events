'use strict';

angular.module('mavenEventsListApp').controller('EventListCtrl',
	['$scope', '$http', 'EventsService', 'EventsFilterService',
		function($scope, $http, EventsService, EventsFilterService) {
			
			$scope.filter = EventsFilterService;
			$scope.filter.search = 'searchit';

			$scope.event_count = 10;
			$scope.draft_count = 5;


			$scope.doSearch = function() {
				EventsService.getList($scope.filter, function(data) {
					console.log(data);
				});
				//alert("Search: '" + $scope.filter.search + "'");
			}

		}]);
