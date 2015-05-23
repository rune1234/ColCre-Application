<?php
/**
 * @package      pkg_projectfork
 * @subpackage   com_projectfork
 *
 * @author       Tobias Kuhn (eaxs)
 * @copyright    Copyright (C) 2006-2013 Tobias Kuhn. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl.html GNU/GPL, see LICENSE.txt
 */

defined('_JEXEC') or die();
function lookupIcon($task)
        {
            $key = (int) $task->id;
             //print_r($task);

            // Default - Projectfork avatar
            $base_path = JPATH_ROOT . '/media/com_projectfork/repo/0/logo';
            $base_url  = JURI::root(true) . '/media/com_projectfork/repo/0/logo';
            $img_path  = NULL;
   
            if (JFile::exists($base_path . '/' . $key . '.jpg')) {
                $img_path = $base_url . '/' . $key . '.jpg';
            }
            elseif (JFile::exists($base_path . '/' . $key . '.jpeg')) {
                $img_path = $base_url . '/' . $key . '.jpeg';
            }
            elseif (JFile::exists($base_path . '/' . $key . '.png')) {
                $img_path = $base_url . '/' . $key . '.png';  
            }
            elseif (JFile::exists($base_path . '/' . $key . '.gif')) {
                $img_path = $base_url . '/' . $key . '.gif';
            }
            else {  //echo JPATH_ROOT."/templates/colcre/images/".$task->category_alias.".png"; 
                 if ($task->category_alias && is_file(JPATH_ROOT."/templates/colcre/images/".$task->category_alias.".jpg"))  
                $img_path = JUri::base()."/templates/colcre/images/".$task->category_alias.".jpg"; 
                elseif ($task->category_alias && is_file(JPATH_ROOT."/templates/colcre/images/".$task->category_alias.".png"))  
                $img_path = JUri::base()."/templates/colcre/images/".$task->category_alias.".png";       
               else return JUri::base()."/images/foldered.jpg";
                  
            }
//echo $img_path;
            return $img_path;
 
        }
function showMatches($matches, $item, $bla, $task = '')
{
    $userMatches = array();
    $shownMatches = false;
     if ($matches){
         foreach ($matches as $match)
         {
             if ($task != '') { if ($match->MatchingTaskId != $task->id) {   continue;} }
             else $shownMatches = true;
             $userMatches[$match->user_id][] = $match->SkillName;
         }
         $avoidRepeat = array();
         foreach ($matches as $match)
         {
             if ($task != '') { if ($match->MatchingTaskId != $task->id) continue; }
             //if (isset($avoidRepeat[$match->MatchingTaskId])) continue;
             $avoidRepeat[$match->MatchingTaskId] = true;
             if (isset($userMatches[$match->user_id]) && is_array($userMatches[$match->user_id]))
             {
                 $matchesImplode = implode(', ', $userMatches[$match->user_id]);
                 unset($userMatches[$match->user_id]);//let's get rid of the array, so we don't show the same users over and over again
             }
             else continue;
             echo "<div class='row-fluid'><div class='span10 userMatch' style='font-size: 10px; margin-left: 12px;'>";
//print_r($match);
             $spec = $bla->specifyMatch($item->text, $match->MatchingTaskId, $item->id, $match->user_id, $match->MatchingTaskId);
             //print_r($spec);
             if (is_array($spec))
             {
                 //print_r($spec);
             }
             $avatar = getAvatarThumb($match->user_id);
             if ($avatar)
             {
                 echo "<img src='$avatar' alt='user avatar' style='float: left; margin-right: 5px;' />";
             }
             echo "<p><b>Name:</b> <a href='".JRoute::_('index.php?option=com_community&view=profile&userid='.$match->user_id.'&Itemid=103')."'>".ucwords($match->DeveloperName)."</a>";
             echo "<br /><b>SkillName:</b> ".ucwords($matchesImplode);//ucwords($match->SkillName);
             echo "<br /><b>Task Percentage:</b> ".$match->TaskMatchPercentage."%";
             echo "<br /><b>Project Match Percentage:</b> ".$match->ProjectMatchPercentage."%";

             echo "</p>";

             echo "</div><div style='clear: both;'></div></div>";
         }
         echo "<div style='clear: both;'></div>";
         return $shownMatches;
     }
}
function getAvatarThumb($userid)
{
    if (!is_numeric($userid) || $userid == 0) return;
      $query = "SELECT thumb FROM #__community_users WHERE userid = $userid LIMIT 1";
      $db = JFactory::getDbo();
      $db->setQuery($query);
      $avatar = $db->loadResult();
      if (!$avatar) return false;
      return JURI::root()."/".$avatar;
}
function proposalExists($userid, $projectid)
{
    if (!is_numeric($userid) || !is_numeric($projectid)) return false;
    $query = "SELECT * FROM #__pf_projects_msg WHERE user_id = $userid AND project_id = $projectid LIMIT 1";
    $db = JFactory::getDbo();
    $row = $db->setQuery($query)->loadObject();
    return ($row) ? $row : false;
}
// Create shortcuts
$item    = $this->item;
$params  = $this->params;
$state   = $this->state;
$modules = $this->modules;

