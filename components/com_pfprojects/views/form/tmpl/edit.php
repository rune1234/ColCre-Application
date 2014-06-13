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
            <div class="control-group"><div class="control-label control-group">Skills Required:
                    <ul class="token-input-list">
             

    <li class="token-input-token">
        <p>

            jquery mobile

        </p>
        <span class="token-input-delete-token">

            ×

        </span>
    </li>
    <li class="token-input-token">
        <p>

            psd to joomla

        </p>
        <span class="token-input-delete-token">

            ×

        </span>
    </li>
    
     <li class="token-input-input-token">

  
</li></ul> 
                <input id="ctl00_ctl00_guB_guB_ctl00_txtSkills_txtSkills_TextBox" class="txtInput" type="text" maxlength="50" name="ctl00$ctl00$guB$guB$ctl00$txtSkills$txtSkills_TextBox" style="display: none;"></input>
                </div></div>
            
            <div class="control-group">
                    <div class="control-label"><?php echo $this->form->getLabel('project_brief'); ?>:</div>
                    <div class="controls"><textarea name='jform[project_brief]' wrap="off" cols="90" rows="4" style='overflow: auto;'></textarea>
				<?php// echo $this->form->getInput('project_brief'); ?>
			</div>
		</div>
            
	</fieldset>

    <hr />
    <h2>Add Tasks</h2>
    <p>Here you can divide your project into the different tasks and goals required for the project to be completed.</p>
        <div class="control-group">
                    <div class="control-label">Task 1:</div>
                    <div class="controls"><textarea name='taskform[task]' wrap="off" cols="90" rows="4" style='overflow: auto;'></textarea>
                        <br /><br />Choose how you will measure the success of this task<br /><br />
                        <div style='float: left;'><select name='taskform[measure]'>
                            <option value='1'>Likes</option>
                            <option value='2'>Comments</option>
                            <option value='3'>Commitments</option>
                        </select></div>
                            <div style="float: right;">How Many? <input type='text' /></div>
                        <br /><br />
                        <div id="addtask">Add Another Task</div>	
		</div><br /><br />
      <hr />

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
				<?php echo $this->form->getInput('start_date'); ?>
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

    <?php echo JHtml::_('tabs.panel', JText::_('COM_PROJECTFORK_FIELDSET_LABELS'), 'project-labels') ;?>
    <fieldset>
    	<div class="formelm control-group">
    		<?php echo $this->form->getInput('labels'); ?>
    	</div>
    </fieldset>

    <?php if ($this->item->id && PFApplicationHelper::enabled('com_pfrepo')) : ?>
    <?php echo JHtml::_('tabs.panel', JText::_('COM_PROJECTFORK_FIELDSET_ATTACHMENTS'), 'project-attachments') ;?>
    <fieldset>
    	<div class="formelm control-group">
    		<?php echo $this->form->getInput('attachment'); ?>
    	</div>
    </fieldset>
    <?php endif; ?>

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
