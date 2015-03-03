function dump(arr,level) {
	var dumped_text = "";
	if(!level) level = 0;
	
	//The padding given at the beginning of the line.
	var level_padding = "";
	for(var j=0;j<level+1;j++) level_padding += "    ";
	
	if(typeof(arr) == 'object') { //Array/Hashes/Objects 
		for(var item in arr) {
			var value = arr[item];
			
			if(typeof(value) == 'object') { //If it is an array,
				dumped_text += level_padding + "'" + item + "' ...\n";
				dumped_text += dump(value,level+1);
			} else {
				dumped_text += level_padding + "'" + item + "' => \"" + value + "\"\n";
			}
		}
	} else { //Stings/Chars/Numbers etc.
		dumped_text = "===>"+arr+"<===("+typeof(arr)+")";
	}
	return dumped_text;
}
function selectCatg($catg)
{
           jQuery('#addskillbox').slideDown();
           var $userid = jQuery('input[name=userid]').val();
          
           
           if (parseInt($userid) == 0)
           {
               alert("You need to log in"); 
               return;
           }
           else
           {
                angular.element(jQuery('#addusersk')).scope().setUserID($userid); 
                angular.element(jQuery('#addusersk')).scope().setCatg($catg); 
           }
           angular.element(jQuery('#addusersk')).scope().showDelete(0); 
           jQuery('html, body').animate({ scrollTop:  jQuery('#addskillbox').offset().top - 50 }, 'slow');
           if (1 ==1 || $catg != jQuery("input[name=skillcatg]").val()) 
           { 
                
               jQuery("input[name=skillcatg]").val( $catg );
               
               $fragment_refresh = {
		url: tasksURL,
		type: 'POST',
		data: { option: 'com_pfprojects', task: 'getUserSkilAj', 'catg' : $catg },
		success: function( data ) {  
                   
                     data = JSON.parse(data);
                     jQuery("#addusersk").data('addskill', data);
                     angular.element(jQuery('#addusersk')).scope().changeTags($catg); 
                    
                      
                } };
                jQuery.ajax( $fragment_refresh );
                //*********************************************************
                $fragment_refresh = {
		url: tasksURL,
		type: 'POST',
		data: { option: 'com_pfprojects', task: 'getUserMainSkilAj', 'catg' : $catg },
		success: function( data ) { //alert(data);
                    
                     data = JSON.parse(data);
                     if(data.skillDesc) 
                         
                    {
                         
                        angular.element(jQuery('#addusersk')).scope().showDelete(1); 
                        angular.element(jQuery('#addusersk')).scope().Skillset = data.id; 
                        
                       
                    }
                     
                     jQuery("input[name=skill2dd]").val(data.skill);
                     jQuery("#skilldesc").val(data.skillDesc);
                     
                } };
                  jQuery.ajax( $fragment_refresh );
               
           }
           /*else if($catg)
           {
               angular.element(jQuery('#addusersk')).scope().showDelete(1); 
                angular.element(jQuery('#addusersk')).scope().Skillset = data.id; 
           }*/
               
           
           jQuery("input[name=skillcatg]").val(  $catg ); 
           if (jQuery('.newSkillTagCag').length == 1) 
           {
               jQuery('.newSkillTagCag option').each(function()
               {
                  // alert(jQuery("input[name=skillcatg]").val());
                  //alert(jQuery(this).attr('name'));
                   if (jQuery("input[name=skillcatg]").val() == jQuery(this).attr('name')) 
                   { 
                       jQuery('.newSkillTagCag').val( jQuery(this).val() );
                       
                   }
               });
           }
           jQuery('.newSkillTagCag_2').val($catg);
          /* jQuery('#categoryPan').html( $catTitle ).css({'color' : '#000'});*/
}
function addSkillLayer($this)
{
    var centerHeight = (( jQuery($this).outerHeight()) /1);
             var centerWidth = '20%';//Math.max(0, (( jQuery(window).width() - jQuery(this).outerWidth()) / 2));
             var catg = jQuery($this).data('catg');
             if (isNaN(catg)) { alert('ERROR - category ID is not recognized'); return; }
             jQuery("input[name=skillcatg]").val(catg);
             var ff = jQuery('#addskillbox');
             ff.fadeIn().css({'top': centerHeight, 'left': centerWidth });
             title = jQuery($this).children('div.catgtitle').html();
             jQuery('#addskillbox > h1').html("Add Category for "  + title);
             ff.prepend('<a class="close2"><img src="images/close-bl.png" class="btn_close" title="Close Window" alt="Close" /></a>');; 
             jQuery('body').append('<div id="fade"></div>'); //Add the fade layer to bottom of the body tag.
             jQuery('#fade').fadeIn();
             jQuery('.close2').click(function(){ removeLayer(); });
}
function addUserSkill()
{
    var skilltoAdd = jQuery("input[name=skill2dd]").val();
    var skillDesc = jQuery("textarea[name=skilldesc]").val();
    var userid = jQuery("input[name=userid]").val();
    var editInstead = jQuery("input[name=editInstead]").val();
    //var skillTags = jQuery("textarea[name=skilltags]").val();
    var taskNames = [];
    var taskIds = [];
   
    var $t = 0;
    var newTagsCatg = [];
    jQuery(".newSkillTagCag").each(function()
    {
        
        var nTaCg = $("option:selected", this).attr('name');
        if(typeof(nTaCg) !== 'undefined' && nTaCg != '') newTagsCatg.push(nTaCg);
    });
    var newTags = [];
    jQuery(".newSkillTag").each(function() //male sure t is here, since there will always be a category, but not necessarily a tag
    {
       
       var nTa = $(this).val();
       if(typeof(nTa) !== 'undefined' && nTa != '') { newTags.push(nTa); $t++; }
       
    });
    var JNewTags = '';
    var JNewTagsCag = '';
    if ($t > 0)
    {
         JNewTags = JSON.stringify(newTags);
         JNewTagsCag = JSON.stringify(newTagsCatg);
    }
    
    
   
    
    
    jQuery(".taskNfNam").each(function() { var aValue = $(this).val();   taskNames.push(aValue); });
    jQuery(".taskfID").each(function() { var aValue = $(this).val();   taskIds.push(aValue); });
    var taskIds = JSON.stringify(taskIds);
    var skillTags = taskNames.join();
   
     
    var skillCatg = jQuery("input[name=skillcatg]").val();
    
    
    if (isNaN(skillCatg) || skillCatg == 0) { /* jQuery('#categoryPan').css({'color' : '#a00'}); */ jQuery('#skillboxWarn').fadeIn().html('ERROR - category ID is not valid'); return false; }
     
    if (isNaN(userid)) { jQuery('#skillboxWarn').fadeIn().html('ERROR - user id is not valid. You may need to log in'); return false; }
    if (skilltoAdd.trim() == '') { jQuery('#skillboxWarn').fadeIn().html('Your skill must have a name'); return false; }
    if (skillTags.trim() == '') { jQuery('#skillboxWarn').fadeIn().html('Add skill tags'); return false; }
    if (skillDesc.trim() == '') { jQuery('#skillboxWarn').fadeIn().html('Add a skill description'); return false; }
    if (jQuery('#skillboxWarn').is(":visible")) { jQuery('#skillboxWarn').fadeOut().html(''); }
    $fragment_refresh = {
		url: tasksURL,
		type: 'POST',
		data: { editInstead: editInstead, JNewTagsCag: JNewTagsCag, JNewTags: JNewTags, option: 'com_pfprojects', taskIds: taskIds, task:'addUserKill', userid:userid, skilltoAdd: skilltoAdd, skillDesc: skillDesc, skillTags: skillTags, skillCatg: skillCatg},
		success: function( data ) {  
                     data = JSON.parse(data);
                     if (!data.status)
                     {
                         alert(data.error);
                         
                     }
                     else
                     {
                         jQuery('#noskillsadded').remove();
                         if (! data.edited)
                         {
                             jQuery('#skilalraded').append("<li id='skillnk_" + data.id + "'><a onClick='selectCatg(" + skillCatg +")' href='javascript:void(0)'>" + skilltoAdd  + "</a></li>") ;
                         }
                         jQuery('#addskillbox').fadeOut(function() { jQuery('#fade, a.close2').remove(); } );
                     }
                } };
    jQuery.ajax( $fragment_refresh );
     
    return false;
}



