angular.module('mavenEventsApp').controller('VenueCtrl', ['$scope', '$http', 'geocoder', function($scope, $http, geocoder) {
		$scope.map = {
			center: {
				latitude: 38,
				longitude: -97 
			},
			zoom: 4
		};
		$scope.countries = CachedCountries;
		$scope.venue = CachedVenue;

		$scope.refreshAddress = function() {
			$scope.completeAddress = $scope.venue.address;
//			$scope.completeAddress = $scope.venue.address + ', ' + $scope.venue.state + ', ' + $scope.venue.country; //commented for testing purposes
			var result = geocoder.geocode($scope.completeAddress, function(callbackResult) {
				var lat = callbackResult.results[0].geometry.location.lat();
				var lng = callbackResult.results[0].geometry.location.lng();
				$scope.map = {
					center: {
						latitude: lat,
						longitude: lng
					},
					refresh: true,
					zoom: 8
				};
				$scope.$apply();
			});
		};
	}]);
 