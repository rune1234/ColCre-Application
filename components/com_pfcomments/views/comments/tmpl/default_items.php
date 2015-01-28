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

$user     = JFactory::getUser();
$ul_open  = false;
$level    = 1;
$uid      = $user->get('id');
$php53    = version_compare(PHP_VERSION, '5.3', 'ge');
?>
<?php
foreach($this->items AS $i => $item) :
    if ($item->level > $level) :
        $ul_open = true;
        ?>
        <li id="comment-node-<?php echo $item->id;?>">
            <ul class="unstyled offset1">
        <?php
    elseif ($item->level < $level) :
        if ($item->level == 1) $ul_open = false;
        $tmp_level = $level;
        while($tmp_level > $item->level)
        {
            ?>
                </ul>
            </li>
            <?php
            $tmp_level--;
        }
    endif;
    $level = $item->level;
    $can_create = $this->access->comments($item, 'create');//$this->access->get('core.create');
    $can_trash  = ( $this->access->comments($item, 'delete') );//($this->access->get('core.edit.state') || ($item->created_by == $uid));
    ?>
    <li id="comment-item-<?php echo $i; ?>">
        <div class="comment-item">
	        <div class="row-fluid">
	            <div class="span1">
                    <a href="#"><img class="thumbnail" width="90" src="<?php echo JHtml::_('projectfork.avatar.path', $item->created_by);?>" alt="" /></a>
                </div>
                <div class="span11">
	                <div class="comment-content">
	                    <blockquote>
	                    	<span class="item-date small pull-right">
			                    <?php echo JHtml::date($item->created); ?>
			                </span>
                            <?php
                                if ($php53) {
                                    $item->description = $this->linkify($item->description);
                                }

                                echo nl2br($item->description);
                            ?>
                            <small><cite title="<?php echo $item->author_name; ?>"><?php echo $item->author_name; ?></cite></small>
	                        <div class="btn-group comment-item-actions">
                                    <?php if (! $can_create) { $style= 'style="display: none;"'; } else { $style = '';} //redacron alteration. The grid.id must always be there. ?>
                                    <a class="btn btn-mini btn-add-reply" href="javascript:void(0)" <?php echo $style;?>>
                                        <i class="icon-comment"></i> <?php echo JText::_('COM_PROJECTFORK_ACTION_REPLY'); ?>
    	                            </a>
                                    <div style="display: none !important;">
                                        <?php echo JHtml::_('grid.id', $i, $item->id); ?>
                                    </div>
                                <?php //endif; ?>
                                <?php if (! $can_trash) { $style= 'style="display: none;"'; } else { $style = '';}  ?>
    	                            <a class="btn btn-mini btn-trash-reply" href="javascript:void(0);" <?php echo $style;//redacron alteration. The grid.id must always be there.?> >
                                        <i class="icon-remove"></i> <?php echo JText::_('COM_PROJECTFORK_ACTION_DELETE'); ?>
    	                            </a>
                                     
                                <?php //endif; ?>
	                        </div>
	                    </blockquote>
	                </div>
                </div>
	        </div>
        </div>
    </li>
    <?php
endforeach;

if ($ul_open) {
    while($level > 1)
    {
        ?>
            </ul>
        </li>
        <?php
        $level--;
    }
    $ul_open = false;
}
