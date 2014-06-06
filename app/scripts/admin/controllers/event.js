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

		$scope.variationsCombinations = [];
		$scope.selectedCombination = {};
		$scope.lastId = 3;
		$scope.variations = [{
				id: '1-n',
				name: 'Color',
				options: [
					{id: '3-n', name: 'Red'},
					{id: '4-n', name: 'Blue'}
				]
			},
			{
				id: '2-n',
				name: 'Size',
				options: [
					{id: '5-n', name: 'Small'},
					{id: '6-n', name: 'Medium'}
				]
			}];

		$scope.addVariation = function() {
			var defaultVariation = {
				id: $scope.lastId + '-n',
				name: 'Variation',
				options: [
					{id: 1, name: 'Option 1'},
					{id: 2, name: 'Option 2'}
				]
			};

			$scope.variations.push(defaultVariation);

			$scope.lastId++;
		};

		$scope.addOption = function($index) {
			$scope.variations[$index].options.push({id: $scope.lastId + '-n', name: 'Option'});
			$scope.lastId++;
		};

		$scope.deleteOption = function(variation, option) {

			angular.forEach($scope.variations, function(_variation) {
				if (_variation.id === variation.id) {
					var index = 0;
					angular.forEach(variation.options, function(_option) {
						if (_option.id === option.id) {
							variation.options.splice(index, 1);
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
					if ($scope.selectedVariation.id === variation.id) {
						$scope.selectedVariation = {
							id: -1,
							options: []
						};
					}
				}
				index++;

			});

		};

		$scope.addAll = function() {
			console.log('Add all');
		};

		$scope.addCombination = function(variationCombination) {

			var id = 'id';
			var options = [];
			for (var property in variationCombination) {
				id += '_' + variationCombination[property].id;
				options.push({variation: property, option: variationCombination[property].name})
			}

			var combination = {
				id: id,
				options: options
			}

			$scope.variationsCombinations.push(combination);
		};

		$scope.deleteCombination = function($index) {
			$scope.variationsCombinations.splice($index, 1);
		}


	}]);

 