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
			var id = $scope.lastId + '*';
			$scope.lastId++;
			return id;

		}

		$scope.eventId = CachedEvent.id;
		$scope.variations = CachedEvent.variations;
		$scope.priceOperators = CachedPriceOperators;

		//console.log(CachedCombinations);
		if (CachedCombinations instanceof Array) {
			$scope.variationsCombinations = {};
		} else {
			$scope.variationsCombinations = CachedCombinations;
		}


		$scope.selectedCombination = {};
		$scope.addVariation = function() {
			var defaultVariation = {
				id: $scope.newClientId(),
				name: '',
				options: [
					{id: $scope.newClientId(), name: ''}
				]
			};
			$scope.variations.push(defaultVariation);

			//we add a variation. remove all combinations
			$scope.variationsCombinations = {};
		};
		$scope.addOption = function($index) {
			$scope.variations[$index].options.push({id: $scope.newClientId(), name: ''});
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

			//we should remove combinations with this options
			angular.forEach($scope.variationsCombinations, function(_combination) {
				var keys = _combination.groupKey.split('-');

				if (keys.indexOf(option.id) >= 0) {
					//unset the combination
					delete $scope.variationsCombinations[_combination.groupKey];
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

			//we delete a variation. remove all combinations
			$scope.variationsCombinations = {};
		};
		$scope.addAllCombinations = function() {
			var options = [];
			angular.forEach($scope.variations, function(_var) {
				var opt = [];
				angular.forEach(_var.options, function(_opt) {
					opt.push(_opt);
				});
				options.push(opt);
			});

			//Create all options combinations
			var r = [], arg = options, max = arg.length - 1;
			function helper(arr, i) {
				for (var j = 0, l = arg[i].length; j < l; j++) {
					var a = arr.slice(0); // clone arr
					a.push(arg[i][j])
					if (i == max) {
						r.push(a);
					} else
						helper(a, i + 1);
				}
			}
			helper([], 0);

			//add combinations to the object
			angular.forEach(r, function(_group) {
				var groupKey = []
				//var options = [];
				//construct groupKey and options array
				angular.forEach(_group, function(_opt) {
					groupKey.push(_opt.id);
					/*options.push(
					 {
					 variationId: _opt.variationId,
					 id: _opt.id,
					 name: _opt.name
					 });*/
				});
				groupKey.sort();

				//if this combination is not already defined
				if (!$scope.variationsCombinations.hasOwnProperty(groupKey.join('-'))) {
					var combination = {
						id: null,
						groupKey: groupKey.join('-'),
						priceOperator: DefaultPriceOperator,
						price: 0,
						quantity: 0,
						options: _group
					};

					$scope.variationsCombinations[combination.groupKey] = combination;
				}
			});


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
					quantity: 0,
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

 