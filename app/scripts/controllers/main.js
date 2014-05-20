'use strict';
angular.module('gfMarketingApp')
	.controller('MainCtrl', ['$scope', '$rootScope', 'StatsFilterService', 'FormsList', 'MetricsList', 'Metrics', 'Referrals',
		function($scope, $rootScope, StatsFilterService, FormsList, MetricsList, Metrics, Referrals) {

			$scope.filterService = StatsFilterService;
			/*Forms.getForms(function(data) {
			 
			 
			 $scope.filterService.forms = data;
			 });*/
			$scope.filterService.forms = FormsList;
			$scope.stats = MetricsList;
			$scope.filterService.stat = $scope.stats[0];
			$scope.filterService.vsStat = $scope.stats[1];
			/*Metrics.getMetrics(function(data) {
			 $scope.stats = data;
			 $scope.filterService.stat = $scope.stats[0];
			 })*/

			var that = this;
			//When we change the filter, we need to update the chart
			
			$scope.removeVsMetric = function() {
				StatsFilterService.vsStat = null;
			};
			//Here we check if something change on the filter, then fire the update
			$scope.$watch(
				function() {
					return StatsFilterService;
				},
				function(newVal, oldVal) {
					//When the watch initialize, this function get called, altough the values dont change
					//with this comparison, we avoid the call to the function.
					if (newVal !== oldVal) {
						//console.log('Broadcast change on filter!',newVal,oldVal);
						$rootScope.$broadcast('filter:update', null);
					}
				},
				true);
			//Here we check if something change on the filter, then fire the update
			$scope.$watch(
				function() {
					return StatsFilterService.from + StatsFilterService.to;
				},
				function(newVal, oldVal) {
					//When the watch initialize, this function get called, altough the values dont change
					//with this comparison, we avoid the call to the function.
					//console.log('Broadcast change on filter!',newVal,oldVal);
					$rootScope.$broadcast('filterDateRange:update', null);
				},
				true);
			$scope.$watch(
				function() {
					return {from: StatsFilterService.from, to: StatsFilterService.to, forms: StatsFilterService.forms};
				},
				function(newVal, oldVal) {
					//When the watch initialize, this function get called, altough the values dont change
					//with this comparison, we avoid the call to the function.
					//console.log('Broadcast change on filter!',newVal,oldVal);
					$rootScope.$broadcast('filterDateRangeForms:update', null);
				},
				true);
			this.updateCharts = function() {
				//console.log('Updating Charts!');
				Metrics.getData($scope.filterService, function(data) {
					var cols = [{
							"id": "date",
							"label": "Date",
							"type": "string",
							"p": {}
						}, ];
					var vAxis = [];
					angular.forEach(data.cols, function(col, key) {
						cols.push(
							{
								"id": col.id + col.form + col.stat,
								"label": '#' + col.id + ' ' + col.form + ' ' + col.stat,
								"type": "number",
								"p": {}
							}
						);
						vAxis.push({
							"title": col.form,
							"gridlines": {
								"count": 10
							},
							format: '0 %',
							viewWindowMode: 'explicit',
							viewWindow: {
								min: 0,
								max: col.total + 10
							}
						});
					});
					var rows = [];
					angular.forEach(data.rows, function(values, key) {
						var rowArray = [{"v": key}];
						angular.forEach(values, function(value, key) {
							rowArray.push({"v": value});
						});
						rows.push({"c": rowArray});
					});
					/*console.log('cols', cols);
					 console.log('rows', rows);*/
					var chart = {
						"type": "LineChart",
						"displayed": false,
						"data": {
							"cols": cols,
							"rows": rows
						},
						"options": {
							//"title": 'Graph',
							"isStacked": "false",
							"fill": 20,
							"displayExactValues": true,
							focusTarget: 'category',
							/*chartArea: {
							 left: 45,
							 right:10,
							 top: 25,
							 bottom:95,
							 width: '100%',
							 height: 350
							 },*/
							//curveType: 'function',
							"vAxis": vAxis,
							"hAxis": {
								"title": "Date"
							},
							legend: {
								position: 'top',
								textStyle: {
									color: 'black',
									fontSize: 10}
							}
						}
					};
					$scope.selectedChart = chart;
				});
			};
			this.updateMiniCharts = function() {
				Metrics.getAllMetrics($scope.filterService, function(data) {
					//console.log(data);
					
					//construct the mini-charts
					var charts = [];
					angular.forEach(data.cols, function(col, key) {

						var cols = [
							{
								"id": "date",
								"label": "Date",
								"type": "string",
								"p": {}
							},
							{
								"id": col.form,
								"label": col.form,
								"type": "number",
								"p": {}
							}
						];
						var chart = {
							"type": "AreaChart",
							"displayed": false,
							"data": {
								"cols": cols,
								"rows": []
							},
							"options": {
								"isStacked": "false",
								"fill": 20,
								"displayExactValues": true,
								focusTarget: 'category',
								'tooltip': {
									trigger: 'none'
								},
								enableInteractivity: false,
								legend: {position: 'none'},
								//curveType: 'function',
								'width': 200,
								'height': 50,
								vAxis: {
									baselineColor: '#fff',
									gridlineColor: '#fff',
									textPosition: 'none'
								},
								hAxis: {
									baselineColor: '#fff',
									gridlineColor: '#fff',
									textPosition: 'none'
								},
							}
						};
						charts.push({chart: chart, title: col});
					});
					angular.forEach(data.rows, function(values, day) {
						var index = 0;
						angular.forEach(values, function(value, key) {
							var rowArray = [];
							rowArray.push({"v": day});
							rowArray.push({"v": value});
							charts[index].chart.data.rows.push({"c": rowArray});
							index++;
						});
					});
					$scope.charts = charts;
				});
			};
			this.updateReferrals = function() {
				Referrals.getData($scope.filterService, function(data) {
					//get total
					var total = 0;
					for (var i = 0; i < data.length; i++) {
						total += parseInt(data[i].count);
					}

					$scope.referrals = data;
					$scope.referralTotal = total;
				});
			};
			
			$rootScope.$on('filter:update', function(event, data) {
				that.updateCharts();
			});
			$rootScope.$on('filterDateRangeForms:update', function(event, data) {
				that.updateMiniCharts();
			});
			//When we change the filter, we need to update the chart
			$rootScope.$on('filterDateRange:update', function(event, data) {
				that.updateReferrals();
			});
		}]);
