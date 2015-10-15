<?php
/**
 * @package      Projectfork
 * @subpackage   Tasks
 *
 * @author       Tobias Kuhn (eaxs)
 * @copyright    Copyright (C) 2006-2012 Tobias Kuhn. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl.html GNU/GPL, see LICENSE.txt
 */

defined('_JEXEC') or die();


JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.calendar');
JHtml::_('behavior.formvalidation');
JHtml::_('pfhtml.script.form');
 function getSkillsList()//almost the same as the above function
    {
        $db = JFactory::getDbo();
       // $query = "SELECT * FROM #__pf_skill_category ORDER BY category";
        $query = "SELECT id, LOWER(title) as category FROM #__categories WHERE extension='com_pfprojects' ORDER BY LOWER(title)";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        return $rows;
    }
    $skillCategories = getSkillsList();
// Create shortcut to parameters.
$params = $this->state->get('params');
$user   = JFactory::getUser();
?>
<script type="text/javascript">
<!--
jQuery(document).ready(function()
{
    PFform.radio2btngroup();
});

Joomla.submitbutton = function(task)
{
	if (task == 'taskform.cancel' || document.formvalidator.isValid(document.id('item-form'))) {
		<?php echo $this->form->getField('description')->save(); ?>
        Joomla.submitform(task, document.getElementById('item-form'));
	} else {
		alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
	}
}
//-->
</script>
<div class="edit item-page<?php echo $this->pageclass_sfx; ?>">
    
<?php if ($params->get('show_page_heading', 0)) : ?>
<h1>
	<?php echo $this->escape($params->get('page_heading')); ?>
</h1>
<?php endif; ?>

