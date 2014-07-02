angular.module('mavenEventsApp').controller('VenueCtrl', ['$scope', '$http', function($scope, $http) {		
		 $scope.countries = CachedCountries;
		 $scope.venue = CachedVenue;
		 console.log($scope.venue);
	}]);
 