angular.module('mavenEventsApp').controller('EventCtrl', ['$scope', '$http', function($scope, $http) {

		$scope.event = CachedEvent;

//		var chatbox = angular.element(document.getElementById('publish'));
//		chatbox.val('holaaa');
//		
//		var postForm = angular.element(document.getElementById('post'));
//		angular.element.bind("submit", function(event) {
//			console.log('binded');
//		}

		//ng-disabled="formSettings.$invalid"
	}]);


angular.module('mavenEventsApp').controller('VariationsCtrl', ['$scope', '$http', function($scope, $http) {

		$scope.selectedVariation = {
			id: -1,
			options: []
		};

		$scope.variations = [{
				id: 1,
				name: 'Color',
				options: [
					{id: 1, name: 'Red'},
					{id: 2, name: 'Blue'}
				]
			},
			{
				id: 2,
				name: 'Size',
				options: [
					{id: 1, name: 'Small'},
					{id: 2, name: 'Medium'}
				]
			}];

		$scope.addVariation = function() {
			var defaultVariation = {
				id: -1,
				name: 'Variation',
				options: [
					{id: 1, name: 'Option 1'},
					{id: 2, name: 'Option 2'}
				]
			};

			$scope.variations.push(defaultVariation);
		};

		$scope.deleteOption = function(variation, option) {

			angular.forEach($scope.variations, function(_variation) {
				if (_variation.id === variation.id) {
					var index = 0;
					angular.forEach(variation.options, function(_option) {
						if (_option.id === option.id) {
							variation.options.splice(index, 1);
							//_variation.options.unshift(_option);
						}
						index++;
					});

				}
			});
		};

		$scope.deleteVariation = function(variation) {
			var index = 0;
			angular.forEach($scope.variations, function(_variation) {
				if (_variation.id === variation.id) {
					$scope.variations.splice(index, 1);

					if ($scope.selectedVariation.id == variation.id) {
						$scope.selectedVariation = {
							id: -1,
							options: []
						};
					}
				}
				index++;

			});


		};


	}]);

 