<form action="<?php echo JRoute::_('index.php?option=com_pftasks&view=taskform&id=' . (int) $this->item->id . '&layout=edit'); ?>" method="post" name="adminForm" id="item-form" class="form-inline">
   
    <fieldset>
		
        <?php if ($this->item->id <= 0) : ?>
    		<div class="formelm control-group">
                <div class="control-label">
    		    	<?php echo $this->form->getLabel('project_id'); ?>
    		    </div>
    		    <div class="controls">
    		    	<?php echo $this->form->getInput('project_id'); ?>
    		    </div>
    		</div>
        <?php endif; ?>
        <?php if (1 == 2 && PFApplicationHelper::enabled('com_pfmilestones')) : ?>
    		<div class="formelm control-group">
    			<div class="control-label">
    		    	<?php echo $this->form->getLabel('milestone_id'); ?>
    		    </div>
    		    <div class="controls" id="jform_milestone_id_reload">
    		    	<?php echo $this->form->getInput('milestone_id'); ?>
    		    </div>
    		</div>
        <?php endif; if (1 == 2 ) { ?>
		<div class="formelm control-group">
			<div class="control-label">
		    	<?php echo $this->form->getLabel('list_id'); ?>
		    </div>
		    <div class="controls" id="jform_list_id_reload">
		    	<?php echo $this->form->getInput('list_id'); ?>
		    </div>
		</div>
        <?php } ?>
        <div id="jform_access_element">
            <div id="jform_access_reload"><?php echo $this->form->getInput('access'); ?></div>
        </div>
		<div class="formelm control-group">
			<div class="control-label">
		    	<?php echo $this->form->getLabel('title'); ?>
		    </div>
		    <div class="controls">
		    	<?php echo $this->form->getInput('title'); ?>
		    </div>
		</div>
        <div class="formelm control-group">
            <div class="control-label">
                <?php echo $this->form->getLabel('labels'); ?>
            </div>
            <div class="controls" id="jform_labels_reload">
                <?php echo $this->form->getInput('labels'); ?>
            </div>
        </div>
		<div class="formelm control-group">
		    <div class="controls">
		    	<?php echo $this->form->getInput('description'); ?>
		    </div>
		</div>
        
        
         <hr />
         <?php
         //WARNING: this is tricky. $this->TaskNskills will be outside the rest of the form, and will need to become part of the controlle taskform.php with some tweaks here and there
         ?>
         <div id="task_1" style="display: none;"><?php echo json_encode($this->TaskNskills); // this is a way to send data to Angular.JS without relying on Ajax; ?></div>
       <div ng-app="myProj"><div id="projTasks" ng-controller="taskControl" data-ng-init="editTask()">
             
    <h2>Add Tags</h2>
    <?php $addTask = 0; ?> 
        <div class="task-group" ng-repeat="task in tasks">
                     
                        <br /><br />
                        <div class="control-group"><div class="control-label control-group"><b>Tags:</b>
                                <p style='font-size: 11px;'>Add a Tag. A tag can be a skill, keyword, location, or preferred language. If you cannot see it in the auto-suggestion, you can add it below.</p>
                                <input type='hidden' ng-repeat='chosenSK in skillChosen[<?php echo $addTask;?> + task.id]' value='{{chosenSK.id}}' id='skiinp_{{<?php echo $addTask;?> + task.id}}_{{chosenSK.id}}' name="taskform[{{<?php echo $addTask;?> + task.id}}][SkillInput][]"  />
                    <ul class="token-input-list" style="height: auto;">
             <?php
               
             ?>
 <li class="token-input-token" ng-repeat='chosenSK in skillChosen[<?php echo $addTask;?> + task.id]'><p>{{chosenSK.skill}}</p> <span class="token-input-delete-token" ng-click='deleteSKill(<?php echo $addTask;?> + task.id, chosenSK.id)'>×</span></li>
    
     <li class="token-input-input-token" ng-click='focusOnInput(<?php echo $addTask;?> + task.id)'>
 <input type='text' id='skillInput{{<?php echo $addTask;?> + task.id}}' class="SkillInput" style='width: 100%; background: #fff;' ng-keyup="skillPress($event.altKey, <?php echo $addTask;?> + task.id)" />
                
  
     </li></ul><div style='position: relative; margin-left: 50px;'><ul class='resultsList' id='resultsList{{<?php echo $addTask;?> + task.id}}'><li ng-click='chooseSkill(<?php echo $addTask;?> + task.id, skill.id, skill.skill)' ng-repeat='skill in skillResults[<?php echo $addTask;?> + task.id]'>{{skill.skill}}</li></ul></div>
               </div></div>
                        <?php
                        {
                        ?>
                        <br /><div ng-controller="addSkillTag" class="addSkillTag"><h4>Add New Tag:</h4>
            
            <div ng-repeat='skillTag in skillTags'> 
            
            <br ng-if="skillTag.id > 0" /> 
            <b>Tag {{skillTag.id + 1}}:</b> <input type="text" class="newSkillTag" name="taskform[newSkillTag][{{skillTag.id}}]" value="" />
           <!-- <br /><b>Skill Category:</b> 
         <select class="newSkillTagCag" name="taskform[{{<?php echo $addTask;?> + task.id}}][newSkillTagCag][{{skillTag.id}}]"><?php 
         /*foreach ($skillCategories as $skctg)
        {
             echo "<option value='$skctg->id'>".$skctg->category."</option>\n";
             }*/
         ?></select>
            --> 
            </div>
            <div style='margin: 10px 5px;' ng-Click='addTagForm()' ng-hide="addTagShow()"><a>Add Another Tag</a></div>
        
        
    
      </div><?php
      }
      ?>
		</div><br /><br />
                 <input type='hidden' name="taskform[{{<?php echo $addTask;?> + task.id}}][idedit]" value='{{task.idedit}}' />    
        </div>
         
         
          
        </div>
       
    </div>
        
	</fieldset>

    <?php echo JHtml::_('tabs.start', 'taskform', array('useCookie' => 'true')) ;?>
    <?php echo JHtml::_('tabs.panel', JText::_('COM_PROJECTFORK_FIELDSET_PUBLISHING'), 'task-publishing') ;?>
    <fieldset>
    	<!--<div class="formelm control-group">
    		<div class="control-label">
    	    	<?php echo $this->form->getLabel('state'); ?>
    	    </div>
    	    <div class="controls">
    	    	<?php echo $this->form->getInput('state'); ?>
    	    </div>
    	</div>
    	<div class="formelm control-group">
    		<div class="control-label">
    	    	<?php echo $this->form->getLabel('priority'); ?>
    	    </div>
    	    <div class="controls">
    	    	<?php echo $this->form->getInput('priority'); ?>
    	    </div>
    	</div>-->
    	<div class="formelm control-group">
    		<div class="control-label">
    	    	<?php echo $this->form->getLabel('complete'); ?>
    	    </div>
    	    <div class="controls">
    	    	<?php echo $this->form->getInput('complete'); ?>
    	    </div>
    	</div>
    	<div class="formelm control-group">
    		<div class="control-label">
    	    	<?php echo $this->form->getLabel('start_date'); ?>
    	    </div>
    	    <div id="jform_start_date_reload" class="controls">
    	    	<?php echo $this->form->getInput('start_date'); ?>
    	    </div>
    	</div>
    	<div class="formelm control-group">
    		<div class="control-label">
    	    	<?php echo $this->form->getLabel('end_date'); ?>
    	    </div>
    	    <div id="jform_end_date_reload" class="controls">
    	    	<?php echo $this->form->getInput('end_date'); ?>
    	    </div>
    	</div>
        <div class="formelm control-group">
    		<div class="control-label">
    	    	<?php echo $this->form->getLabel('rate'); ?>
    	    </div>
    	    <div class="controls">
    	    	<?php echo $this->form->getInput('rate'); ?>
    	    </div>
    	</div>
        <div class="formelm control-group">
    		<div class="control-label">
    	    	<?php echo $this->form->getLabel('estimate'); ?>
    	    </div>
    	    <div class="controls">
    	    	<?php echo $this->form->getInput('estimate'); ?>
    	    </div>
    	</div>
    	<?php if ($this->item->modified_by) : ?>
	    	<div class="formelm control-group">
	    		<div class="control-label">
	    	    	<?php echo $this->form->getLabel('modified_by'); ?>
	    	    </div>
	    	    <div class="controls">
	    	    	<?php echo $this->form->getInput('modified_by'); ?>
	    	    </div>
	    	</div>
	    	<div class="formelm control-group">
	    		<div class="control-label">
	    	    	<?php echo $this->form->getLabel('modified'); ?>
	    	    </div>
	    	    <div class="controls">
	    	    	<?php echo $this->form->getInput('modified'); ?>
	    	    </div>
	    	</div>
    	<?php endif; ?>
    </fieldset>

    <?php echo JHtml::_('tabs.panel', JText::_('COM_PROJECTFORK_FIELDSET_ASSIGNED_USERS'), 'task-users') ;?>
    <fieldset>
    	<div id="jform_users_element" class="formelm control-group">
            <div id="jform_users_reload">
		        <?php echo $this->form->getInput('users'); ?>
            </div>
    	</div>
    </fieldset>
