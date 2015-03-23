var likeModule = angular.module('myLikes', []);
likeModule.factory('likesService', function($http) 
{
     var theLikes = 0;
     return {
        likes: theLikes,
        addLike: function(user_id, type_id, type, $scope)
        {
            var likeLayer = jQuery('.likelayer').css('background-image');
            if (likeLayer.indexOf('taken') > 0)
            {
                jQuery('.likelayer').css({ 'background-image' : 'url(images/thumbs-up.png)' }); 
            }
            else jQuery('.likelayer').css({ 'background-image' : 'url(images/thumbs-up-taken.png)' }); 
            //alert('horr');
            $http({method: 'POST', url: "index.php?option=com_projectfork&task=like", 
                data: { user_id: user_id, type_id: type_id, type: type } }).
                    success(function(data, status, headers, config) { //alert(data);
                    data = angular.fromJson(data);
                    if (data.error > 0) 
                    {
                        alert(data.msg);
                    }
                    else
                    {
                        if (data.msg != 'Already liked') {
                        $scope.likes = parseInt(theLikes) + 1;
                    }
                        else if (data.msg == 'Already liked')
                        {
                             theLikes = parseInt(theLikes) - 1;
                             $scope.likes = (theLikes < 1) ? 0 : theLikes;
                             theLikes = $scope.likes;
                              jQuery('.likelayer').css({ 'background-image' : 'url(images/thumbs-up.png)' });
                        }
                    }
                    }).error(function(data, status) {  data = angular.fromJson(data); alert(data.msg); });
        },
       urlLikes: function(type_id, type)
       {
           $http({method: 'POST', url: "index.php?option=com_projectfork&task=getlikes", 
                data: { type_id: type_id, type: type } }).
                    success(function(data, status, headers, config) { 
                          theLikes = data;
                         //  alert(theLikes);
                           
                     }).error(function(data, status) {   });
                     //alert(this.thelikes);
       },
       getLikes: function()
       {
          return theLikes;
       },
       getUserLike: function(type_id, type, user_id)
       {
           $http({method: 'POST', url: "index.php?option=com_projectfork&task=getUserLike", 
                data: { type_id: type_id, type: type, user_id: user_id } }).
                    success(function(data, status, headers, config) { //alert(data);
                        if (parseInt(data) === 1) { jQuery('.likelayer').css({ 'background-image' : 'url(images/thumbs-up-taken.png)' }); }
                           
                     }).error(function(data, status) {   });
       }   
   }
});
likeModule.controller('projectLike', function($scope, likesService, $timeout)
{
    //$scope.likes = likesService.tL;
    
    
    $scope.like = function(user_id, project_id) {  likesService.addLike(user_id, project_id, 1, $scope); }
    $scope.likes = likesService.likes;
    $scope.getLikes = function(project_id, user_id)
    {
       likesService.urlLikes(project_id, 1);
       likesService.getUserLike(project_id, 1, user_id);  
        $timeout(function() { $scope.likes = likesService.getLikes(); }, 1000);
    }
});

likeModule.controller('taskLike', function($scope, likesService, $timeout)
{
    $scope.like = function(user_id, project_id) 
    { 
         
       // jQuery('.likelayer').css({ 'background' : 'images/thumbs-up-taken.png' }); 
        likesService.addLike(user_id, project_id, 2, $scope); 
    }
    $scope.getLikes = function(project_id, user_id)
    {
       likesService.urlLikes(project_id, 2);
      // alert(user_id);//jQuery('.likelayer').css({ 'background-image' : 'url(images/thumbs-up-taken.png)' }); 
       likesService.getUserLike(project_id, 2, user_id);
        $timeout(function() 
        { $scope.likes = likesService.getLikes();  }, 1000);
    }
});