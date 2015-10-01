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
                            <dt>Tags:</dt>
        		 
                            <dd><?php 
                            $skillComm = array();
                            foreach ($this->skillsNeeded  as $tskl) { $skillComm[] = $tskl->skill; }
                            echo implode(', ', $skillComm);
                            ?></dd></div> 
            <?php endif; 
             if (isset($item->start_date) && $item->start_date != '0000-00-00 00:00:00') {
            ?>
            <div style="margin: 10px 0;"> 
                            <dt>Start Date:</dt>
        		 
                            <dd><?php 
                            $unixStart = strtotime(str_replace('-', '/', $item->start_date));
                            $itestart_date = date('M-d-Y H:i:s', $unixStart);
                         echo $itestart_date;
                            ?></dd></div> <?php } 
             if (isset($item->end_date) && $item->end_date != '0000-00-00 00:00:00') {
            ?>
            <div style="margin: 10px 0;"> 
                            <dt>End Date:</dt>
        		 
                            <dd><?php 
                            $unixStart = strtotime(str_replace('-', '/', $item->end_date));
                            $itestart_date = date('M-d-Y H:i:s', $unixStart);
                         echo $itestart_date;
                            ?></dd></div> <?php } 
          
             if (isset($item->rate)) {
            ?>
            <div style="margin: 10px 0;"> 
                            <dt>Rate:</dt>
        		 
                            <dd><?php 
                           echo "$".$item->rate." per hour";
                            ?></dd></div> <?php } ?>
            
            
          
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
                         //print_r($item);
                         echo "<a target='self' href='".JRoute::_('index.php?option=com_community&view=profile&view=projects&user_id='.$item->created_by)."'>".$item->author."</a>";
                          //echo JHtml::_('pfhtml.label.author', $item->author, $item->created); ?>
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
                 <?php if ($authorized) { ?>
                 
               <div style='margin-top: 40px;'><a style="float: left; margin-left: 2%; width: 30%;" class="thumbnail btn" href='<?php echo JRoute::_("index.php?option=com_pftasks&id=".$item->id."&view=taskform&layout=edit");?>'><img src="<?php echo JUri::base();?>media/com_projectfork/projectfork/images/header/icon-48-config.png" alt="Edit Task">  Edit Task</a>
                <?php   echo JRoute::_('<a style="float: left; margin-left: 2%; width: 30%;" class="thumbnail btn taskDel" title="Delete Project" data-userid="'.$item->created_by.'" data-token="'.md5($item->id."taskjk".$item->created_by).'" data-url="'.JRoute::_("index.php?option=com_projectfork&view=dashboard&id=".$item->project_id."&Itemid=124").'" id="taskDel_'.$item->id.'" href="javascript:void(0)">');?><img style="height: 50px;" src="<?php JUri::base();?>images/Delete-icon.png" alt="delete project"/>Delete Task</a>
              
                    <?php } 
    if ($projerized) { ?><a style="float: left; margin-left: 2%; width: 30%;" class="thumbnail btn" href='<?php echo JRoute::_("index.php?option=com_pftasks&view=taskform&layout=edit");?>'><img src="<?php echo JUri::base();?>/media/com_projectfork/projectfork/images/header/icon-48-taskform.add.png" alt="Add Task"> Add another Task for <?php echo $item->project_title;?></a></div><div style='clear: both;'></div><?php } ?>       	    
                        
            </div>
    	</div><br />
<?php } ?>
    <?php  echo $item->event->afterDisplayContent;
   
        ?>
</div>
<?php
$document = JFactory::getDocument();
$document->addCustomTag('<script src="'.JURI::root().'libraries/projectfork/js/jquery-ui.dialog.js" type="text/javascript"></script>');
    $document->addScript(JURI::root() . 'components/com_pfprojects/js/angpfp.js');
   
?>
<div id="dialog" data-jsondel=''>
     <p>Do you really want to delete this task?</p>
     <div style='width: 100%; text-align: center;'>
         <input type='button' value='Yes' class='TaskDelYes' style='padding: 5px; background: #fff; width: 70px;' /> | <input type='button' value='No' class='projDelClos' style='padding: 5px; background: #fff; width: 70px;' />
</div></div>
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
 <?php
   $document = JFactory::getDocument();
   $document->addCustomTag('<script src="'.JURI::root().'components/com_pfprojects/js/pfp.js" type="text/javascript"></script>');
 ?>