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


// Create shortcuts to some parameters.
$item    = &$this->item;
$params	 = $item->params;
$canEdit = $item->params->get('access-edit');
$user	 = JFactory::getUser();
$uid	 = $user->get('id');

$nulldate = JFactory::getDBO()->getNullDate();

$asset_name = 'com_pftasks.task.'.$this->item->id;
$canEdit	= ($user->authorise('core.edit', $asset_name));
$canEditOwn	= ($user->authorise('core.edit.own', $asset_name) && $this->item->created_by == $uid);
$projerized = PFtasksHelper::projectPerm($item->project_id, $user->id);//redacron
$authorized = PFtasksHelper::taskPermission($item->id, $user->id);//redacron
//print_r($this->skillsNeeded);
?> 
<div id="projectfork" class="item-page view-task projectFrame" style="padding: 10px;">
	<?php if ($this->params->get('show_page_heading', 1)) : ?>
    <h3>Task:</h3>
    <?php endif; ?>

    <div class="btn-toolbar btn-toolbar-top">
        <?php echo $this->toolbar;?>
    </div>

    <div class="page-header">
	    <h2><?php echo $this->escape($item->title); ?></h2>
	</div>

    <?php echo $item->event->beforeDisplayContent;?>
<div ng-app="myLikes">
            <div ng-controller="taskLike"  data-ng-init="getLikes(<?php echo $item->id; ?>, <?php echo $user->id;?>)">
                <div ng-click="like(<?php echo $user->id;?>, <?php echo $item->id; ?>)" class="likemain"><div class="likelayer"></div><br /><span>Like</span> | <span style="color: #000; font-size: 12px;"><span ng-bind="likes"></span> Likes</span></div>
                <div style="float: left; margin-left: 150px; margin-top: -33px;"><a href="#comments" style="color: #a00;"><img src="<?php echo JURI::base()."images/comicon.jpg";?>" alt="comment icon" style="height: 45px;"/>Comments</a></div>
                  
            
                <div style="clear: both"></div>
            </div>
        </div><br />
	<div class="item-description">
		<?php echo $item->text; ?>
<?php if($this->skillsNeeded && is_numeric($this->skillsNeeded[0]->task_id) && $this->skillsNeeded[0]->task_id > 0) : ?>
        		<div style="margin: 10px 0;"> 
                            <dt>Skills Needed:</dt>
        		 
                            <dd><?php 
                            $skillComm = array();
                            foreach ($this->skillsNeeded  as $tskl) { $skillComm[] = $tskl->skill; }
                            echo implode(', ', $skillComm);
                            ?></dd></div> 
            <?php endif; ?>
        <dl class="article-info dl-horizontal pull-right">
    		<dt class="project-title">
    			<?php echo JText::_('JGRID_HEADING_PROJECT');?>:
    		</dt>
    		<dd class="project-data">
    			<a href="<?php echo JRoute::_(PFprojectsHelperRoute::getDashboardRoute($item->project_slug));?>"><?php echo $item->project_title;?></a>
    		</dd>
                
                <?php if($item->milestone_id) : ?>
        		<dt class="milestone-title">
        			<?php echo JText::_('JGRID_HEADING_MILESTONE');?>:
        		</dt>
        		<dd class="milestone-data">
        			<a href="<?php echo JRoute::_(PFmilestonesHelperRoute::getMilestoneRoute($item->project_slug, $item->milestone_slug));?>"><?php echo $item->milestone_title;?></a>
        		</dd>
            <?php endif; ?>
                
            
            <?php if($item->list_id) : ?>
        		<dt class="list-title">
        			<?php echo JText::_('JGRID_HEADING_TASKLIST');?>:
        		</dt>
        		<dd class="list-data">
        			<a href="<?php echo JRoute::_(PFtasksHelperRoute::getTasksRoute($item->project_slug, $item->milestone_slug, $item->list_slug));?>">
                        <?php echo $item->list_title;?>
                    </a>
        		</dd>
            <?php endif; ?>
            <?php if($item->start_date != $nulldate): ?>
        		<dt class="start-title">
        			<?php echo JText::_('JGRID_HEADING_START_DATE');?>:
        		</dt>
        		<dd class="start-data">
        		  <?php echo JHtml::_('pfhtml.label.datetime', $item->start_date); ?>
        		</dd>
            <?php endif; ?>
            <?php if($item->end_date != $nulldate): ?>
        		<dt class="due-title">
        			<?php echo JText::_('JGRID_HEADING_DEADLINE');?>:
        		</dt>
        		<dd class="due-data">
        			<?php echo JHtml::_('pfhtml.label.datetime', $item->end_date); ?>
        		</dd>
            <?php endif; ?>
    		<dt class="owner-title">
    			<?php echo JText::_('JGRID_HEADING_CREATED_BY');?>:
    		</dt>
    		<dd class="owner-data">
    			 <?php  
                          echo JHtml::_('pfhtml.label.author', $item->author, $item->created); ?>
    		</dd>
            <?php if (PFApplicationHelper::enabled('com_pfrepo') && count($item->attachments)) : ?>
                <dt class="attachment-title">
        			<?php echo JText::_('COM_PROJECTFORK_FIELDSET_ATTACHMENTS'); ?>:
        		</dt>
        		<dd class="attachment-data">
                     <?php echo JHtml::_('pfrepo.attachments', $item->attachments); ?>
        		</dd>
            <?php endif; ?>
    	</dl>
   
        <div class="clearfix"></div>
	</div>
 
	<hr />