$nulldate = JFactory::getDbo()->getNullDate();

$details_in     = ($state->get('project.request') ? 'in ' : '');
$details_active = ($state->get('project.request') ? ' active' : '');
$user = JFactory::getUser();

$proDat = new projectData();   
$proCreator = $proDat->lookupUser($item->created_by);
?>
<div id="projectfork" class="category-list<?php echo $this->pageclass_sfx;?> view-dashboard projectFrame" style="padding: 10px; margin: 10px;">

     <div id="projprof">
                <a style="float: left; margin: 5px;" href="<?php echo JRoute::_('index.php?option=com_community&view=profile&userid='.$item->created_by); ?>"> 
                    <img src="<?php echo $proCreator->thumb;?>" alt="<?php echo $proCreator->username; ?>" /></a>
                 
                
                 <a class="menu-icon" href="<?php echo JRoute::_('index.php?option=com_community&view=profile&view=projects&user_id='.$proCreator->id); ?>">Other Projects</a> by 
                     <a href="<?php echo JRoute::_('index.php?option=com_community&view=profile&userid='.$item->created_by); ?>">
                     <?php echo $proCreator->username; ?></a>
        <?php
        
        ?>
                 <div style="clear: both;"></div>
            </div>
    
    <?php if ($params->get('show_page_heading', 1)) : ?>
        <h1><?php echo $this->escape($params->get('page_heading')); ?></h1>
    <?php endif; 
    $likedata = array();
                $likedata['userid'] = 45;
                $likedata['typeid'] = 666;
                
    ?>    
        
        <div ng-app="myLikes" >
            
            <div ng-controller="projectLike" data-ng-init="getLikes(<?php echo $item->id; ?>, <?php echo $user->id; ?>)">
                <div ng-click="like(<?php echo $user->id;?>, <?php echo $item->id; ?>)" class="likemain">
                    
                    <div class="likelayer"></div><br /><span>Like</span> | <span style="color: #000; font-size: 12px;"><span ng-bind="likes"></span> Likes</span></div>
                    
                <div style="float: left; margin-left: 150px; margin-top: -33px;"><a href="#comments" style="color: #a00;"><img src="<?php echo JURI::base()."images/comicon.jpg";?>" alt="comment icon" style="height: 45px;"/>Comments</a></div>
                  
                   
               <div style="clear: both"></div>
               <br /> 
            </div>
        </div>
     <div class="cat-items">

        <form id="adminForm" name="adminForm" method="post" action="<?php echo JRoute::_(PFprojectsHelperRoute::getDashboardRoute($state->get('filter.project'))); ?>">

        	<?php if( 1 == 2 && $state->get('filter.project') && !empty($item)) : //redacron alteration. The details button is gone ?>
                <div class="btn-group pull-right">
    			    <a data-toggle="collapse" data-target="#project-details" class="btn<?php echo $details_active;?>">
                        <?php echo JText::_('COM_PROJECTFORK_DETAILS_LABEL'); ?> <span class="caret"></span>
                    </a>
    			</div>
            <?php endif; ?>

            <div class="btn-toolbar btn-toolbar-top">
                <?php echo $this->toolbar;?> 
                <?php  echo JHtml::_('pfhtml.project.filter');?>
            </div> 
 
            <?php 
            $proj_logo = lookupIcon($item) ? lookupIcon($item) : JUri::base()."images/foldered.jpg";
                         
            if($item) echo $item->event->afterDisplayTitle; ?>

            <input type="hidden" name="task" value="" />
	        <?php echo JHtml::_('form.token'); ?>

            <div class="clearfix"></div>

            <?php if ($item) echo $item->event->beforeDisplayContent; ?>

            <?php if($state->get('filter.project') && !empty($item)) : ?>
                <div  id="project-details"><?php // I got rid of this: class="<(?)php echo $details_in;(?)> collapse"?>
                    <div>
                        <div class="item-description">

                            <?php 
                           echo "<img src='$proj_logo' alt='$item->title' style='float: right;' />";  
                            echo nl2br($item->text)."<br /><br />"; 
                                 
                            ?>
