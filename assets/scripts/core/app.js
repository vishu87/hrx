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

app.directive('autoCompleteTrigger', function() {
    return {
        restrict: 'A',
        link: function(scope, elem, attr, ctrl) {
            elem.autocomplete({
                source: base_url+'/api/triggers/get-triggers',
                position: {
                     my: "left top-3",
                },
                minLength: 3,
                select: function (event, ui) {
                    event.preventDefault();
                    
                    var label = ui.item.label;
                    var value = ui.item.value;
                    console.log(ui.item);
                    scope.setTrigger(value, label);
                },
                focus: function (event, ui) {
                    event.preventDefault();
                }
            });
        }
    };
});


app.directive('autoCompleteCompany', function() {
    return {
        restrict: 'A',
        link: function(scope, elem, attr, ctrl) {
            elem.autocomplete({
                source: base_url+'/api/companies/get-companies',
                position: {
                     my: "left top-3",
                },
                minLength: 3,
                select: function (event, ui) {
                    event.preventDefault();
                    
                    var label = ui.item.label;
                    var value = ui.item.value;
                    console.log(ui.item);
                    scope.setCompany(value, label);
                },
                focus: function (event, ui) {
                    event.preventDefault();
                }
            });
        }
    };
});
