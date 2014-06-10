angular.module('mavenEventsApp').controller('EventCtrl', ['$scope', '$http', function($scope, $http) {

		console.log(CachedEvent);
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

		$scope.guid = function() {
			return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
				var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
				return v.toString(16);
			});
		}

		$scope.join = function(arr1, arr2)
		{
			var union = arr1.concat(arr2);

			for (var i = 0; i < union.length; i++)
			{
				for (var j = i + 1; j < union.length; j++)
				{
					if ($scope.sameCombination(union[i], union[j]))
						union.splice(i, 1);
				}
			}

			return union;
		}

		$scope.sameCombination = function(g1, g2)
		{
			return g1.groupKey === g2.groupKey;
		}

		$scope.eventId = CachedEvent.id;
		$scope.variations = CachedEvent.variations;

		$scope.priceOperators = CachedPriceOperators;

		$scope.variationsCombinations = [];
		for (var i = 0; i < CachedEvent.variations.length; i++) {

			$scope.variationsCombinations = $scope.join($scope.variationsCombinations, CachedEvent.variations[i].groups);
		}
		console.log($scope.variationsCombinations);
		//$scope.variationsCombinations = [];
		$scope.selectedCombination = {};
		/*$scope.variations = [{
		 id: $scope.guid(),
		 name: 'Color',
		 options: [
		 {id: $scope.guid(), name: 'Red'},
		 {id: $scope.guid(), name: 'Blue'}
		 ]
		 },
		 {
		 id: $scope.guid(),
		 name: 'Size',
		 options: [
		 {id: $scope.guid(), name: 'Small'},
		 {id: $scope.guid(), name: 'Medium'}
		 ]
		 }];*/
		$scope.addVariation = function() {
			var defaultVariation = {
				id: null,
				c_id: $scope.guid(),
				name: '',
				options: [
					{id: null, c_id: $scope.guid(), name: ''}
				]
			};
			$scope.variations.push(defaultVariation);
			$scope.lastId++;
		};
		$scope.addOption = function($index) {
			$scope.variations[$index].options.push({id: null, c_id: $scope.guid(), name: ''});
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
		$scope.combinationsSelected = function() {
			var i = 0;
			for (var property in $scope.selectedCombination) {
				i++;
			}
			//console.log(i, $scope.variations.length);
			return !(i === $scope.variations.length);
		}

		$scope.addCombination = function(variationCombination) {
			var id = 'id';
			var options = [];
			for (var property in variationCombination) {
				id += '_' + variationCombination[property].id;
				options.push(
					{
						variation: property,
						option: {
							variationId: variationCombination[property].variationId,
							id: variationCombination[property].id,
							name: variationCombination[property].name
						}
					})
			}

			var combination = {
				id: id,
				priceOperator: DefaultPriceOperator,
				options: options
			}

			$scope.variationsCombinations.push(combination);
		};
		$scope.deleteCombination = function($index) {
			$scope.variationsCombinations.splice($index, 1);
		}


	}]);

 