<div class="clearfix"></div><br />

                            <dl class="article-info dl-horizontal pull-right">
                        		<?php if($item->start_date != $nulldate): ?>
                        			<dt class="start-title">
                        				<?php echo JText::_('JGRID_HEADING_START_DATE'); ?>:
                        			</dt>
                        			<dd class="start-data">
                                        <?php echo JHtml::_('pfhtml.label.datetime', $item->start_date); ?>
                        			</dd>
                        		<?php endif; ?>
                        		<?php if($item->end_date != $nulldate): ?>
                        			<dt class="due-title">
                        				<?php echo JText::_('JGRID_HEADING_DEADLINE'); ?>:
                        			</dt>
                        			<dd class="due-data">
                                        <?php echo JHtml::_('pfhtml.label.datetime', $item->end_date); ?>
                        			</dd>
                        		<?php endif;?>
                        		<dt class="owner-title">
                        			<?php echo JText::_('JGRID_HEADING_CREATED_BY'); ?>:
                        		</dt>
                        		<dd class="owner-data">
                                     <?php echo JHtml::_('pfhtml.label.author', $item->author, $item->created); ?>
                        		</dd>
                                <?php if ($item->params->get('website')) : ?>
                                    <dt class="owner-title">
                            			<?php echo JText::_('COM_PROJECTFORK_FIELD_WEBSITE_LABEL'); ?>:
                            		</dt>
                            		<dd class="owner-data">
                                        <a href="<?php echo $item->params->get('website');?>" target="_blank">
                                            <?php echo JText::_('COM_PROJECTFORK_FIELD_WEBSITE_VISIT_LABEL');?>
                                        </a>
                            		</dd>
                                <?php endif; ?>
                                <?php if ($item->params->get('email')) : ?>
                                    <dt class="owner-title">
                            			<?php echo JText::_('COM_PROJECTFORK_FIELD_EMAIL_LABEL'); ?>:
                            		</dt>
                            		<dd class="owner-data">
                                        <a href="mailto:<?php echo $item->params->get('email');?>" target="_blank">
                                            <?php echo $item->params->get('email');?>
                                        </a>
                            		</dd>
                                <?php endif; ?>
                                <?php if ($item->params->get('phone')) : ?>
                                    <dt class="owner-title">
                            			<?php echo JText::_('COM_PROJECTFORK_FIELD_PHONE_LABEL'); ?>:
                            		</dt>
                            		<dd class="owner-data">
                                        <?php echo $item->params->get('phone');?>
                            		</dd>
                                <?php endif; ?>
                                <?php if (PFApplicationHelper::enabled('com_pfrepo') && count($item->attachments)) : ?>
                                    <dt class="owner-title">
                            			<?php echo JText::_('COM_PROJECTFORK_FIELDSET_ATTACHMENTS'); ?>:
                            		</dt>
                            		<dd class="owner-data">
                                         <?php echo JHtml::_('pfrepo.attachments', $item->attachments); ?>
                            		</dd>
                                <?php endif; ?>
                        	</dl>

                            <div class="clearfix"></div>
                             
                            <?php if ($this->owner) { ?>
                            <div style="padding: 5px; float: left;"><?php echo JRoute::_('<a href="index.php?option=com_projectfork&task=prmatch&id='.$item->id.'&Itemid=124">');?><img style="height: 70px;" src="<?php JUri::base();?>images/survey_icon.gif" alt="project matches" /><?php echo ucwords($item->title).' Matches</a>' ; ?></div>
                             <div style="padding: 5px; float: left;"><?php echo JRoute::_('<a href="index.php?option=com_projectfork&task=proposals&id='.$item->id.'&Itemid=124">');?><img style="height: 70px;" src="<?php JUri::base();?>images/notepad-icon.png" alt="proposals"/><?php echo ucwords($item->title).' Proposals</a>' ; ?></div>
                             <div style="padding: 5px; float: left;"><?php echo JRoute::_('<a href="index.php?option=com_projectfork&task=userlikes&id='.$item->id.'&Itemid=124">');?><img style="height: 70px;" src="<?php JUri::base();?>images/thumbs-up-users.png" alt="user likes"/><?php echo ucwords($item->title).' Likes</a>' ; ?></div>
                             <div style="padding: 5px; float: left; padding-left: 30px;">
                        <?php echo JRoute::_('<a  data-token="'.md5($item->id."projk".$item->created_by).'" data-userid="'.$item->created_by.'" data-projtitle="'.$item->title.'" id="projDel_'.$item->id.'" href="javascript:void(0)" class="projPagDel">');?><img style="height: 60px;" src="<?php JUri::base();?>images/Delete-icon.png" alt="delete project"/>
                           <?php echo  'Delete Project</a>' ; ?> </div>
                             <div class="clearfix"></div>
                                <?php } ?>
                            <hr />
                           
<?php
 
