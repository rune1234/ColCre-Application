<?php
/**
 * @package      pkg_projectfork
 * @subpackage   com_pfprojects
 *
 * @author       Tobias Kuhn (eaxs)
 * @copyright    Copyright (C) 2006-2013 Tobias Kuhn. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl.html GNU/GPL, see LICENSE.txt
 */

defined('_JEXEC') or die();

jimport('projectfork.colcre.project');
JHtml::_('pfhtml.script.listform');

$list_order = $this->escape($this->state->get('list.ordering'));
$list_dir   = $this->escape($this->state->get('list.direction'));
$user       = JFactory::getUser();
$uid        = $user->get('id');

$nulldate = JFactory::getDbo()->getNullDate();

$filter_in     = ($this->state->get('filter.isset') ? 'in ' : '');
$milestones_enabled = PFApplicationHelper::enabled('com_pfmilestones');
$tasks_enabled = PFApplicationHelper::enabled('com_pftasks');
$time_enabled = PFApplicationHelper::enabled('com_pftime');
$repo_enabled  = PFApplicationHelper::enabled('com_pfrepo');
$forum_enabled = PFApplicationHelper::enabled('com_pfforum');
$users_enabled = PFApplicationHelper::enabled('com_pfusers');
$cmnts_enabled = PFApplicationHelper::enabled('com_pfcomments');

$is_ssl = JFactory::getURI()->isSSL();
?>
<div id="projectfork" class="category-list<?php echo $this->pageclass_sfx;?> view-projects">
    <?php if ($this->params->get('show_page_heading', 1)) : ?>
        <h1><?php echo $this->escape($this->params->get('page_heading')); ?></h1>
    <?php endif; ?>

    <div class="clearfix"></div>

    <div class="grid">
        <form name="adminForm" id="adminForm" action="<?php echo JRoute::_(PFprojectsHelperRoute::getProjectsRoute()); ?>" method="post">
 <?php //user should know what projects match his:
            if (is_numeric($user->id) && $user->id > 0) {
            ?><div><a id="projectInvite" href="<?php echo JRoute::_("index.php?option=com_community&view=matches&Itemid=158");?>">My Skill Matches</a></div>
            <?php
            }
            ?>
            <div class="btn-toolbar btn-toolbar-top">
                <?php echo  $this->toolbar;?>
            </div>

            <div class="clearfix"></div>
           
            <div class="<?php echo $filter_in;?>collapse" id="filters">
                <div class="btn-toolbar clearfix">
                    <div class="filter-search btn-group pull-left">
                         <input type="text" name="filter_search" placeholder="<?php echo JText::_('JSEARCH_FILTER_SEARCH'); ?>" id="filter_search" value="<?php echo $this->escape($this->state->get('filter.search')); ?>"/>
                    </div>
                    <div class="filter-search-buttons btn-group pull-left">
                        <button type="submit" class="btn" style='width: 150px; text-align: center;' rel="tooltip" title="<?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?>">
                            Search
                        </button>
                        <button type="button" class="btn" style='width: 150px; text-align: center;' rel="tooltip" title="<?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_search').value='';this.form.submit();">
                            Filter
                        </button>
                    </div>
                    <div class="filter-order btn-group pull-left">
                        <select name="filter_order" class="inputbox input-small"><!-- onchange="this.form.submit()">-->
                            <?php echo JHtml::_('select.options', $this->sort_options, 'value', 'text', $list_order, true);?>
                        </select>
                    </div>
                    <div class="folder-order-dir btn-group pull-left">
                        <select name="filter_order_Dir" class="inputbox input-small"><!-- onchange="this.form.submit()">-->
                            <?php echo JHtml::_('select.options', $this->order_options, 'value', 'text', $list_dir, true);?>
                        </select>
                    </div>
                    <div class="filter-category btn-group pull-left">
                        <select name="filter_category" class="inputbox input-small" style='width: 200px;'><!-- onchange="this.form.submit()">-->
                            <option value=""><?php echo JText::_('JOPTION_SELECT_CATEGORY');?></option>
                            <option>All</option>
                            <?php echo JHtml::_('select.options', JHtml::_('category.options', 'com_pfprojects'), 'value', 'text', $this->state->get('filter.category'));?>
                        </select>
                    </div>
                    <?php if ($this->access->get('core.edit.state') || $this->access->get('core.edit')) : ?>
                        <div class="filter-author btn-group pull-left">
                            <select name="filter_published" class="inputbox input-small" onchange="this.form.submit()">
                                <option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
                                <?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions'), 'value', 'text', $this->state->get('filter.published'), true);?>
                            </select>
                        </div>
                    <?php endif; 
                    $item_id = JRequest::getUInt('Itemid');
                    ?>
                </div>
            </div> 
          
