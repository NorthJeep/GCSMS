var app = angular.module('app', ['angularUtils.directives.dirPagination']);
app.controller('memberdata',function($scope, $http, $window){
	$scope.AddModal = false;
    $scope.EditModal = false;
    $scope.DeleteModal = false;

    $scope.errorFirstname = false;

    $scope.showAdd = function(){
        $scope.stud_no=null;
    	$scope.stud_firstname = null;
    	$scope.stud_lastname = null;
    	$scope.address = null;
    	$scope.errorFirstname = false;
    	$scope.errorLastname = false;
    	$scope.errorAddress = false;
    	$scope.AddModal = true;
    }
  
    $scope.fetch = function(){
    	$http.get("fetch.php").success(function(data){ 
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
            "add.php", {
                'stud_no': $scope.stud_no,
                'stud_firstname': $scope.stud_firstname,
                'stud_lastname': $scope.stud_lastname,
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
    	$http.post("edit.php", $scope.clickMember)
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
    	$http.post("delete.php", $scope.clickMember)
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