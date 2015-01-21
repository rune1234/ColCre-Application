var likeModule = angular.module('myLikes', []);
likeModule.factory('likesService', function($http) 
{
    var likeInputs = [{id: 0}];
     return {
        likeInputs: likeInputs,
        addLike: function(user_id, type_id, type)
        {
            $http({method: 'POST', url: "index.php?option=com_projectfork&task=like", 
                data: { user_id: user_id, type_id: type_id, type: type } }).
                    success(function(data, status, headers, config) { 
                    data = angular.fromJson(data);
                    if (data.error > 0) 
                    {
                        alert(data.msg);
                    }
                    }).error(function(data, status) {  data = angular.fromJson(data); alert(data.msg); });
        }
       
   }

});
likeModule.controller('projectLike', function($scope, likesService)
{
    $scope.like = function(user_id, project_id) { likesService.addLike(user_id, project_id, 1); }
});

likeModule.controller('taskLike', function($scope, likesService)
{
    $scope.like = function(user_id, project_id) { likesService.addLike(user_id, project_id, 2); }
});