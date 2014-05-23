angular.module('mavenEventsApp').controller('EventCtrl', ['$scope', '$http', function($scope, $http) {

		$scope.event = CachedEvent;
console.log('EventCtrl');

//		var chatbox = angular.element(document.getElementById('publish'));
//		chatbox.val('holaaa');
//		
//		var postForm = angular.element(document.getElementById('post'));
//		angular.element.bind("submit", function(event) {
//			console.log('binded');
//		}

		//ng-disabled="formSettings.$invalid"
	}]);


angular.module('mavenEventsApp').controller('PricesCtrl', ['$scope', '$http', function($scope, $http) {
		
		this.getEmptyPrice = function(){
			return {name:'',price:'',id:false};
		};
		
		this.showAddButton = function( ){
			$scope.labels.addButton = 'Add';
		};
		
		this.showSaveButton = function( ){
			$scope.labels.addButton = 'Save';
		};
		
		
		var that = this;
		
		$scope.selectedPrice = this.getEmptyPrice();
		$scope.labels = {
			addButton:'Add'
		};
		
		$scope.prices = [{
				id:1,
				name: 'Member',
				price: 123
			},
			{
				id:2,
				name: 'Non member',
				price: 456
			}];
		
		$scope.showPrice = function(price){
			
			$scope.selectedPrice.id = price.id; 
			$scope.selectedPrice.price = price.price;
			$scope.selectedPrice.name = price.name;
			
			that.showSaveButton();
		};
		
		$scope.cancel = function(){
			$scope.selectedPrice = that.getEmptyPrice();
			that.showAddButton();
		};
		
		$scope.delete = function( price ){
			
			angular.forEach($scope.prices, function(item){
					if ( item.id === price.id ){
						$scope.prices.unshift(item);
					}
				});
			
			$scope.selectedPrice = that.getEmptyPrice();
		};
		
		$scope.addPrice = function(price){
			
			if ( price.id !== false){
				angular.forEach($scope.prices, function(item){
					if ( item.id === price.id ){
						item.name = price.name;
						item.price = price.price;
					}
				});
			}else{
				$scope.prices.push(price);
			}
			
			// Save in db
			$scope.selectedPrice = that.getEmptyPrice();
			that.showAddButton();
		};
		
		
	}]);





mañana 