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
?>
<script><!--
    jQuery(document).ready(function($) {  
        jQuery('.token-input-input-token').click(function() { jQuery('.SkillInput').focus(); } );
        jQuery('.catgbox').click(function(e) {   addSkillLayer(this); } );
});
    function removeLayer() { jQuery('#addskillbox').fadeOut(function() { jQuery('#fade, a.close2').remove(); } ); return false; }
     

    --></script>
<div style='padding: 5px; padding-left: 10px;'><a href='<?php echo JRoute::_('index.php?option=com_community&view=profile&task=skills&Itemid=103');?>'>Go Back</a></div>
<div id="addskillbox"><h1>Add Skill to</h1>
    <form onSubmit='return addUserSkill()'><table>
            <tr><td valign='top'>Skill: </td><td><input style='width: 520px;' type='text' name='skill2dd' /><br /></td></tr>
            <tr><td valign='top'>Description:&nbsp;</td><td><textarea name='skilldesc' style='width: 520px; height: 200px;'></textarea></td></tr>
        <tr><td valign='top'>Skill Tags: </td><td>
                
                  <div ng-app="myProj"><div ng-controller="taskControl">
     <div class="control-group"><div class="control-label control-group">Adding skills to your profile will help us match them to projects that need people just like you:
               <div class="task-group" ng-repeat="task in tasks">
                                <input type='hidden' ng-repeat='chosenSK in skillChosen[task.id]' value='{{chosenSK.id}}' id='skiinp_{{task.id}}_{{chosenSK.id}}' name="taskform[{{task.id}}][SkillInput][]" />
                    <ul class="token-input-list">
             
 <li class="token-input-token" ng-repeat='chosenSK in skillChosen[task.id]'><p>{{chosenSK.skill}}</p> <span class="token-input-delete-token" ng-click='deleteSKill(task.id, chosenSK.id)'>×</span></li>
    
     <li class="token-input-input-token" ng-click='focusOnInput(task.id)'>
         <input type='text' id='skillInput{{task.id}}' class="SkillInput" style='border: 0 none !important;' ng-keyup="skillPress($event.altKey, task.id)" />
                
  
     </li></ul><div style='position: relative; margin-left: 50px;'><ul class='resultsList' id='resultsList{{task.id}}'><li ng-click='chooseSkill(task.id, skill.id, skill.skill)' ng-repeat='skill in skillResults[task.id]'>{{skill.skill}}</li></ul></div>
               </div></div></div>
    </div></div>
                
                <textarea name='skilltags' style='width: 320px; height: 100px;'></textarea></td></tr>
        </table> 
    <input type='hidden' name='skillcatg' value='' />
    
    <input type='hidden' name='userid' value='<?php echo $user->id; ?>' />
    <input type='submit' value='Submit' /> 
    <span id="skillboxWarn">One of the fields is empty</span>
    </form>    
    
</div> 