//******************************************************************
var projectModule = angular.module('myProj', []);
projectModule.factory('theMenus', function() { return });
projectModule.factory('skillService', function($http) 
{
    var $tasks = [];
    var skillInputs = [{id: 0}];
    var $chosenSkill = [];
    return {
        skillInputs: skillInputs,
        addSkillInputs: function()
        {
            var k = {};
            k.id = skillInputs.length;
            skillInputs.push(k);
            
        },
        skillHandler:{
           xArray: 0,
           chosenSkill: $chosenSkill,
          SkillInput: function()
           {
               
           },
           
       },
       addTag: function()
       {}
       
   }

});
projectModule.factory('projInvite', function($http, $sce) 
{
   var projectList1 = [];
   
   return {
	   projectlist: projectList1,
	   projectSearch: function()
           {    var skinput = 'bla';
			  
             $http({method: 'POST', url: "index.php?option=com_community&view=profile&task=myProjects", 
                data: { skill: skinput}}).
                    success(function(data, status, headers, config) {  
                        if (data.msg == '')
                        {   
							 
                            var  projects = JSON.parse(data.projects);
                            for (id in projects)
                            {
                                var oneSKill = {};
                                //alert(projects[id].title);
                                if (typeof projects[id].title === 'undefined') continue;
                                oneSKill.title = projects[id].title;
                                oneSKill.description = $sce.trustAsHtml(projects[id].description);
                                oneSKill.id = projects[id].id;
                                projectList1[id] = oneSKill;
                            }
							 
                        }
                        else {   }
                    }).error(function(data, status) {  alert(status);
                    });
          },
          projectInvite: function(user_id, project_id)
          {
              $http({method: 'POST', url: "index.php?option=com_pfprojects&task=inviteuser", 
                data: { user_id: user_id, project_id: project_id}}).
                    success(function(data, status, headers, config) {  alert(data);
                        if (data.msg == '')
                        {   
							 
                            //var  projects = JSON.parse(data.projects);
                            /*for (id in projects)
                            {
                                var oneSKill = {};
                                //alert(projects[id].title);
                                if (typeof projects[id].title === 'undefined') continue;
                                oneSKill.title = projects[id].title;
                                oneSKill.description = $sce.trustAsHtml(projects[id].description);
                                oneSKill.id = projects[id].id;
                                projectList1[id] = oneSKill;
                            }*/
							 
                        }
                        else {   }
                    }).error(function(data, status) {  alert(status);
                    });
          }
   }
});

