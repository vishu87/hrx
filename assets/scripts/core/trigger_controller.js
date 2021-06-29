app.controller('MFTriggerCtrl', function($scope,$http,DBService){
 
	$scope.edit_mode = false;
	$scope.categories = [];
	$scope.parent_id = 0;
	$scope.triggers = [];
	$scope.alert = {
		success:{
			status:false,
			message:"",
		},
		failure:{
			status:false,
			message:"",
		}
	};
	$scope.processing = true;


	$scope.changeEditMode = function(){
		$scope.edit_mode = !$scope.edit_mode;
		if($scope.edit_mode){
			$scope.parent_id = 0;
			$scope.triggers = [];
		} else {
			$scope.processing = true;
			$scope.triggerList();	
		}
	}

	$scope.parentCategoryInit = function(){
		DBService.getCall('/api/mf/triggers/categories/parent-list')
		.then(function(data){
			if (data.success) {
				$scope.categories = data.categories;
			}
		});
		$scope.triggerList();
	};

	$scope.triggerList = function(){
		DBService.getCall('/api/mf/triggers/list')
		.then(function(data){
			if (data.success) {
				$scope.triggers = data.triggers;
				$scope.processing = false;
			}
		});
	}

	$scope.getChildCategories = function(){
		DBService.postCall({id:$scope.parent_id},'/api/mf/triggers/categories/childs')
			.then(function(data){
			if (data.success) {
				$scope.triggers = data.triggers;
			}
		});
	};

	$scope.saveUserTrigegr = function(){
		DBService.postCall({
			parent_category_id: $scope.parent_id,
			triggers: $scope.triggers,
		},'/api/mf/triggers/save-user')
		.then(function(data){
			console.log(data);
			alert(data.message);
		})
	};

});

app.controller('SESTriggerCtrl', function($scope,$http,DBService){

	$scope.categories = {};
    $scope.formData = {
    };
    
    $scope.getCategories = function(){
        DBService.getCall('/api/ses/trigger/categories/list')
        .then(function(data){
            if (data.success) {
                $scope.categories = data.categories;
            }
        });
    }

    $scope.edit = function(id){
        $scope.getCategories();
        DBService.postCall({id:id}, '/api/ses/trigger/get-info')
        .then(function(data){
            console.log(data);
            if (data.success) {
                $scope.formData = data.trigger;
            }
        });
    }
});