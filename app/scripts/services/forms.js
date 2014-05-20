'use strict';

var services = angular.module('gfMarketingApp.services', ['ngResource']);

services.factory('StatsFilterService', [function() {
		return {
			forms: [],
			stat: null,
			vsStat: null,
			from: null,
			to: null,
			interval: 'D'
		};
	}]);

services.factory('Forms', ['$http', function($http) {
		return {
			getForms: function(callback) {

				$http({
					cache: true,
					method: 'POST',
					url: GFSeoMk.ajaxUrl,
					data: 'action=getForms' // pass in data as strings $.param(data);
				}).success(function(response) {
					return callback(response);
				});

			}
		};
	}]);

services.factory('FormsLoader', ['$http', '$q',
	function($http, $q) {
		return function() {
			var delay = $q.defer();
			$http({
				cache: true,
				method: 'POST',
				url: GFSeoMk.ajaxUrl,
				data: 'action=getForms' // pass in data as strings $.param(data);
			}).success(function(response) {
				delay.resolve(response);
			}).error(function() {
				delay.reject('Unable to fetch Forms');
			});
			return delay.promise;
		};
	}]);


/*services.factory('Stats', ['$http', function($http) {
 
 return {
 getStats: function(filter, callback) {
 $http({
 cache: true,
 method: 'POST',
 url: GFSeoMk.ajaxUrl,
 data: 'action=getFormStats&forms=' + filter.forms + '&stat=' + filter.stat + '&from=' + filter.from + '&to=' + filter.to + '&interval=' + filter.interval, // pass in data as strings $.param(data);
 }).success(function(data) {
 return callback(data);
 });
 }
 };
 }]);*/

services.factory('MetricsLoader', ['$http', '$q',
	function($http, $q) {
		return function() {
			var delay = $q.defer();
			$http({
				cache: true,
				method: 'POST',
				url: GFSeoMk.ajaxUrl,
				data: 'action=getMetricsDefinition' // pass in data as strings $.param(data);
			}).success(function(response) {
				delay.resolve(response);
			}).error(function() {
				delay.reject('Unable to fetch Metrics');
			});
			return delay.promise;
		};
	}]);

services.factory('Metrics', ['$http', function($http) {

		return {
			getData: function(filter, callback) {
				var data = {
					action: 'getMetric',
					stat: [filter.stat],
					from: filter.from,
					to: filter.to,
					interval: filter.interval,
					forms: []
				};
				if (filter.vsStat) {
					data.stat.push(filter.vsStat);
				}
				for (var index = 0; index < filter.forms.length; ++index) {
					if (filter.forms[index].selected) {
						data.forms.push(filter.forms[index].id);
					}
				}

				$http({
					cache: true,
					method: 'POST',
					url: GFSeoMk.ajaxUrl,
					data: jQuery.param(data), // pass in data as strings $.param(data);
				}).success(function(data) {
					return callback(data);
				});
			},
			getMetrics: function(callback) {

				$http({
					cache: true,
					method: 'POST',
					url: GFSeoMk.ajaxUrl,
					data: 'action=getMetricsDefinition' // pass in data as strings $.param(data);
				}).success(function(response) {
					return callback(response);
				});

			},
			getAllMetrics: function(filter, callback) {
				var data = {
					action: 'getAllMetrics',
					from: filter.from,
					to: filter.to,
					interval: filter.interval,
					forms: []
				};
				for (var index = 0; index < filter.forms.length; ++index) {
					if (filter.forms[index].selected) {
						data.forms.push(filter.forms[index].id);
					}
				}

				$http({
					cache: true,
					method: 'POST',
					url: GFSeoMk.ajaxUrl,
					data: jQuery.param(data), // pass in data as strings $.param(data);
				}).success(function(data) {
					return callback(data);
				});
			}
		};
	}]);

services.factory('Referrals', ['$http', function($http) {

		return {
			getData: function(filter, callback) {
				var data = {
					action: 'getReferrals',
					from: filter.from,
					to: filter.to
				};

				$http({
					cache: true,
					method: 'POST',
					url: GFSeoMk.ajaxUrl,
					data: jQuery.param(data), // pass in data as strings $.param(data);
				}).success(function(data) {
					return callback(data);
				});
			}
		};
	}]);