<!--
    <?php echo JHtml::_('tabs.panel', JText::_('COM_PROJECTFORK_FIELDSET_DEPENDENCIES'), 'task-dependencies') ;?>
    <fieldset>
    	<div id="jform_dependency_element" class="formelm control-group">
            <div id="jform_dependency_reload">
		        <?php echo $this->form->getInput('dependency'); ?>
            </div>
    	</div>
    </fieldset>

    <?php if (PFApplicationHelper::enabled('com_pfrepo')) : ?>
    <?php echo JHtml::_('tabs.panel', JText::_('COM_PROJECTFORK_FIELDSET_ATTACHMENTS'), 'task-attachments') ;?>
        <fieldset>
        	<div id="jform_attachment_element" class="formelm control-group">
                <div id="jform_attachment_reload">
        		  <?php echo $this->form->getInput('attachment'); ?>
                </div>
        	</div>
        </fieldset>
    <?php endif; ?>
-->
    <?php
    $fieldsets = $this->form->getFieldsets('attribs');
    if (count($fieldsets)) :
        echo JHtml::_('tabs.panel', JText::_('COM_PROJECTFORK_DETAILS_FIELDSET'), 'task-options');
		foreach ($fieldsets as $name => $fieldset) :
            ?>
			<fieldset>
                <?php foreach ($this->form->getFieldset($name) as $field) : ?>
                    <div class="formelm control-group">
                    	<div class="control-label"><?php echo $field->label; ?></div>
            		    <div class="controls"><?php echo $field->input; ?></div>
            		</div>
                <?php endforeach; ?>
            </fieldset>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if ($user->authorise('core.admin', 'com_pftasks') || $user->authorise('core.manage', 'com_pftasks')) : ?>
        <?php echo JHtml::_('tabs.panel', JText::_('COM_PROJECTFORK_FIELDSET_RULES'), 'task-permissions') ;?>
        <fieldset>
            <p><?php echo JText::_('COM_PROJECTFORK_RULES_LABEL'); ?></p>
            <p><?php echo JText::_('COM_PROJECTFORK_RULES_NOTE'); ?></p>
            <div class="formlm" id="jform_rules_element">
                <div id="jform_rules_reload" class="controls">
                    <?php echo $this->form->getInput('rules'); ?>
                </div>
            </div>
        </fieldset>
    <?php endif; ?>

    <?php echo JHtml::_('tabs.end') ;?>

    <div style="display: none;">
    <?php
        if ($this->item->id > 0) {
            echo $this->form->getInput('project_id');
        }
        echo $this->form->getInput('alias');
        echo $this->form->getInput('created');
        echo $this->form->getInput('elements');
    ?>
    </div>
   
     
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="return" value="<?php echo $this->return_page;?>" />
    <input type="hidden" name="view" value="<?php echo htmlspecialchars($this->get('Name'), ENT_COMPAT, 'UTF-8');?>" />
	<?php echo JHtml::_( 'form.token' ); ?>
    <div class="formelm-buttons btn-toolbar">
            <?php echo $this->toolbar; ?>
		</div>
</form>
</div>
<?php
    $document = JFactory::getDocument();
    $uribase = JURI::base(true). "/components/com_pfprojects/css/style.css";
    $document->addStyleSheet($uribase);
    $document->addScript(JURI::root() . 'libraries/projectfork/js/angular.min.js');
    $document->addScript(JURI::root() . 'components/com_pfprojects/js/angpfp.js');
    $js = "var tasksURL = '".JURI::root()."';";
    $document->addScriptDeclaration($js);
?>