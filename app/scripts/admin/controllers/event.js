angular.module('mavenEventsApp').controller('EventCtrl', ['$scope', '$http', function($scope, $http) {
		$scope.event = CachedEvent;
	}]);
angular.module('mavenEventsApp').controller('VariationsCtrl', ['$scope', '$http', function($scope, $http) {

		$scope.guid = function() {
			return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
				var r = Math.random() * 16 | 0, v = c == 'x' ? r : (r & 0x3 | 0x8);
				return v.toString(16);
			});
		}
		$scope.lastId = 1;
		$scope.newClientId = function() {
			var id = $scope.lastId;
			$scope.lastId++;
			return id;

		}

		$scope.eventId = CachedEvent.id;
		$scope.variations = CachedEvent.variations;
		$scope.priceOperators = CachedPriceOperators;

		//console.log(CachedCombinations);
		$scope.variationsCombinations = CachedCombinations;


		$scope.selectedCombination = {};
		$scope.addVariation = function() {
			var defaultVariation = {
				id: null,
				c_id: $scope.newClientId(),
				name: '',
				options: [
					{id: null, c_id: $scope.newClientId(), name: ''}
				]
			};
			$scope.variations.push(defaultVariation);			
		};
		$scope.addOption = function($index) {
			$scope.variations[$index].options.push({id: null, c_id: $scope.newClientId(), name: ''});			
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
					/*if ($scope.selectedVariation.id === variation.id) {
						$scope.selectedVariation = {
							id: -1,
							options: []
						};
					}*/
				}
				index++;
			});
		};
		$scope.addAll = function() {
			console.log('Add all');
		};

		$scope.allCombinationsDisabled = function() {
			//if there is no variations, disable combination button
			return $scope.variations.length === 0;

		}

		$scope.addCombinationDisabled = function() {
			//if there is no variations, disable combination button
			if ($scope.variations.length === 0) {
				return true;
			}

			var i = 0;
			for (var property in $scope.selectedCombination) {
				i++;
			}
			//console.log(i, $scope.variations.length);
			return !(i === $scope.variations.length);
		};
		/*
		 var found = $scope.items.reduce(function(previous, i){
		 if ($scope.item === i) return true;
		 return previous;
		 }, false);
		 if (found){
		 alert('duplicate value');
		 }
		 else{
		 $scope.items.push($scope.item);
		 }
		 */
		$scope.addCombination = function(variationCombination) {
			var id = [];
			var options = [];

			for (var property in variationCombination) {
				id.push(variationCombination[property].id);
				options.push(
					{
						variationId: variationCombination[property].variationId,
						id: variationCombination[property].id,
						name: variationCombination[property].name
					});
			}
			//we need to sort the array, always the ids need to be ordered.
			id.sort();
			if (!$scope.variationsCombinations.hasOwnProperty(id.join('-'))) {

				var combination = {
					id: null,
					groupKey: id.join('-'),
					priceOperator: DefaultPriceOperator,
					price: 0,
					options: options
				};



				$scope.variationsCombinations[combination.groupKey] = combination;
			} else {
				alert("Combination already defined");
			}

		};
		$scope.deleteCombination = function(groupKey) {
			delete $scope.variationsCombinations[groupKey];
		};

	}]);

 