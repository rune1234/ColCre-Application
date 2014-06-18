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
 
var projectModule = angular.module('myProj', []);
projectModule.factory('theMenus', function() { return });
projectModule.factory('theService', function(theMenus, $http) {
    
    var $tasks = [{id:'1'}];
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