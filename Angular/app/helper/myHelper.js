function apiModifyTable(originalData,id,response){
    angular.forEach(originalData, function (course,key) {
        if(course.id == id){
            originalData[key] = response;
        }
    });
    return originalData;
}