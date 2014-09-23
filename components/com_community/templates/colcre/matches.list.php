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
    <?php echo "<div style='margin: 5px;'><b>Matching Tasks: ".$totalMatches."</b></div>";?>
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
<table id="inbox-listing" class="table table-hover" >
	<?php foreach ( $matches as $match ) : ?>
	<tr>
		<td class="js-mail-checkbox">
		 
		</td>
		<td><?php //print_r($match);
                 require_once( JPATH_ROOT .'/libraries/projectfork/colcre/project.php' ); 
                 $projectData =  new projectData(); 
                echo '<div class="span9 pull-left" style="background: #fff;">';
              //  print_r($match);
                $category = $projectData->getCategory($match->catid);
                $project = new stdClass();
                $project->id = $match->project_id;
                $project->category_alias = $category->alias;
                echo "<div style='float: left; margin: 10px;'><img alt='".$match->task_title."' src='".$projectData->lookupIcon($project)."' /></div>";
                echo "<p><a href='".JRoute::_("index.php?option=com_projectfork&view=dashboard&id=".$match->project_id."&Itemid=124")."'>".ucwords($match->title)."</a></p>";
                echo "<p>".$match->description."</p>";
                echo "<p>Task: <b>".$match->task_title."</b></p>";
                echo "<p>Skill Needed: ".$match->skill."</p>";
                echo "<p>Task Match Percentage: ".$match->TaskMatchPercentage."%";
                echo "<p>Project Match Percentage: ".$match->ProjectMatchPercentage."%";
                 echo "<p>Skill Description Score: ".$match->MatchAgainst."%</p>";
                if ($match->created) { echo "<p style='font-size: 10px;'>Created: ".date('M-d-Y', (strtotime($match->created)))."</p>"; }
                echo "</div>\n"
                ?>
                    <div class="pull-right small"></div>
		</td>
	</tr>
	<?php endforeach; ?>
</table>

<?php
if ( $pagination )
{
?>
<div class="cPagination">
	<?php echo $pagination; ?>
</div>
<?php
}
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
</div>
