<?php
/**
* @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author iJoomla.com <webmaster@ijoomla.com>
* @url https://www.jomsocial.com/license-agreement
* The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
* More info at https://www.jomsocial.com/license-agreement
*/
defined('_JEXEC') or die();
$validPassword = JText::sprintf( JText::_( 'JLIB_DATABASE_ERROR_VALID_AZ09', true ), JText::_( 'Password', true ), 4 );
$user = JFactory::getUser();
//redacron file ******************

if (isset($getSkiAdded) && is_object($getSkiAdded) && isset($getSkiAdded->skill))
{
    $useSkillAdd = true;
} else $useSkillAdd = false;
?>
<script><!--
    jQuery(document).ready(function($) {  
        jQuery('.token-input-input-token').click(function() { jQuery('.SkillInput').focus(); } );
       jQuery('.catgbox').click(function(e) { jQuery('#addskillbox').slideDown();
           jQuery("input[name=skillcatg]").val(jQuery(this).data('catg')); 
           var categName = jQuery(this).children('.catgtitle').html(); 
           jQuery('#categoryPan').html(categName).css({'color' : '#000'}); } );
});
    function removeLayer() { jQuery('#addskillbox').fadeOut(function() { jQuery('#fade, a.close2').remove(); } ); return false; }
     

    --></script>
<div style='padding: 5px; padding-left: 10px;'><a href='<?php echo JRoute::_('index.php?option=com_community&view=profile&task=skills&Itemid=103');?>'>Go Back</a></div>
<div class="cLayout cProfile-Edit"> 
    <h1 class='colcreh'>Add Skills</h1><br />
     
    <div class='maincatgbox'>
        <?php
        //print_r($userSkills);
         foreach ($skillCategories as $skctg)
        {
             echo "<div class='catgbox' data-catg='$skctg->id'>\n<div class='catgtitle' >".$skctg->category."</div></div>\n";
        }
        ?>
        <div style='clear: both;'></div>
    </div>
    
	 
		
</div>
<div id="addskillbox" style="display: none;"><h1>Define Your Skills</h1>
    <form onSubmit='return addUserSkill()' method="post" action="<?php echo JRoute::_('index.php?option=com_community&view=profile&task=skills&Itemid=103');?>"><table>
            <tr><td valign='top'>Skill: </td><td><input style='width: 520px;' type='text' name='skill2dd' value="<?php echo ($useSkillAdd) ? $getSkiAdded->skill : ''; ?>" /><br /></td></tr>
            <tr><td valign='top'>Category: </td><td><div id="categoryPan" style="font-weight: bold;">None</div><br /></td></tr>
            <tr><td valign='top'>Description:&nbsp;</td><td><textarea name='skilldesc' style='width: 520px; height: 200px;'><?php echo ($useSkillAdd) ? $getSkiAdded->skillDesc : ''; ?></textarea></td></tr>
        <tr><td valign='top'>Skill Tags: </td><td>
                
                  <div ng-app="myProj"><div ng-controller="taskControl" data-ng-init="addUserSkills()" id="addusersk" data-addskill='<?php echo str_replace("'", "\\'", json_encode($userSkills)); ?>'>
     <div class="control-group"><div class="control-label control-group">Adding skills to your profile will help us match them to projects that need people just like you:
               <div class="task-group" ng-repeat="task in tasks">
                    <input type='hidden' ng-repeat='chosenSK in skillChosen[task.id]' value='{{chosenSK.id}}' id='skiinp_{{$index}}' class='taskfID' name="taskfID[{{$index}}]" />
                    <input type='hidden' ng-repeat='chosenSK in skillChosen[task.id]' value='{{chosenSK.skill}}' id='skiinnam_{{$index}}' class='taskNfNam' name="taskNfNam[{{$index}}]" />
                                <ul class="token-input-list">
             
 <li class="token-input-token" ng-repeat='chosenSK in skillChosen[task.id]'><p>{{chosenSK.skill}}</p> 
     <span class="token-input-delete-token" ng-click='deleteSKill(task.id, chosenSK.id)'>×</span></li>
    
     <li class="token-input-input-token" ng-click='focusOnInput(task.id)'>
         <input type='text' id='skillInput{{task.id}}' class="SkillInput" style='border: 0 none !important;' ng-keyup="skillPress($event.altKey, task.id)" />
                
  
     </li></ul><div style='position: relative; margin-left: 50px;'><ul class='resultsList' id='resultsList{{task.id}}'><li ng-click='chooseSkill(task.id, skill.id, skill.skill)' ng-repeat='skill in skillResults[task.id]'>{{skill.skill}}</li></ul></div>
               </div></div></div>
    </div></div>
                
               </td></tr>
        </table> 
    <input type='hidden' name='skillcatg' value='' />
    <input type='hidden' name='editInstead' value='<?php echo ($useSkillAdd) ? 1 : 0; ?>' />
     <input type='hidden' name='userid' value='<?php echo $user->id; ?>' />
    <input type='submit' value='Submit' /> 
    <span id="skillboxWarn">One of the fields is empty</span>
    </form>    
    
</div> 