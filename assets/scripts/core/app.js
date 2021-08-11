var app = angular.module('app', [
	'jcs-autoValidate',
  'datatables',
	// 'angular-ladda',
  'ngSanitize',
  'selectize',
  'ngFileUpload',
]);

angular.module('jcs-autoValidate')
    .run([
    'defaultErrorMessageResolver',
    function (defaultErrorMessageResolver) {
        defaultErrorMessageResolver.getErrorMessages().then(function (errorMessages) {
          errorMessages['patternInt'] = 'Please fill a numeric value';
          errorMessages['patternFloat'] = 'Please fill a numeric/decimal value';
        });
    }
]);

app.directive('convertToNumber', function() {
  return {
    require: 'ngModel',
    link: function(scope, element, attrs, ngModel) {
      ngModel.$parsers.push(function(val) {
        return val != null ? parseInt(val, 10) : null;
      });
      ngModel.$formatters.push(function(val) {
        return val != null ? '' + val : null;
      });
    }
  };
});


app.directive('tablePaginate', ['$compile', function ($compile) {
    return {
      restrict: 'EA',
      template: '<div class="row">\
        <div class="col-md-6" ng-if="filter.max_page > 0">\
        <div class="total-count">Showing <span>{{filter.max_per_page*(filter.page_no-1) + 1}} - {{filter.max_per_page*filter.page_no < total ? filter.max_per_page*filter.page_no : total}}</span> of <span>{{total}}</span></div>\
        </div>\
        <div class="col-md-6" style="text-align: right;">\
          <ul class="pagination" ng-if="filter.max_page > 1">\
            <li class="page-item">\
              <a class="page-link" href="javascript:;" ng-click="setPage(1)"> << </a>\
            </li>\
            <li class="page-item">\
              <a class="page-link" href="javascript:;" ng-click="setPage(filter.page_no - 1)"> < </a>\
            </li>\
            <li class="page-item" ng-repeat="page in pages">\
              <a class="page-link" href="javascript:;" ng-click="setPage(page)" ng-class="page == filter.page_no ? \'active\' : \'\' ">{{page}}</a>\
            </li>\
            <li class="page-item">\
              <a class="page-link" href="javascript:;" ng-click="setPage(filter.page_no + 1)"> > </a>\
            </li>\
            <li class="page-item">\
              <a class="page-link" href="javascript:;" ng-click="setPage(filter.max_page)"> >> </a>\
            </li>\
          </ul>\
      </div>\
      </div>',
      link: function (scope, element, attrs) {
          
          scope.setPagination = function(){
            var pages = [];
            if(scope.filter.page_no == 1){
                pages.push(1);    
                pages.push(2);    
                if(scope.filter.max_page > 2) pages.push(3);    
            } else {
                if(scope.filter.max_page == scope.filter.page_no && scope.filter.max_page > 2){
                    pages.push(scope.filter.page_no - 2);
                }
                pages.push(scope.filter.page_no - 1);    
                pages.push(scope.filter.page_no);
                if(scope.filter.max_page != scope.filter.page_no){
                    pages.push(scope.filter.page_no + 1);
                }
            }
            scope.pages = pages;
          }

          scope.setPagination();

          scope.setPage = function(page_number){
            if(page_number < 1 || page_number > scope.filter.max_page || page_number == scope.page_number){
              return;
            }
            scope.filter.page_no = page_number;
            scope.setPagination();
            scope.getList();
          }
      }
    }
}]);

app.directive('thSort', ['$compile', function ($compile) {
    return {
      restrict: 'EA',
      template: function(element,attrs){
        console.log(attrs);
        return '<span style="display:block; cursor:pointer" ng-click="setOrder(\''+attrs.columnId+'\')">' + attrs.columnName + ' <span ng-if="filter.order_by == \''+ attrs.columnId +'\'"><i class="fa fa-angle-up" ng-if="filter.order_type == \'ASC\'"></i><i class="fa fa-angle-down" ng-if="filter.order_type == \'DESC\'"></i></span></span>'
      },
      link: function (scope, element, attrs) {
        scope.setOrder = function(column){
          if(column == scope.filter.order_by){
            scope.filter.order_type = (scope.filter.order_type == 'ASC') ? 'DESC' : 'ASC';
          } else {
            scope.filter.order_by = column;
            scope.filter.order_type = 'ASC';
          }
          scope.filter.page_no = 1;
          scope.getList();
        }
      }
    }
}]);