projectModule.factory('theService', function(theMenus, $http) 
{
    
    var $tasks = [];
    var $inputs = [];
    var $chosenSkill = [];
    var userid = 0;
    var catg = 0;
    return {
        skillHandler:{
           x: $inputs,
           xArray: 0,
           chosenSkill: $chosenSkill,
          SkillInput: function()
           {
               
           },
           setUserID: function(id)
           {
               userid = id;
           },
           setCatg: function(changCatg)
           {
               catg = changCatg;
           },
           outputChange: function(id, value)
           {
               $inputs[id] = value;
               this.x = $inputs;
           },
           setChosenSKill: function(taskid, skillid, skill)
           {  
              if(typeof(jQuery("#skiinp_" + taskid + "_" + skillid).val()) != 'undefined') return;//don't add a skill that has already been added
              var s = {}
              s.skill =  skill;
              s.id = skillid;
              if (typeof($chosenSkill[taskid]) == 'undefined') { $chosenSkill[taskid] = []; }
              $chosenSkill[taskid].push(s);
              
           },
           clearSkills: function(taskid)
           {
               var changeSkillsList = [];
               $chosenSkill[taskid] = changeSkillsList;
           },
           delChosenSkill: function(taskid, skillid)
           {
               var changeSkillsList = [];
               for (skill in $chosenSkill[taskid])
               {
                  if (! isNaN($chosenSkill[taskid][skill].id )) 
                  { if ($chosenSkill[taskid][skill].id != skillid) changeSkillsList.push($chosenSkill[taskid][skill]); }
               }
               $chosenSkill[taskid] = changeSkillsList;
           },
           skillSearch: function(skinput)
           {
            var xArray = this.xArray;
            var skillHan = this.outputChange;
            skillHan(xArray, '');
             
            jQuery(".resultsList").css("display", "none");
            $http({method: 'POST', url: tasksURL + "?option=com_pfprojects&task=getskills", 
                data: { skill: skinput}}).
                    success(function(data, status, headers, config) { 
                        if (data.msg == '')
                        { 
                            jQuery("#resultsList" + xArray).css("display", "block");
                            var dataSkill = JSON.parse(data.skills);
                            var skillFormat = [];
                            if (dataSkill[0].id == 0)
                            {
                                jQuery('.addSkillTag').css({'border': '1px solid #f00', 'padding' : '10px'});
                            }
                            else jQuery('.addSkillTag').css({'border': 'none'});
                            for (id in dataSkill)
                            {
                                var oneSKill = {};
                                 
                                oneSKill.id = dataSkill[id].id;
                                oneSKill.skill = dataSkill[id].skill;
                                skillFormat.push(oneSKill);
                            }
                           skillHan(xArray, skillFormat);//let ng-repeat fill the right task with possible skills
                        }
                        else {   }
                    }).error(function(data, status) {  alert(status);
                    });
          },
          SkillDelete: function(id)
          {
              if (userid == 0) 
              {
                 // alert(userid);
                  return;
              }
              $http({method: 'POST', url: tasksURL + "?option=com_pfprojects&task=delskills", 
                data: { id: id, userid: userid, catg: catg}}).
                    success(function(data, status, headers, config) {
                        data = angular.fromJson(data);
                        if (data.msg == '')
                        {
                             jQuery('#skillnk_' + data.id).remove();
                             jQuery('#addskillbox').slideUp();
                        }
                        else alert(data.msg);
                    }).error(function(data, status) {  alert(status);
                    });
          }
        },
        theTasks: {
            addTask: function(task){  
            $tasks.push(task);
        },
            getTasks: function() { return $tasks; } 
        }
    };
});



