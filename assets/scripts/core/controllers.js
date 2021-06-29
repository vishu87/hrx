
app.controller('adminUserCtrl', function($scope , $http, $timeout , DBService){
    $scope.processing = false;
    $scope.users = [];

    $scope.adminUserInit = function(){
        $scope.processing = true;
        
        DBService.getCall('/api/admin/users').then(function(data){
            if(data.success){
                $scope.users = data.users;
            }
            $scope.processing = false;
        });
    };

    $scope.editClient = function(client){
        client.processing = true;
        $scope.open_client = client;
        $("#editClient").modal("show");
        client.processing =false;
    }

    // $scope.updateClient = function(){
    //     $scope.processing = true;
    //     DBService.postCall($scope.open_client,'/api/ses/updateClient').then(function(data){
    //         if(data.success){
    //             for (var i = $scope.users.length - 1; i >= 0; i--) {
    //                 if($scope.users[i]['id'] = data.client.id){
    //                     $scope.users[i] = data.client;

    //                 }
    //             }
    //             $("#editClient").modal("hide");
    //         }
    //         alert(data.message);
    //         $scope.processing = false;
    //     });
    // }
    $scope.deleteUser = function(user,index){
        $scope.user_id = user.id;
        console.log($scope.user_id);
        bootbox.confirm('Are you sure to delete it?',function(result){
          if(result){
            DBService.getCall('/api/admin/delete/'+ $scope.user_id).then(function(data){
              if(data.success){
                bootbox.alert(data.message);
                $scope.users.splice(index,1); 
              }
              else{
                  bootbox.alert(data.message);
              }
             
            });
          }
          
        });
    }

});

app.controller('JopOffersCtrl', function($scope , $http, $timeout , DBService){
    $scope.processing = false;
    $scope.offer_id = '';
    $scope.offers = [];
    $scope.companies = [];
    $scope.formData = {company_id:""};

    $scope.offersInit = function(){
        $scope.processing = true;
        DBService.postCall({offer_id:$scope.offer_id},'/api/company/job-offers/init')
        .then(function(data){
            if(data.success){
                $scope.companies = data.companies;
                if (data.offer) {
                    $scope.formData = data.offer;
                    $scope.formData.company_id = String($scope.formData.company_id);
                }
            }
            $scope.processing = false;
        });
    };

    $scope.onSubmit = function(){
        $scope.processing = true;
        DBService.postCall($scope.formData,'/api/company/job-offers/save').then(function(data){
            if(data.success){
                bootbox.alert(data.message,function(){
                    window.location = base_url+'/company/job-offers';
                });
            }else{
                bootbox.alert(data.message);
            }
            $scope.processing = false;
            
        });
    };

});

app.controller('companyCtrl', function($scope , $http, $timeout , DBService){
    $scope.processing = false;
    $scope.company_id = 0;
    $scope.products = [];
    $scope.subscriptions =[];
    $scope.users=[];
    $scope.formData = {
        morePersons:[],
    };
    $scope.person = {name:'',email:'',phone_no:''};
    $scope.companies =[];

    $scope.companyInit = function(){
        $scope.processing = true;
        DBService.postCall({company_id:$scope.company_id},'/api/admin/companies/init').then(function(data){
            if(data.success){
                $scope.products = data.products;
                $scope.subscriptions = data.subscriptions;
                
                if(data.company){
                    $scope.formData = data.company;
                    $scope.formData.morePersons = data.morePersons;
                }
            }
            $scope.processing = false;
        });
    };

    $scope.listing = function(){
        $scope.processing = true;
        DBService.getCall('/api/admin/companies').then(function(data){
            if(data.success){
                $scope.companies = data.companies;
            }
            $scope.processing = false;
        });
    };
    $scope.clientInit = function(){
        $scope.processing = true;
        
        DBService.getCall('/api/company/users').then(function(data){
            if(data.success){
                $scope.users = data.users;
            }
            $scope.processing = false;
        });
    };

    $scope.onSubmit = function(){
        $scope.processing = true;
        DBService.postCall($scope.formData,'/api/admin/companies/save').then(function(data){
            if(data.success){
                bootbox.alert(data.message,function(){
                    window.location = base_url+'/admin/companies';
                });
            }else{
                bootbox.alert(data.message);
            }
            $scope.processing = false;
            
        });
    };

    $scope.onSubmitByCompany = function(){
        $scope.processing = true;
        DBService.postCall($scope.formData,'/api/company/save').then(function(data){
            if(data.success){
                bootbox.alert(data.message,function(){
                    window.location = base_url+'/company/users';
                });
            }else{
                bootbox.alert(data.message);
            }
            $scope.processing = false;
            
        });
    };

    $scope.addMorePersons = function(){
        if (!$scope.formData.morePersons) {
            $scope.formData.morePersons = [];
        }
        $scope.formData.morePersons.push(JSON.parse(JSON.stringify($scope.person)));
    }

    $scope.spliceMorePersons = function(index){ 
        $scope.formData.morePersons.splice(index,1);
    }
    $scope.deleteUser = function(user,index){
        $scope.user_id = user.id;
        console.log($scope.user_id);
        bootbox.confirm('Are you sure to delete it?',function(result){
          if(result){
            DBService.getCall('/api/company/delete/'+ $scope.user_id).then(function(data){
              if(data.success){
                bootbox.alert(data.message);
                $scope.users.splice(index,1); 
              }
              else{
                  bootbox.alert(data.message);
              }
             
            });
          }
          
        });
    }
    $scope.deleteCompany = function(company,index){
        $scope.company_id = company.id;
        console.log($scope.company_id);
        bootbox.confirm('Are you sure to delete it?',function(result){
          if(result){
            DBService.getCall('/api/admin/companies/delete/'+ $scope.company_id).then(function(data){
              if(data.success){
                bootbox.alert(data.message);
                $scope.companies.splice(index,1); 
              }
              else{
                  bootbox.alert(data.message);
              }
             
            });
          }
          
        });
    }

});



app.controller('AutoCompCtrl', function($scope , $http, $timeout , DBService){
    
    $scope.filter = {
        com_id: '',
        com_name: '',
        trigger_id :'',
        trigger_name :'',
    }

    $scope.setTrigger = function(value, label){
        $scope.filter.trigger_name = label;
        $scope.filter.trigger_id = value;
        $scope.$apply();
    }

    $scope.setCompany = function(value, label){
        $scope.filter.com_name = label;
        $scope.filter.com_id = value;
        $scope.$apply();
    }

    $scope.removeTrigger = function(){
        $scope.filter.trigger_id = "";
        $scope.filter.trigger_name = "";
    }

    $scope.removeCompany = function(){
        $scope.filter.com_id = "";
        $scope.filter.com_name = "";
    }

    $scope.getTriggerList = function(){
        DBService.getCall('/api/triggers/get-triggers-list')
        .then(function(data){
            $scope.triggers = data.triggers;
            $scope.categories = data.categories;
        });
    }

    $scope.idSelector = function(cat_id, tr_id, tr_name, com_id,com_name){
        $scope.getTriggerList();
        if (cat_id) {
            $scope.filter.category_id = cat_id;
        }
        if (tr_id) {
            $scope.filter.trigger_id = tr_id;
        }
        if (tr_name) {
            $scope.filter.trigger_name = tr_name;
        }
        if (com_id) {
            $scope.filter.com_id = com_id;
        }
        if (com_name) {
            $scope.filter.com_name = com_name;
        }
    }
});