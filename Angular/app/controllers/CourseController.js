app.controller('AdminController', function($scope,$http){
  $scope.pools = []; 
});

app.controller('CourseController', function(dataFactory,$scope,$http){
 
  $scope.data = [];
  $scope.totalItems = 0;
  $scope.libraryTemp = {};
  $scope.totalItemsTemp = {};
  
  $scope.users_data = [];
  $scope.users_data_Edit = [];
  $scope.users_pageNumber_new = 1;
  $scope.users_pageNumber_edit = 1;
  
  /*
   * Handle user pagination while in create course form
   */
  $scope.user_pageChanged_create = function(newPage) {
    getUsersCreate(newPage);
  };
  
  /*
   * Handle user pagination while in edit course form
   */
  $scope.user_pageChanged_edit = function(newPage, course_id) {
    getUsersEdit(newPage, course_id);
  };

  getCourses();
  getUsersCreate(1);
  
  function getCourses() {
      if(! $.isEmptyObject($scope.libraryTemp)){
          dataFactory.httpRequest('courses?search='+$scope.searchText).then(function(data) {
            $scope.data = data.data;
          });
      }else{
        dataFactory.httpRequest('courses').then(function(data) {
          $scope.data = data.data;
        });
      }
  }

  function getUsersCreate(pageNumber) {
	  dataFactory.httpRequest('users?search='+$scope.searchText+'&page='+pageNumber).then(function(users_data) {
		$scope.users_data = users_data.users_data;
		$scope.totalItems = users_data.users_total;
		$scope.users_pageNumber_new = pageNumber;
	  });
  }

  function getUsersEdit(pageNumber, courseId) {
		dataFactory.httpRequest('users_GetAll?search='+$scope.searchText+'&page='+pageNumber+'&courseId='+courseId).then(function(users_data) {
		$scope.users_data_Edit = users_data.users_data_Edit;
		$scope.users_pageNumber_edit = pageNumber;
	  });
  }

  function getUsersAttached(courseId) {
	dataFactory.httpRequest('users_GetAllAttached?search='+$scope.searchText+'&courseId='+courseId).then(function(users_Data_Attached) {
		localStorage.clear();
		for (i = 0; i < users_Data_Attached.users_data_Attached.length; i++) { 
			var user_id = users_Data_Attached.users_data_Attached[i].user_id;
			localStorage.setItem(user_id, user_id);
		}
	});
  }

  $scope.filterCourses = function(){
      if($scope.searchText.length >= 3){
          if($.isEmptyObject($scope.libraryTemp)){
              $scope.libraryTemp = $scope.data;
              $scope.totalItemsTemp = $scope.totalItems;
              $scope.data = {};
          }
          getCourses(1);
      }else{
          if(! $.isEmptyObject($scope.libraryTemp)){
              $scope.data = $scope.libraryTemp ;
              $scope.totalItems = $scope.totalItemsTemp;
              $scope.libraryTemp = {};
          }
      }
  }
  
  $scope.filterOnUserAge = function(){
		  $scope.libraryTemp = $scope.data;
		  $scope.totalItemsTemp = $scope.totalItems;
		  $scope.data = {};
          //getResultsPage(1);
  }
  
  $scope.saveAddCourse = function(){
	$scope.form.Users = localStorage;
	debugger;
    dataFactory.httpRequest('coursesCreate','POST',{},$scope.form).then(function(data) {
      
	  $scope.data.push(data);
      $(".modal").modal("hide");
	  localStorage.clear();
    });
  }

  /**
	* Edit course
	*/
  $scope.edit = function(id){
    dataFactory.httpRequest('coursesEdit/'+id).then(function(data) {
		
		getUsersAttached(id);
		getUsersEdit(1, id);

      	$scope.form = data;
    });
  }

  $scope.saveEdit = function(){
    $scope.form.Users = localStorage;
	dataFactory.httpRequest('coursesUpdate/'+$scope.form.id,'PUT',{},$scope.form).then(function(data) {
      	$(".modal").modal("hide");
		localStorage.clear();
        $scope.data = apiModifyTable($scope.data,data.id,data);
    });
  }

  $scope.remove = function(course,index){
    var result = confirm("Are you sure delete this course?");
   	if (result) {
      dataFactory.httpRequest('coursesDelete/'+course.id,'DELETE').then(function(data) {
          $scope.data.splice(index,1);
      });
    }
  }
  
    /**
	* Save/add users to local storage
	*/
   $scope.add_Remove_User = function(user_id,index){
	   debugger;
		if(! $.isEmptyObject(localStorage.getItem(user_id))){
			localStorage.removeItem(user_id);
			$("#user_row_"+user_id).addClass("btn-success");
			$("#user_row_"+user_id).removeClass("btn-warning");
			$("#user_row_"+user_id).html("Attach User");
		
		}
		else
		{
			localStorage.setItem(user_id, user_id);
			$("#user_row_"+user_id).removeClass("btn-success");
			$("#user_row_"+user_id).addClass("btn-warning");
			$("#user_row_"+user_id).html("Deattach User");
		}
	}
	
	$scope.editUser = function(user_id){
		alert("not implemented yet");
	}
	
	$scope.removeUser = function(course,index){
		alert("not implemented yet");
	}
});