projectModule.controller('addSkillTag', function($scope, $timeout, skillService)
{
      $scope.skillTitle = skillService.skillHandler;
      $scope.skillTags = skillService.skillInputs;
      
      $scope.addTagForm = function()
      {
          if ($scope.skillTags.length > 4) return;
          skillService.addSkillInputs();
          $timeout(function() {
              jQuery('.newSkillTagCag:last option').each(function()
           { 
               if (jQuery("input[name=skillcatg]").val() == jQuery(this).attr('name'))
               {
                   jQuery('.newSkillTagCag:last').val(jQuery(this).val());
               }
           });
    }, 300);
         
         
              
      }
      $scope.addTagShow = function()
      {
          if ($scope.skillTags.length > 4) return true;
          else return false;
      }
          
});
projectModule.controller('projctInvite', function($scope, projInvite, $timeout)
{
	$scope.projects = projInvite.projectlist;
    $scope.inviteUser = function()
    {
		projInvite.projectSearch();
        $timeout(function() { jQuery('#myProjt').slideDown(); }, 1000);
    }
    $scope.submitInvite = function()
    {
		jQuery('.invcheck').each(function()
		{
			if (jQuery(this).is(':checked')) {  
			    projInvite.projectInvite(jQuery('#invitedUser').val(), jQuery(this).attr('id').replace('project_', ''));
                       }
                });
	}
});
projectModule.controller('taskControl', 
    function($scope, theService, $timeout) {
        $scope.deleteTask = true;
        $scope.delTYesNo = true;
         var $tasks = theService.theTasks.getTasks();
         $scope.tasks = $tasks;
         $scope.Skillset = 0;
         $scope.skillResults = theService.skillHandler.x;
         $scope.skillChosen = theService.skillHandler.chosenSkill;
         $scope.skillPress = function(keyp, taskid)
         {
             theService.skillHandler.xArray = taskid;
             var skillInput = jQuery('#skillInput' + taskid).val();
             theService.skillHandler.skillSearch(skillInput);
         }
         $scope.showDelete = function(show)
         {
             $scope.$apply(function() {
                 if (show) $scope.deleteTask = false;
                 else 
                 {
                     $scope.deleteTask = true;
                     $scope.delTYesNo = true;
                 }
             });
         }
         $scope.SkillYNHide = function()
         {
             $scope.delTYesNo = true;
             
         }
         $scope.SkillYNYes = function()
         {
             theService.skillHandler.SkillDelete($scope.Skillset);
         }
         $scope.deletethisSet = function()
         {
              $scope.delTYesNo = false;
        }
         $scope.hideBox = function(taskid)
         {
             $timeout(function () { jQuery("#resultsList" + taskid).css("display", "none"); }, 2000);
         }
         $scope.clearTags = function()
         {
             theService.skillHandler.clearSkills(1);
             $scope.skillChosen = '';
              $scope.addUserSkills(false);
         }
         $scope.setUserID = function(id)
         {
             theService.skillHandler.setUserID(id);
         }
         $scope.setCatg = function(id)
         {
             theService.skillHandler.setCatg(id);
         }
         $scope.changeTags = function(changeTags)
         {
             if (isNaN(changeTags)) return;
             angular.element('#cleartags').trigger('click');
            // $scope.addUserSkills(false);
         }
         $scope.addUserSkills = function(addTask)
         {
              
             if (typeof addTask == 'undefined' || addTask !== false) { $scope.addTask(); }
             var alrSkills = jQuery("#addusersk").data('addskill');
             for (a in alrSkills)
             { 
                 if (typeof alrSkills[a].id === 'undefined') continue;
                  
                 $scope.skillChosen = theService.skillHandler.setChosenSKill(1, alrSkills[a].id, alrSkills[a].skill); 
             }
             $scope.skillChosen = theService.skillHandler.chosenSkill;
             
         }
         
         $scope.addTask = function()
         {
             var id = $scope.tasks.length + 1;
             var task = {};
             task.id = id;
             jQuery(".resultsList").css("display", "none");
             theService.theTasks.addTask(task);
         }
         $scope.roddy = function()
         {
             alert('arr');
         }
         $scope.editTask = function()
         {
             
             var task = jQuery('#task_1').html();
              
             task = angular.fromJson(task);
             
             var id, getSkills;
             var noTasks = false;
             for (i in task)
             {
                 if (typeof task[i].title == 'undefined') continue;
                 noTasks = true;
                 var task2Add = {};
                 id = $scope.tasks.length + 1;
                 task2Add.id = id;
                 task2Add.idedit = task[i].id;
                 task2Add.title= task[i].title;
                 task2Add.description = task[i].description;
                 getSkills = angular.fromJson(task[i].taskSkills);
                 for (q in getSkills)
                 { 
                     if (typeof getSkills[q].skill == 'undefined') continue;
                     
                     theService.skillHandler.setChosenSKill(task2Add.id, getSkills[q].skill_id, getSkills[q].skill);
                     //alert(getSkills[q].skill);
                 }
                 theService.theTasks.addTask(task2Add);
             }
             if (noTasks === false) $scope.addTask();
             jQuery(".resultsList").css("display", "none");
             
         }
         $scope.focusOnInput = function(taskid)
         { jQuery('#skillInput' + taskid).focus(); }
         $scope.chooseSkill = function(taskid, skillid, skill)//also used for the user to add skills to his profile
         {
             if (skillid == 0) return;//skillid is present when no matching skill has been found
             jQuery(".resultsList").css("display", "none");
             jQuery("#skillInput" + taskid).val('');
             
             $scope.skillChosen = theService.skillHandler.setChosenSKill(taskid, skillid, skill);
             $scope.skillChosen = theService.skillHandler.chosenSkill;
         }
         $scope.deleteSKill = function(taskid, skillid)
         {   theService.skillHandler.delChosenSkill(taskid, skillid); }
    });