<div id='userProfileDiv' style="width: 65%; margin: 40px;">
    
    <!--<ul id='userProfUl'><li>Most Popular</li><li><a href='<?php echo JRoute::_('index.php?option=com_pfprojects&view=projects&Itemid='.$item_id.'&extr=newweek'); ?>'>New this Week</a></li><li>Most Awarded</li><li>Most Funded</li></ul>--></div>
            <?php 
                echo (isset($_POST['filter_category']) && is_numeric($_POST['filter_category']) && isset($this->items[0]->category_title ) ) ? "<div style='margin: 0px; padding: 0px;'><h4>Category: ".$this->items[0]->category_title."</h4></div>" : ''; 
                if (! $this->items) { echo "<p><strong>There are no results</strong></p>"; }
            ?>  
            <div class="clearfix"></div> 
            <div class="row-striped" style="margin: auto; margin-top: 15px; width: 98%;">
                <div>
                    <?php 
                $k = 0;
                $current_cat = '';
                foreach($this->items AS $i => $item) :
                     
                    $access = PFprojectsHelper::getActions($item->id);
                    $link   = PfprojectsHelperRoute::getDashboardRoute($item->slug);

                    $can_edit     = $access->get('core.edit');
                    $can_checkin  = ($user->authorise('core.manage', 'com_checkin') || $item->checked_out == $uid || $item->checked_out == 0);
                    $can_edit_own = ($access->get('core.edit.own')   && $item->created_by == $uid);
                    $can_change   = ($access->get('core.edit.state') && $can_checkin);

                    // Calculate project progress
                    $task_count = (int) $item->tasks;
                    $completed  = (int) $item->completed_tasks;
 
                    // Repo directory
                    $repo_dir = (int) $this->params->get('repo_dir');

                    if ($item->progress >= 67)  $progress_class = 'info';
                    if ($item->progress == 100) $progress_class = 'success';
                    if ($item->progress < 67)   $progress_class = 'warning';
                    if ($item->progress < 34)   $progress_class = 'danger label-important';

                    // Prepare the watch button
                    if ($uid) {
                        $options = array('div-class' => '', 'a-class' => 'btn-mini');
                        $watch   = JHtml::_('pfhtml.button.watch', 'projects', $i, $item->watching, $options);
                    }
                    else {
                        $watch = '';
                    }
                ?>
                <?php /*if ($item->category_title != $current_cat && !is_numeric($this->state->get('filter.category'))) : ?>
                    <h3><?php echo $this->escape($item->category_title);?></h3>
                    <hr />

                <?php $current_cat = $item->category_title; endif;*/ ?>
                	
    	    	    	<div class="span3 pull-left projectBox"> 
                            <div style='width: 100%; margin: auto; text-align: center;'><a href="<?php echo JRoute::_($link);?>">
	    	    	    		<?php if (!empty($item->logo_img)) : ?>
	    	    	    		        <img src="<?php echo $item->logo_img;?>" width="100%" alt="<?php echo "project $item->title logo";?>" />
                                        <?php elseif (is_file(JPATH_ROOT."/templates/colcre/images/".$this->escape($item->category_alias).".jpg")) : ?>
	    	    	    		        <img src="<?php echo JUri::base()."/templates/colcre/images/".$this->escape($item->category_alias).".jpg"; ?>" width="100%" alt="<?php echo "project $item->title logo";?>" />
	    	    	    		
	    	    	    		<?php elseif (is_file(JPATH_ROOT."/templates/colcre/images/".$this->escape($item->category_alias).".png")) : ?>
	    	    	    		        <img src="<?php echo JUri::base()."/templates/colcre/images/".$this->escape($item->category_alias).".png"; ?>" width="100%" alt="<?php echo "project $item->title logo";?>" />
	    	    	    		<?php else: echo "<img src='".JUri::base()."images/foldered.jpg' alt='project $item->title logo' style='margin: auto; width: 120px; height: 120px;' />"; ?>
                                                    <?php endif ; ?>
                                </a></div><div class="projcatg"><?php echo $item->category_title;?></div>
                                <?php
                                 $pd = new projectData();//  lookupUser($id)
                                 $createdBY = $pd->lookupUser($item->created_by);
                                 //print_r($createdBY);
                                 
                                 ?>
                                <div style='padding: 10px;'>
    	    	    		<h2 class="item-title">
                                <?php if ($can_change || $uid) : ?>
                                    <label for="cb<?php echo $i; ?>" class="checkbox pull-left">
                                        <?php echo JHtml::_('pf.html.id', $i, $item->id); ?>
                                    </label>
                                <?php endif; ?>

                                <?php if ($item->checked_out) : ?>
                                    <span aria-hidden="true" class="icon-lock"></span>
                                <?php endif; ?>

                                <a href="<?php echo JRoute::_($link);?>" class="project-title">
                                    <?php echo $this->escape($item->title);?>
                                </a>
                            </h2>
                            <hr style='margin: 5px 0px;' />
                            <?php
                          //  if (!isset($createdBY)) $createdBY = new stdClass();
                            if (!isset($createdBY->username)) @$createdBY->username = '';
                            if (!isset($createdBY->thumb)) @$createdBY->thumb = '';
                            
                            ?>
                            <div class='pcreator'><img style='height: 50px;' src="<?php echo $createdBY->thumb; ?>" alt="<?php echo $createdBY->username; ?>" /><span>Creator: <a target='blank' href='<?php echo JRoute::_('index.php?option=com_community&view=profile&userid='.$item->created_by); ?>'><?php echo $createdBY->username; ?></a></span>
                                <div><a class="menu-icon" href="<?php echo JRoute::_('index.php?option=com_community&view=profile&view=projects&user_id='.$item->created_by."&Itemid=174"); ?>">Other Projects</a> by <?php echo $createdBY->username; ?></div>
                            </div>
                                  <hr style='margin: 5px 0px;' />
                                
	    	    	    	<div class="project-description"><?php echo JHtml::_('pf.html.truncate', $item->description, 150); ?></div>


    	    	    	 
    	    	    		<hr class="visible-phone" />
    	    	    		<div class="progress progress-<?php echo $progress_class;?> progress-striped progress-project">
    	    	    		    <div class="bar" style="width: <?php echo ($item->progress > 0) ? $item->progress . "%" : "24px"; ?>">
    	    	    		        <span class="label label-<?php echo $progress_class;?> pull-right"><?php echo $item->progress; ?>%</span>
    	    	    		    </div>
    	    	    		</div>
                                <!--
    	    	    		<dl class="article-info dl-horizontal">
                        		<?php if($item->start_date != $nulldate): ?>
                        			<dt class="start-title">
                        				<span class="pull-left"><?php echo JText::_('JGRID_HEADING_START_DATE'); ?>:</span>
                        			</dt>
                        			<dd class="start-data">
                                        <?php echo JHtml::_('pfhtml.label.datetime', $item->start_date); ?>
                        			</dd>
                        		<?php endif; ?>
                        		<?php if($item->end_date != $nulldate): ?>
                        			<dt class="due-title">
                        				<span class="pull-left"><?php echo JText::_('JGRID_HEADING_DEADLINE'); ?>:</span>
                        			</dt>
                        			<dd class="due-data">
                                        <?php echo JHtml::_('pfhtml.label.datetime', $item->end_date); ?>
                        			</dd>
                        		<?php endif;?>
                        		<dt class="owner-title">
                        			<span class="pull-left"><?php echo JText::_('JGRID_HEADING_CREATED_BY'); ?>:</span>
                        		</dt>
                        		<dd class="owner-data">
                                     <?php echo JHtml::_('pfhtml.label.author', $item->author_name, $item->created); ?>
                        		</dd>
                                <?php if ($item->params->get('website')) : ?>
                                    <dt class="owner-title">
                            			<span class="pull-left"><?php echo JText::_('COM_PROJECTFORK_FIELD_WEBSITE_LABEL'); ?>:</span>
                            		</dt>
                            		<dd class="owner-data">
                                        <a href="<?php echo $item->params->get('website');?>" target="_blank">
                                            <?php echo JText::_('COM_PROJECTFORK_FIELD_WEBSITE_VISIT_LABEL');?>
                                        </a>
                            		</dd>
                                <?php endif; ?>
                                <?php if ($item->params->get('email')) : ?>
                                    <dt class="owner-title">
                            			<span class="pull-left"><?php echo JText::_('COM_PROJECTFORK_FIELD_EMAIL_LABEL'); ?>:</span>
                            		</dt>
                            		<dd class="owner-data">
                                        <a href="mailto:<?php echo $item->params->get('email');?>" target="_blank">
                                            <?php echo $item->params->get('email');?>
                                        </a>
                            		</dd>
                                <?php endif; ?>
                                <?php if ($item->params->get('phone')) : ?>
                                    <dt class="owner-title">
                            			<span class="pull-left"><?php echo JText::_('COM_PROJECTFORK_FIELD_PHONE_LABEL'); ?>:</span>
                            		</dt>
                            		<dd class="owner-data">
                                        <?php echo $item->params->get('phone');?>
                            		</dd>
                                <?php endif; ?>
                        	</dl>-->
                        </div></div>
    	    	    	<!--<div class="span12 hidden-phone">
    	    	    		<div class="btn-toolbar">
    	    	    			<?php if ($can_edit || $can_edit_own) : ?>
    	    	    			<div class="btn-group">
    	    	    			    <a class="btn btn-mini" href="<?php echo JRoute::_('index.php?option=com_pfprojects&task=form.edit&id=' . $item->slug);?>">
    	    	    			        <span aria-hidden="true" class="icon-pencil"></span> <?php echo JText::_('COM_PROJECTFORK_ACTION_EDIT'); ?>
    	    	    			    </a>
    	    	    			</div>
    	    	    			<?php endif; ?>

	    	    				<?php if ($cmnts_enabled) : ?>
	    	    				<div class="btn-group">
	    	    					<a class="btn btn-mini" href="<?php echo JRoute::_($link);?>#comments">
	    	    			       	 <span aria-hidden="true" class="icon-comment"></span> <?php echo $item->comments; ?> <?php echo JText::_('Comments'); ?>
	    	    			        </a>
	    	    			    </div>
	    	    				<?php endif; ?>
	    	    				<?php if ($milestones_enabled) : ?>
    	    	    				<div class="btn-group">
    	    	    			        <a class="btn btn-mini" href="<?php echo JRoute::_(PFmilestonesHelperRoute::getMilestonesRoute($item->slug, $item->slug));?>">
    	    	    			            <span aria-hidden="true" class="icon-location"></span>
    	    	    			            <?php echo (int) $item->milestones;?> <?php echo JText::_('JGRID_HEADING_MILESTONES'); ?>
    	    	    			        </a>
    	    	    				</div>
    	    	    			<?php endif; ?>
    	    	    			<?php if ($tasks_enabled) : ?>
    	    	    				<div class="btn-group">
    	    	    			        <a class="btn btn-mini" href="<?php echo JRoute::_(PFtasksHelperRoute::getTasksRoute($item->slug));?>">
    	    	    			            <span aria-hidden="true" class="icon-list-view"></span>
    	    	    			            <?php echo (int) $item->tasklists;?> <?php echo JText::_('JGRID_HEADING_TASKLISTS'); ?>
    	    	    			        </a>
    	    	    				</div>
    	    	    			<?php endif; ?>
    	    	    			<?php if ($tasks_enabled) : ?>
	    	    	    			<div class="btn-group">
	                                    <a class="btn btn-mini" href="<?php echo JRoute::_(PFtasksHelperRoute::getTasksRoute($item->slug));?>">
	                                        <span aria-hidden="true" class="icon-checkmark"></span>
	                                        <?php echo (int) $item->tasks;?> <?php echo JText::_('JGRID_HEADING_TASKS'); ?>
	                                    </a>
	    	    	    			</div>
    	    	    			<?php endif; ?>
    	    	    			<?php if ($repo_enabled) : ?>
    	    	    				<div class="btn-group">
    	    	    			        <a class="btn btn-mini" href="<?php echo JRoute::_(PFrepoHelperRoute::getRepositoryRoute($item->slug, $repo_dir));?>">
    	    	    			            <span aria-hidden="true" class="icon-flag-2"></span>
    	    	    			            <?php echo (int) $item->attachments;?> <?php echo JText::_('COM_PROJECTFORK_FIELDSET_ATTACHMENTS'); ?>
    	    	    			        </a>
    	    	    				</div>
    	    	    			<?php endif; ?>
    	    	    			<?php echo $watch; ?>
    	    	    		</div>
    	    	    	</div>-->
    	    	    

                <?php
                    $k = 1 - $k;
                    endforeach;
                ?>
                        </div>
                </div>
<div style='clear: both; width: 90%;'>
        	<?php if ($this->pagination->get('pages.total') > 1) : ?>
    <br /><div class="pagination center">
        	        <?php echo $this->pagination->getPagesLinks(); ?>
        	    </div>
        	    <p class="counter center"><?php echo $this->pagination->getPagesCounter(); ?></p>
        	<?php endif; ?>
            <div class="filters center">
            	<span class="display-limit">
            	    <?php echo $this->pagination->getLimitBox(); ?>
            	</span>
            </div>
</div>
            <input type="hidden" id="boxchecked" name="boxchecked" value="0" />
            <input type="hidden" name="task" value="" />
            <?php echo JHtml::_('form.token'); ?>
        </form>
    </div>
</div>