//echo $item->id; 
if (isset($user->id) && $user->id > 0 && $item->created_by != $user->id)
{
    $row = proposalExists($user->id, $item->id);
    $submit = (! $row) ? "Submit" : "Edit Your Proposal";
    $create = (! $row) ? "Create a" : "Edit your";
    //print_r($row);
    if (!$row)
    {
        $proos = new stdClass();
        $proos->howwould = '';
        $proos->proposal = '';
        
    }
    else
    {
        $proos = $row;
        unset($row);
    }
    ?>
                            <form> <div class="alert" style="color: #555;">
                            <h3><?php echo $create;?> Proposal for <?php echo $item->title; ?>:</h3>
                            <div style="margin: 25px 0 5px;">Describe your relevant experience and qualifications.</div>
                            <div id="propoDIV"><textarea style="width: 60%;" id="proposal"><?php echo $proos->proposal; ?></textarea></div>
                            <div style="margin: 25px 0 5px;">Outline your approach to the job, or ask for more information.</div>
                            <div id="howwDIV"><textarea style="width: 60%;" id="howwould"><?php echo $proos->howwould;?></textarea></div>
                            <input type="hidden" id="project_id" value="<?php echo $item->id;?>" />
                            <input type="hidden" id="created_by" value="<?php echo $item->created_by;?>" />
                            <input type="hidden" id="user_id" value="<?php echo $user->id;?>" />
                            <p id="submitPropos"><a href="javascript:void(0)" class="btn btn-mini btn-info" onClick="msgOwner()"><?php echo $submit;?></a></p>
                                </div>
                            
                            </form>
                            <hr />
    <?php
    
}
?>
                           


<!-- Begin Dashboard Modules -->
        <?php if(count(JModuleHelper::getModules('pf-dashboard-top'))) : ?>
        <div class="row-fluid">
        	<div class="span12">
        		<?php echo $modules->render('pf-dashboard-top', array('style' => 'xhtml'), null); ?>
        	</div>
        </div>
        <?php endif; ?>


                    	</div>
                    </div>
                    
                </div>
                <div class="clearfix"></div>
            <?php endif; ?>
            <input type="hidden" name="task" value="" />
            <?php echo JHtml::_('form.token'); ?>
        </form>
 <?php   
 //index.php?option=com_pftasks&view=task&id=13&Itemid=130
 $MTCFound = false;
  if ($this->tasks){ //$this->getProjectTasks($this->item->id);
      echo "<h3>Project Tasks</h3>";
     foreach ($this->tasks as $task)
     {
         echo "<div class='row-fluid'><div class='span10 userMatch'>";
          //print_r($task);//MatchingTaskId
         echo "<p><b>Title:</b> <a href=".JRoute::_('index.php?option=com_pftasks&view=task&id='.$task->id.'&Itemid=130').">".ucwords($task->title)."</a>";
         echo "<br />".$task->description;
         $shownMatches = showMatches($this->matches, $item, $this, $task);
         //if ($this->matches) { $MTCFound = true; }
         echo "</div><div style='clear: both;'></div></div>";
         
     }
     echo "<div style='clear: both;'></div>";
     
 } 
/*
 if (! $MTCFound )
 {
      showMatches($this->matches, $item, $this);
 }*/
 
 ?>
        
        <?php if(count(JModuleHelper::getModules('pf-dashboard-left')) || count(JModuleHelper::getModules('pf-dashboard-right'))) : ?>
        <div class="row-fluid">
        	<div class="span6">
        		<?php echo $modules->render('pf-dashboard-left', array('style' => 'xhtml'), null); ?>
        	</div>
        	<div class="span6">
        		<?php echo $modules->render('pf-dashboard-right', array('style' => 'xhtml'), null); ?>
        	</div>
        </div>
        <?php endif; ?>
        <?php if(count(JModuleHelper::getModules('pf-dashboard-bottom'))) : ?>
        <div class="row-fluid">
        	<div class="span12">
        		<?php echo $modules->render('pf-dashboard-bottom', array('style' => 'xhtml'), null); ?>
        	</div>
        </div>
        <?php endif; ?>
        <!-- End Dashboard Modules -->
 
        <?php if ($item) echo $item->event->afterDisplayContent; ?>

	</div>
        <div id="bottomcom"><!--used for comments scroll---></div>
</div>
 <div id="dialog" title="Delete Project" data-jsondel='' data-url='<?php echo JRoute::_("index.php?option=com_community&view=projects&Itemid=158");?>'>
     <p>Do you really want to delete Project <span id='dialogtitle'></span>?</p>
     <div style='width: 100%; text-align: center;'><input type='button' value='Yes' class='projPagDelYes' style='padding: 5px; background: #fff; width: 70px;' /> | <input type='button' value='No' class='projDelClos' style='padding: 5px; background: #fff; width: 70px;' />
</div></div>
 <?php
   $document = JFactory::getDocument();
    $js = "var projectURL = '".JURI::root()."';";
    $document->addScriptDeclaration($js);
   // $document->addCustomTag('<script src="'.JURI::root().'components/com_pfprojects/js/pfp.js" type="text/javascript"></script>');
 ?>