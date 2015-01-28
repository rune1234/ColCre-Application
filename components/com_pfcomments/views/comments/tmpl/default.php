<?php
/**
 * @package      Projectfork
 * @subpackage   Comments
 *
 * @author       Tobias Kuhn (eaxs)
 * @copyright    Copyright (C) 2006-2012 Tobias Kuhn. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl.html GNU/GPL, see LICENSE.txt
 */

defined('_JEXEC') or die();


$state = $this->state;
jimport('projectfork.colcre.project');
jimport('projectfork.colcre.likes');
$cp = new colcrePermissions();
$context = JRequest::getCmd('filter_context');

if ($context)
{
    $context = explode('.', $context);
    $context = $context[1];
}
$item_id = JRequest::getUint('filter_item_id');
$access = $cp->projectAccess($item_id, $context);
if ($access) {
?>
<form class="form-validate" id="commentForm" name="commentForm" method="post" action="<?php echo JRoute::_('index.php?option=com_pfcomments&view=comments'); ?>">
    <h4><span id="comment_count"><?php echo count($this->items); ?></span><?php echo ' ' . JText::_('COM_PROJECTFORK_COMMENTS'); ?></h4>
    <hr />

    <ul class="unstyled" id="comment-node-0">
        <?php echo $this->loadTemplate('items'); ?>
    </ul>

    <?php
    jimport('projectfork.controller.form.json');//redacron alterations. We are working with our permissions now
    
    $state->get('filter.project');
    $pfc = new PFControllerFormJson();
    $user = JFactory::getUser();
    if ($pfc->comProjAccess($state->get('filter.project'), $user->id))
    {echo $this->loadTemplate('editor');}
    
       // endif;
    ?>

    <input type="hidden" id="jform_context" name="jform[context]" value="<?php echo $this->escape($state->get('filter.context')); ?>" />
    <input type="hidden" id="jform_item_id" name="jform[item_id]" value="<?php echo $this->escape($state->get('filter.item_id')); ?>" />
    <input type="hidden" id="jform_project_id" name="jform[project_id]" value="<?php echo $this->escape($state->get('filter.project')); ?>" />
    <input type="hidden" id="jform_id" name="jform[id]" value="0" />
    <input type="hidden" id="jform_parent_id" name="jform[parent_id]" value="0" />
    <input type="hidden" name="task" value="commentform.apply" />
    <input type="hidden" name="format" value="json" />
    <?php echo JHtml::_('form.token'); ?>
</form>
<?php
}
?>