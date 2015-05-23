<?php
/**
 * @package      Projectfork
 * @subpackage   Projects
 *
 * @author       Tobias Kuhn (eaxs)
 * @copyright    Copyright (C) 2006-2012 Tobias Kuhn. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl.html GNU/GPL, see LICENSE.txt
 */

defined('_JEXEC') or die();


JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.calendar');
JHtml::_('pfhtml.script.form');

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
	if (task == 'form.cancel' || document.getElementById('jform_title').value != '') {
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
<h1><?php echo $this->escape($params->get('page_heading')); ?></h1>
<?php endif; ?>
 
<form action="<?php echo JRoute::_('index.php?option=com_pfprojects&view=form&id=' . (int) $this->item->id . '&layout=edit'); ?>" method="post" name="adminForm" id="item-form" class="form-inline" enctype="multipart/form-data">
	<fieldset>
		
		<div class="formelm control-group">
			<div class="control-label">
		    	<?php echo $this->form->getLabel('title'); ?>
		    </div>
		    <div class="controls">
		    	<?php echo $this->form->getInput('title'); ?>
                       
		    </div>
		</div>
		<div class="control-group">
                    <div class="control-label">Description:</div>
			<div class="controls">
				<?php echo $this->form->getInput('description'); ?>
			</div>
		</div>
            
           <!--
            <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('project_brief'); ?>:</div>
                    <div class="controls"><textarea name='jform[project_brief]' wrap="off" cols="90" rows="4" style='overflow: auto;'></textarea>
				<?php// echo $this->form->getInput('project_brief'); ?>
			</div>
		</div>
            -->
	</fieldset>
      <hr /> 
      <div style="background: #777; color: #fff; padding: 10px; border-radius: 5px; width: 90%;">
      <?php
    $fieldsets = $this->form->getFieldsets('attribs');
    if (count($fieldsets)) :
        echo JHtml::_('tabs.panel', JText::_('COM_PROJECTFORK_DETAILS_FIELDSET'), 'project-options');
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
    <?php endif;
    ?>
    
       <div class="formelm control-group">
                        <div class="control-label">Project Comments:</div>
                                <div class="controls"><select name="commentsetting">
                                        <option value="0">All Users</option>
                                        <option value="1"<?php echo ($this->item->commentsetting == 1) ? 'selected="selected"' : '';?>>Only Invited Users and Project Members</option>
                                         <option value="2"<?php echo ($this->item->commentsetting == 2) ? 'selected="selected"' : '';?>>Only Project Members</option>
                                          <option value="3"<?php echo ($this->item->commentsetting == 3) ? 'selected="selected"' : '';?>>Comments are not Allowed</option>
                            </select></div>
            		</div>  
      </div>
<div id="task_1" style='display: none;'><?php echo json_encode($this->tasks); // this is a way to send data to Angular.JS without relying on Ajax; ?></div>
    <hr /><div ng-app="myProj"><div id="projTasks" ng-controller="taskControl" data-ng-init="editTask()">
             
    <h2>Add Tasks</h2>
    <p>Here you can divide your project into the different tasks and goals required for the project to be completed.</p>
    <?php $addTask = 0; ?> 
        <div class="task-group" ng-repeat="task in tasks">
                    <div class="control-label">Task {{task.id}}:</div><br />
                    <div class="controls">Title: <input type='text' name='taskform[{{<?php echo $addTask;?> + task.id}}][title]' value="{{task.title}}" /></div><br />
                    <div class="controls">Task Category: <select name="taskform[{{<?php echo $addTask;?> + task.id}}][category]">
                            
                    <?php 
                    foreach($this->categories as $catg)
                    {
                        echo "<option value='$catg->id'>$catg->title</option>\n";
                    }
                     ?>
                        </select></div><br />
                    <div class="controls">Description: <textarea name='taskform[{{<?php echo $addTask;?> + task.id}}][description]' wrap="off" cols="90" rows="4" style='width: 600px; overflow: auto;'>{{task.description}}</textarea>
                        <!--<br /><br />Choose how you will measure the success of this task<br /><br />
                        <div style='float: left;'><select name='taskform[{{<?php echo $addTask;?> + task.id}}][measure]'>
                             <option value='2'>Comments</option>
                            <option value='3'>Commitments</option>
                        </select></div>
                            <div style="float: right;">How Many? <input type='text' name="taskform[{{<?php echo $addTask;?> + task.id}}][howmanylikes]" /></div>-->
                        <br /><br />
                        <div class="control-group"><div class="control-label control-group">Tags:
                                <input type='hidden' ng-repeat='chosenSK in skillChosen[<?php echo $addTask;?> + task.id]' value='{{chosenSK.id}}' id='skiinp_{{<?php echo $addTask;?> + task.id}}_{{chosenSK.id}}' name="taskform[{{<?php echo $addTask;?> + task.id}}][SkillInput][]" />
                    <ul class="token-input-list" style="height: auto;">
             <?php
              
              if (is_array($this->tasks))
              {
                  
              }
             ?>
 <li class="token-input-token" ng-repeat='chosenSK in skillChosen[<?php echo $addTask;?> + task.id]'><p>{{chosenSK.skill}}</p> <span class="token-input-delete-token" ng-click='deleteSKill(<?php echo $addTask;?> + task.id, chosenSK.id)'>×</span></li>
    
     <li class="token-input-input-token" ng-click='focusOnInput(<?php echo $addTask;?> + task.id)'>
 <input type='text' id='skillInput{{<?php echo $addTask;?> + task.id}}' class="SkillInput" style='width: 100%' ng-keyup="skillPress($event.altKey, <?php echo $addTask;?> + task.id)" />
                
  
     </li></ul><div style='position: relative; margin-left: 50px;'><ul class='resultsList' id='resultsList{{<?php echo $addTask;?> + task.id}}'><li ng-click='chooseSkill(<?php echo $addTask;?> + task.id, skill.id, skill.skill)' ng-repeat='skill in skillResults[<?php echo $addTask;?> + task.id]'>{{skill.skill}}</li></ul></div>
               </div>
                         <br /><div ng-controller="addSkillTag" class="addSkillTag"><h4>Add a Skill Tag:</h4><form><br />
            
            <div ng-repeat='skillTag in skillTags'> 
            
            <br ng-if="skillTag.id > 0" /> 
            <b>Skill {{skillTag.id + 1}}:</b> <input type="text" class="newSkillTag" name="taskform[{{<?php echo $addTask;?> + task.id}}][newSkillTag][{{skillTag.id}}]" value="" />
            <br /><b>Skill Category:</b> 
         <select class="newSkillTagCag" name="taskform[{{<?php echo $addTask;?> + task.id}}][newSkillTagCag][{{skillTag.id}}]"><?php foreach ($this->skillCategories as $skctg)
        {
             echo "<option value='$skctg->id'>".$skctg->category."</option>\n";
             }?></select>
             
            </div>
            <div style='margin: 10px 5px;' ng-Click='addTagForm()' ng-hide="addTagShow()"><a>Add Another Tag</a></div>
        
        
    
      </div> 
                        </div>
                       
		</div><br /><br />
                 <input type='hidden' name="taskform[{{<?php echo $addTask;?> + task.id}}][idedit]" value='{{task.idedit}}' />    
        </div>
         <div id="addtask" ng-click="addTask()">Add Another Task</div>
         
          
        </div>
       
    </div>
    
      <div id='projectTabs'> 
    <?php echo JHtml::_('tabs.start', 'projectform', array('useCookie' => 'true')) ;?> 
    <?php echo JHtml::_('tabs.panel', JText::_('COM_PROJECTFORK_FIELDSET_PUBLISHING'), 'project-publishing') ;?> 
    <fieldset>
        <div class="formelm control-group">
        	<div class="control-label">
		    	<?php echo $this->form->getLabel('state'); ?>
		    </div>
		    <div class="controls">
				<?php echo $this->form->getInput('state'); ?>
			</div>
		</div>
		<div class="formelm control-group">
			<div class="control-label">
				<?php echo $this->form->getLabel('catid'); ?>
		    </div>
		    <div class="controls">
				<?php echo $this->form->getInput('catid'); ?>
			</div>
		</div>
        <div class="formelm control-group">
        	<div class="control-label">
		    	<?php echo $this->form->getLabel('start_date'); ?>
		    </div>
		    <div class="controls">
				<?php 
                                
                                
                                echo $this->form->getInput('start_date'); ?>
			</div>
		</div>
        <div class="formelm control-group">
        	<div class="control-label">
		    	<?php echo $this->form->getLabel('end_date'); ?>
		    </div>
		    <div class="controls">
				<?php echo $this->form->getInput('end_date'); ?>
			</div>
		</div>
        <?php if ($this->item->modified_by) : ?>
            <div class="formelm control-group">
            	<div class="control-label">
                	<?php echo $this->form->getLabel('modified_by');?>
                </div>
                <div class="controls">
                	<?php echo $this->form->getInput('modified_by');?>
                </div>
            </div>
            <div class="formelm control-group">
            	<div class="control-label">
                	<?php echo $this->form->getLabel('modified');?>
                </div>
                <div class="controls">
                	<?php echo $this->form->getInput('modified');?>
                </div>
            </div>
		<?php endif; ?>
    </fieldset>
<!--
    <?php //echo JHtml::_('tabs.panel', JText::_('COM_PROJECTFORK_FIELDSET_LABELS'), 'project-labels') ;?>
    <fieldset> <---- These are the labels, all of them, just these lines
    	<div class="formelm control-group">
    		<?php // echo $this->form->getInput('labels'); ?>
    	</div>
    </fieldset>
-->
    <?php if ($this->item->id && PFApplicationHelper::enabled('com_pfrepo')) : ?>
    <?php echo JHtml::_('tabs.panel', JText::_('COM_PROJECTFORK_FIELDSET_ATTACHMENTS'), 'project-attachments') ;?>
    <fieldset>
    	<div class="formelm control-group">
    		<?php echo $this->form->getInput('attachment'); ?>
    	</div>
    </fieldset>
    <?php endif; ?>

    

    <?php if ($user->authorise('core.admin', 'com_pfprojects') || $user->authorise('core.manage', 'com_pfprojects')) : ?>
        <?php echo JHtml::_('tabs.panel', JText::_('COM_PROJECTFORK_FIELDSET_RULES'), 'project-permissions') ;?>
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
 </div>
    <?php
        echo $this->form->getInput('alias');
        echo $this->form->getInput('created');
        echo $this->form->getInput('id');
        echo $this->form->getInput('asset_id');
        echo $this->form->getInput('elements');
        
    ?>

	<input type="hidden" name="task" value="" />
	<input type="hidden" name="return" value="<?php echo $this->return_page;?>" />
    <input type="hidden" name="view" value="<?php echo htmlspecialchars($this->get('Name'), ENT_COMPAT, 'UTF-8');?>" />
	<?php echo JHtml::_( 'form.token' ); ?>
    <div class="formelm-buttons btn-toolbar">
		    <?php echo $this->toolbar; ?>
		</div>
    
     
</form>
</div>