<?php if ($user->id == $this->item->created_by)  { ?>
        
  <div class="formelm control-group">
    		<div class="control-label">
                    <label id="jform_complete-lbl" for="jform_complete" class="hasTooltip" title="<strong>Complete</strong><br />Select whether this task is completed or not."><b>Mark Task as Completed:</b></label>    	    </div>
    	    <div class="controls">
    	    	 
                <div style='padding: 10px; color: #fff; background: #bd362f;   -webkit-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
  -moz-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
  box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05); margin-bottom: 5px; width: 20%;'><input type="radio" id="jform_complete0" name="jformcomplete" class='jformcomplete' value="0" <?php echo (! $item->complete) ? "checked=\"checked\"" : ''; ?>  />&nbsp;&nbsp;<span>Project is not Completed</span></div>
                        
                    
                <div style='padding: 10px; color: #fff; background: #51a351;   -webkit-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
  -moz-box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05);
  box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.15), 0 1px 2px rgba(0, 0, 0, 0.05); width: 20%;'><input type="radio" id="jform_complete1" name="jformcomplete" class='jformcomplete' value="1" <?php echo ($item->complete) ? "checked=\"checked\"" : ''; ?> />&nbsp;&nbsp;<span>Project is Completed</span></div>
                       	    
            </div>
    	</div><br />
<?php } ?>
    <?php  echo $item->event->afterDisplayContent;
    if ($authorized) { ?><p><a href='<?php echo JRoute::_("index.php?option=com_pftasks&id=".$item->id."&view=taskform&layout=edit");?>'>Edit Task</a></p><?php } 
    if ($projerized) { ?><p><a href='<?php echo JRoute::_("index.php?option=com_pftasks&view=taskform&layout=edit");?>'>Add another Task for <?php echo $item->project_title;?></a></p><?php } ?>
        
</div>
<script><!--
   jQuery(document).ready(function () {  
        jQuery('.jformcomplete').change(function () { 
             var $fragment_refresh = {
		url: '<?php echo JUri::base(); ?>',
		type: 'POST',
		data: { option: 'com_pftasks', task: 'finishtask', taskid: '<?php echo $item->id;?>', finish : jQuery('input:radio[name=jformcomplete]:checked').val() },
		success: function( data ) { console.log(data); } };
             jQuery.ajax( $fragment_refresh );
        });
       
        
    });
--></script>