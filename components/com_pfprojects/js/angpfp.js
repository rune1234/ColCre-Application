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
    //var skillTags = jQuery("textarea[name=skilltags]").val();
    var taskNames = [];
    var taskIds = [];
    jQuery(".taskNfNam").each(function() { var aValue = $(this).val();   taskNames.push(aValue); });
    jQuery(".taskfID").each(function() { var aValue = $(this).val();   taskIds.push(aValue); });
    var taskIds = JSON.stringify(taskIds);
    var skillTags = taskNames.join();
    
     
    var skillCatg = jQuery("input[name=skillcatg]").val();
    
    if (isNaN(skillCatg) || skillCatg == 0) { jQuery('#categoryPan').css({'color' : '#a00'}); jQuery('#skillboxWarn').fadeIn().html('ERROR - category ID is not valid'); return false; }
     
    if (isNaN(userid)) { jQuery('#skillboxWarn').fadeIn().html('ERROR - user id is not valid. You may need to log in'); return false; }
    if (skilltoAdd.trim() == '') { jQuery('#skillboxWarn').fadeIn().html('Your skill must have a name'); return false; }
    if (skillTags.trim() == '') { jQuery('#skillboxWarn').fadeIn().html('Add skill tags'); return false; }
    if (skillDesc.trim() == '') { jQuery('#skillboxWarn').fadeIn().html('Add a skill description'); return false; }
    if (jQuery('#skillboxWarn').is(":visible")) { jQuery('#skillboxWarn').fadeOut().html(''); }
    $fragment_refresh = {
		url: tasksURL,
		type: 'POST',
		data: { option: 'com_pfprojects', taskIds: taskIds, task:'addUserKill', userid:userid, skilltoAdd: skilltoAdd, skillDesc: skillDesc, skillTags: skillTags, skillCatg: skillCatg},
		success: function( data ) {  
                     data = JSON.parse(data);
                     if (!data.status)
                     {
                         alert(data.error);
                     }
                     else
                     {
                         jQuery('#addskillbox').fadeOut(function() { jQuery('#fade, a.close2').remove(); } );
                     }
                } };
    jQuery.ajax( $fragment_refresh );
    return false;
} 
var projectModule = angular.module('myProj', []);
projectModule.factory('theMenus', function() { return });
projectModule.factory('theService', function(theMenus, $http) {
    
    var $tasks = [];
    var $inputs = [];
    var $chosenSkill = [];
    return {
        skillHandler:{
           x: $inputs,
           xArray: 0,
           chosenSkill: $chosenSkill,
          SkillInput: function()
           {
               
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
              s.skill = skill;
              s.id = skillid;
              if (typeof($chosenSkill[taskid]) == 'undefined') { $chosenSkill[taskid] = []; }
              $chosenSkill[taskid].push(s);
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


projectModule.controller('taskControl', 
    function($scope, theService) {
         var $tasks = theService.theTasks.getTasks();
         $scope.tasks = $tasks;
         $scope.skillResults = theService.skillHandler.x;
         $scope.skillChosen = theService.skillHandler.chosenSkill;
         $scope.skillPress = function(keyp, taskid)
         {
             theService.skillHandler.xArray = taskid;
             var skillInput = jQuery('#skillInput' + taskid).val();
             theService.skillHandler.skillSearch(skillInput);
         }
         $scope.addTask = function()
         {
             var id = $scope.tasks.length + 1;
             var task = {};
             task.id = id;
             jQuery(".resultsList").css("display", "none");
             theService.theTasks.addTask(task);
         }
         $scope.editTask = function()
         {
             var task = jQuery('#task_1').html();
             task = angular.fromJson(task);
             var id = $scope.tasks.length + 1;
             // var task = {};
             task.id = id;
             task.title= task[0].title;
             task.description = task[0].description;
             jQuery(".resultsList").css("display", "none");
             theService.theTasks.addTask(task);
         }
         $scope.focusOnInput = function(taskid)
         { jQuery('#skillInput' + taskid).focus(); }
         $scope.chooseSkill = function(id, skillid, skill)
         {
             jQuery(".resultsList").css("display", "none");
             jQuery("#skillInput" + id).val('');
             $scope.skillChosen = theService.skillHandler.setChosenSKill(id, skillid, skill);
             $scope.skillChosen = theService.skillHandler.chosenSkill;
         }
         $scope.deleteSKill = function(taskid, skillid)
         { theService.skillHandler.delChosenSkill(taskid, skillid); }
    });