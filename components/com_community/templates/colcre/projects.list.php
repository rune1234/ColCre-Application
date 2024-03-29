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
?>
<div class="cLayout cMail Inbox">
<?php if (1==2) { ?>    
    <!--
<ul class="cMailBar cResetList cFloatedList clearfix">
	<li>
		<b class="cMail-MasterCheck">
			<input type="checkbox" name="select" class="checkbox jomNameTips" onclick="checkAll();" id="checkall" title="<?php echo JText::_('COM_COMMUNITY_INOBOX_SELECT_ALL'); ?>" />
		</b>
	</li>
	<li>
		<div class="btn-group">
			<?php if ( !JRequest::getVar('task') == 'sent' ) { ?>
				<a class="btn btn-small" href="javascript:void(0);" onclick="setAllAsRead();"><?php echo JText::_('COM_COMMUNITY_INBOX_MARK_READ'); ?></a>&nbsp;&nbsp;&nbsp;
				<a class="btn btn-small" href="javascript:void(0);" onclick="setAllAsUnread();"><?php echo JText::_('COM_COMMUNITY_INBOX_MARK_UNREAD'); ?></a>&nbsp;&nbsp;&nbsp;
				<a class="btn btn-small" href="javascript:void(0);" onclick="joms.messaging.confirmDeleteMarked('inbox');"><?php echo JText::_('COM_COMMUNITY_INBOX_REMOVE_MESSAGE'); ?></a>&nbsp;
			<?php } else { ?>
				<a class="btn btn-small" href="javascript:void(0);" onclick="joms.messaging.confirmDeleteMarked('sent');"><?php echo JText::_('COM_COMMUNITY_INBOX_REMOVE_MESSAGE'); ?></a>&nbsp;
			<?php } ?>
		</div>
	</li>
</ul>
--->
<?php } 
$userid = $user->_userid;
?>
<table id="inbox-listing" class="table table-hover" >
	<?php
         $base_path = JPATH_ROOT . '/media/com_projectfork/repo/0/logo';
         $base_url  = JURI::root(true) . '/media/com_projectfork/repo/0/logo';
         
                 require_once( JPATH_ROOT .'/libraries/projectfork/colcre/project.php' ); 
                 $projectData =  new projectData(); 
                 
        
     echo "<h3>".ucwords($user->username)." Projects:</h3>";
        foreach ( $matches as $match ) :  ?>
	<tr  id="projtrDel_<?php echo $match->id;?>">
		<td style="background: #fff;">
		 <?php  
                 $projectInfo = new stdClass();
                 $projectInfo->id = ($match && isset($match->project_id)) ? $match->project_id : '';
                 $category = $projectData->getCategory($match->catid);
                 $projectInfo->category_alias = isset($category->alias) ? $category->alias : '';
                  $match->logo_img = null;
     
            if (JFile::exists($base_path . '/' . $match->id . '.jpg')) {
                $match->logo_img = $base_url . '/' . $match->id . '.jpg';
            }
            elseif (JFile::exists($base_path . '/' . $match->id . '.jpeg')) {
                $match->logo_img = $base_url . '/' . $match->id . '.jpeg';
            }
            elseif (JFile::exists($base_path . '/' . $match->id . '.png')) {
                $match->logo_img = $base_url . '/' . $match->id . '.png';
            }
            elseif (JFile::exists($base_path . '/' . $match->id . '.gif')) {
                $match->logo_img = $base_url . '/' . $match->id . '.gif';
            }
             echo "<img src='".$projectData->lookupIcon($projectInfo)."' alt='project $match->title logo' style='height: 150px; width: 150px;' />";// : "<img src='images/foldered.jpg' alt='project $match->title logo' style='width: 150px; height: 150px;' />";
                 ?>
		</td>
		<td style="background: #fff;"><?php //print_r($match);
                echo '<div class="span8">';
                echo "<p><a href='".JRoute::_("index.php?option=com_projectfork&view=dashboard&id=".$match->id."&Itemid=124")."'>".ucwords($match->title)."</a></p>";
                echo "<p>".substr($match->description, 0, 300)."...</p>";
                
                if ($match->created) { echo "<p style='font-size: 10px;'>Created: ".date('M-d-Y', (strtotime($match->created)))."</p>"; }
                echo "</div>\n";
                ?>
                    <div style="clear: both;"></div>
                    <div class="pull-right small"></div>
                    <?php
                    if ($match->created_by == $userid):
                    ?>
                    <div class="projectDelete" id="projDel_<?php echo $match->id;?>" data-token='<?php echo md5($match->id."projk".$match->created_by); ?>' data-userid='<?php echo $match->created_by; ?>' data-projtitle='<?php echo $match->title;?>'>Delete Project</div>
                    <?php endif; ?>
		</td>
	</tr>
	<?php endforeach; ?>
