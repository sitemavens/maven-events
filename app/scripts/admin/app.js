
angular.module('mavenEventsApp', [
	'ngCookies',
	'ngResource',
	'ngSanitize',
	'ngRoute',
	'ui.bootstrap',
	'googlechart'
]).config(['$routeProvider', '$httpProvider', function($routeProvider, $httpProvider) {

		$httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';


//		$routeProvider
//			.when('/', {
//				templateUrl: GFSeoMk.viewsUrl + 'main.php',
//				controller: 'MainCtrl',
//				resolve: {
//					FormsList: ['FormsLoader',function(FormsLoader) {
//						return FormsLoader();
//					}],
//					MetricsList:['MetricsLoader', function (MetricsLoader){
//						return MetricsLoader();
//					}]
//				}
//			})
//			.when('/settings', {
//				templateUrl: GFSeoMk.viewsUrl + 'settings.php',
//				controller: 'SettingsCtrl'
//			})
//			.otherwise({
//				redirectTo: '/'
//			});
	}]);

angular.module('mavenEventsListApp', [
	'ngCookies',
	'ngResource',
	'ngSanitize',
	'ngRoute',
	'ui.bootstrap',
	'googlechart',
	'mavenEventsApp.services'
]).config(['$routeProvider', '$httpProvider', function($routeProvider, $httpProvider) {

		$httpProvider.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';

	}]);
