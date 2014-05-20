angular.module('gfMarketingApp').directive("dynamicName",['$compile',function($compile){
  return {
      restrict:"A",
      terminal:true,
      priority:1000,
      link:function(scope,element,attrs){
		  
		  console.log('dentro1111');
          element.attr('name', scope.$eval(attrs.dynamicName));
          element.removeAttr("dynamic-name");
          $compile(element)(scope);
      }
   };
}]);