</table>
 <div id="dialog" title="Delete Project" data-jsondel=''>
     <p>Do you really want to delete Project <span id='dialogtitle'></span>?</p>
     <div style='width: 100%; text-align: center;'><input type='button' value='Yes' class='projDelYes' style='padding: 5px; background: #fff; width: 70px;' /> | <input type='button' value='No' class='projDelClos' style='padding: 5px; background: #fff; width: 70px;' />
</div></div>
 
 
 
<?php
if ( $pagination )
{
?>
<div class="cPagination">
	<?php echo $pagination; ?>
</div>
<?php
}
if (1 == 2) {
?>
<script type="text/javascript">/*
function checkAll()
{
	joms.jQuery("#inbox-listing INPUT[type='checkbox']").each( function() {
		if ( joms.jQuery('#checkall').attr('checked') )
			joms.jQuery(this).attr('checked', true);
		else
			joms.jQuery(this).attr('checked', false);
	});
	return false;
}
function checkSelected()
{
	var sel;
	sel = false;
	joms.jQuery("#inbox-listing INPUT[type='checkbox']").each( function() {
		if ( !joms.jQuery(this).attr('checked') )
			joms.jQuery('#checkall').attr('checked', false);
	});
}
function markAsRead( id )
{
	joms.jQuery('#message-'+id).removeClass('unread');
	joms.jQuery('#message-'+id).addClass('read');
	joms.jQuery('#new-message-'+id).hide();
	joms.jQuery("#message-"+id+" INPUT[type='checkbox']").attr('checked', false);
	joms.jQuery('#checkall').attr('checked', false);
}
function markAsUnread( id )
{
	joms.jQuery('#message-'+id).removeClass('read');
	joms.jQuery('#message-'+id).addClass('unread');
	joms.jQuery('#new-message-'+id).show();
	joms.jQuery("#message-"+id+" INPUT[type='checkbox']").attr('checked', false);
	joms.jQuery('#checkall').attr('checked', false);
}
function setAllAsRead()
{
	joms.jQuery("#inbox-listing INPUT[type='checkbox']").each( function() {
		if ( joms.jQuery(this).attr('checked') ) {
			if ( joms.jQuery('#message-'+joms.jQuery(this).attr('value')).hasClass('unread') ) {
				jax.call( 'community', 'inbox,ajaxMarkMessageAsRead', joms.jQuery(this).attr('value') );
			}
		}
	});
}
function setAllAsUnread()
{
	joms.jQuery("#inbox-listing INPUT[type='checkbox']").each( function() {
		if ( joms.jQuery(this).attr('checked') )
			if ( joms.jQuery('#message-'+joms.jQuery(this).attr('value')).hasClass('read') ) {
				jax.call( 'community', 'inbox,ajaxMarkMessageAsUnread', joms.jQuery(this).attr('value') );
			}
	});
}*/
</script>
<?php
}
    $document = JFactory::getDocument();
    $js = "var projectURL = '".JURI::root()."';";
    $document->addScriptDeclaration($js);
    
$document->addCustomTag('<script src="'.JURI::root().'libraries/projectfork/js/jQuery.js" type="text/javascript"></script>');
$document->addCustomTag('<script src="'.JURI::root().'libraries/projectfork/js/jquery-ui.dialog.js" type="text/javascript"></script>');
$document->addCustomTag('<script src="'.JURI::root().'components/com_pfprojects/js/pfp.js" type="text/javascript"></script>');


  
    
?>
 
</div>
