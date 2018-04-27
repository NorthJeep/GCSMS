var app = angular.module('app', ['angularUtils.directives.dirPagination']);
app.controller('memberdata',function($scope, $http, $window){
	$scope.AddModal = false;
    $scope.EditModal = false;
    $scope.DeleteModal = false;

    $scope.errorFirstname = false;

    $scope.showAdd = function(){
    	$scope.firstname = null;
    	$scope.lastname = null;
    	$scope.address = null;
    	$scope.errorFirstname = false;
    	$scope.errorLastname = false;
    	$scope.errorAddress = false;
    	$scope.AddModal = true;
    }
  
    $scope.fetch = function(){
    	$http.get("profile_fetch.php").success(function(data){ 
	        $scope.members = data; 
	    });
    }

    $scope.sort = function(keyname){
        $scope.sortKey = keyname;   
        $scope.reverse = !$scope.reverse;
    }

    $scope.clearMessage = function(){
    	$scope.success = false;
    	$scope.error = false;
    }

    $scope.addnew = function(){
    	$http.post(
            "profile_add.php", {
                'firstname': $scope.firstname,
                'lastname': $scope.lastname,
                'address':$scope.address,
            }
        ).success(function(data) {
        	if(data.firstname){
        		$scope.errorFirstname = true;
        		$scope.errorLastname = false;
        		$scope.errorAddress = false;
        		$scope.errorMessage = data.message;
        		$window.document.getElementById('firstname').focus();
        	}
        	else if(data.lastname){
        		$scope.errorFirstname = false;
        		$scope.errorLastname = true;
        		$scope.errorAddress = false;
        		$scope.errorMessage = data.message;
        		$window.document.getElementById('lastname').focus();
        	}
        	else if(data.address){
        		$scope.errorFirstname = false;
        		$scope.errorLastname = false;
        		$scope.errorAddress = true;
        		$scope.errorMessage = data.message;
        		$window.document.getElementById('address').focus();
        	}
        	else if(data.error){
        		$scope.errorFirstname = false;
        		$scope.errorLastname = false;
        		$scope.errorAddress = false;
        		$scope.error = true;
        		$scope.errorMessage = data.message;
        	}
        	else{
        		$scope.AddModal = false;
        		$scope.success = true;
        		$scope.successMessage = data.message;
        		$scope.fetch();
        	}
        });
    }

    $scope.selectMember = function(member){
    	$scope.clickMember = member;
    }

    $scope.showEdit = function(){
    	$scope.EditModal = true;
    }

    $scope.updateMember = function(){
    	$http.post("profile_edit.php", $scope.clickMember)
    	.success(function(data) {
        	if(data.error){
        		$scope.error = true;
        		$scope.errorMessage = data.message;
        		$scope.fetch();
        	}
        	else{
        		$scope.success = true;
        		$scope.successMessage = data.message;
        	}
        });
    }

    $scope.showDelete = function(){
    	$scope.DeleteModal = true;
    }

    $scope.deleteMember = function(){
    	$http.post("profile_delete.php", $scope.clickMember)
    	.success(function(data) {
        	if(data.error){
        		$scope.error = true;
        		$scope.errorMessage = data.message;
        	}
        	else{
        		$scope.success = true;
        		$scope.successMessage = data.message;
        		$scope.fetch();
        	}
        });
    }

});