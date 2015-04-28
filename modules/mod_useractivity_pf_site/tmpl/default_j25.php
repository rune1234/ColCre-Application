<?php
/**
 * @package      mod_useractivity_pf_site
 *
 * @author       Tobias Kuhn (eaxs)
 * @copyright    Copyright (C) 2013 Tobias Kuhn. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl.html GNU/GPL, see LICENSE.txt
 */

defined('_JEXEC') or die();
class JomSocialLib
{
     public $dcron;
     public function __construct()
     {
         $this->dcron = JFactory::getDbo();
     }
     public function profileURL($userid)
     {
         return JRoute::_('index.php?option=com_community&view=profile&userid='.$userid);
     }
        public function getAvatarThumb($userid)
     {
          $query = "SELECT thumb FROM #__community_users WHERE userid = $userid LIMIT 1";
                    
          $avatar = $this->dcron->setQuery($query)->loadResult();
          
          
          if (is_file(JPATH_BASE."/".$avatar))  { return JURI::root()."".$avatar; }
          else { return JURI::base().'components/com_community/assets/user-Male-thumb.png'; }
    }
     
}

if ($params->get('load_jquery', 1)) {
    JHtml::_('script', 'com_useractivity/jquery.min.js', false, true, false, false, false);
    JHtml::_('script', 'com_useractivity/jquery.noconflict.js', false, true, false, false, false);
}

JHtml::_('script', 'com_useractivity/jquery.module.js', false, true, false, false, false);

$id    = (int) $module->id;
$limit = (int) $params->get('list_limit');
$count = count($data['items']);
$start = 0;

$com_params  = JComponentHelper::getParams('com_useractivity');
$date_rel    = $params->get('date_relative', $com_params->get('date_relative', 1));
$date_format = $params->get('date_format');
if (!$date_format) $date_format = JText::_('DATE_FORMAT_LC1');

$filter_ext = $params->get('filter_extension');
$ext_empty  = empty($filter_ext);

if (!$ext_empty && is_array($filter_ext)) {
    $empty = true;
    foreach ($filter_ext AS $ext)
    {
        if (empty($ext)) continue;
        $empty = false;
    }
    $ext_empty = $empty;
}
?>
<script type="text/javascript">
<!--
var fpv = '';

function uaNext<?php echo $id;?>(el)
{
    if (jQuery(el).hasClass('disabled') == false) {
        modUA.getItems('userActivityForm', '<?php echo $id; ?>', <?php echo $limit; ?>, 'next');
    }
}
function uaPrev<?php echo $id;?>(el)
{
    if (jQuery(el).hasClass('disabled') == false) {
        modUA.getItems('userActivityForm', '<?php echo $id; ?>', <?php echo $limit; ?>, 'prev');
    }
}
function uaFilter<?php echo $id;?>()
{
    modUA.getItems('userActivityForm', '<?php echo $id; ?>', <?php echo $limit; ?>, 'filter');
}
function uaFilterSearch<?php echo $id;?>(v)
{
    if (fpv == v) return;
    fpv = v;

    if (v.length > 2 || v.length == 0) {
        modUA.getItems('userActivityForm', '<?php echo $id; ?>', <?php echo $limit; ?>, 'filter');
    }
}
-->
</script>
<form action="<?php echo JRoute::_('index.php?option=com_useractivity&view=module'); ?>" method="post"
    name="userActivityForm<?php echo $id; ?>"
    id="userActivityForm<?php echo $id; ?>"
    autocomplete="off"
    >

    <?php if ($count && 1 == 2) : ?>
        <fieldset>
            <!-- Start Top Navigation -->
            <div class="fltlft">
                <button type="button" class="actbtn-prev-<?php echo $id; ?> btn button disabled" onclick="uaPrev<?php echo $id; ?>(this);">
                    &lt;
                </button>
                <button type="button" class="actbtn-next-<?php echo $id; ?> btn button <?php if ($limit >=  $data['total']) echo ' disabled'; ?>" onclick="uaNext<?php echo $id; ?>(this);">
                    &gt;
                </button>
            </div>
            <!-- End Top Navigation -->

            <!-- Start Filters -->
            <?php
                if ($params->get('show_filter_extension')) :
                    $ext = $params->get('filter_extension');
                    ?>
                    <div class="fltrt">
                        <select name="filter_extension" onchange="uaFilter<?php echo $id;?>()" class="inputbox">
                            <option value=""><?php echo JText::_('MOD_USERACTIVITY_PF_SITE_FIELD_OPTION_SELECT_EXTENSION'); ?></option>
                            <?php echo JHtml::_('select.options', $extensions, 'value', 'text', $ext); ?>
                        </select>
                    </div>
                <?php
            endif;
            ?>
            <?php if ($params->get('show_filter_event')) : ?>
                <div class="fltrt">
                    <select name="filter_event_id" onchange="uaFilter<?php echo $id;?>()" class="inputbox">
                        <option value=""><?php echo JText::_('MOD_USERACTIVITY_PF_SITE_FIELD_OPTION_SELECT_EVENT'); ?></option>
                        <?php echo JHtml::_('select.options', $model->getEvents(), 'value', 'text', $params->get('filter_event_id')); ?>
                    </select>
                </div>
            <?php endif; ?>
            <!-- End Filters -->

            <!-- Start Search -->
            <?php if ($params->get('show_filter_search')) : ?>
                <div class="fltrt">
                    <input type="text" class="inputbox" placeholder="<?php echo JText::_('MOD_USERACTIVITY_PF_SITE_FILTER_SEARCH_DESC'); ?>"
                        name="filter_search" value="" autocomplete="off" onkeyup="uaFilterSearch<?php echo $id;?>(this.value);"
                        title="<?php echo JText::_('MOD_USERACTIVITY_PF_SITE_FILTER_SEARCH_DESC'); ?>"
                    />
                </div>
            <?php endif; ?>
            <!-- End Search -->
            <div class="clr" style="clear: both;"></div>
        </fieldset>
    <?php endif; ?>
    <strong>Latest MakeWhatever Activities:</strong><br />
    <!-- Start List -->
    <ul id="activities-<?php echo $id; ?>" style='list-style-type: none; font-size: 12px;'>
    	<?php if ($count) : ?>
                <?php
                $jsl = new JomSocialLib();
                foreach ($data['items'] as $i => $item) :
                    $date = JHtml::_('date', $item->created, $date_format);
                //print_r($item);
                $item->created_by
                    ?>
                    <li>
                        <?php
                        $avatar = $jsl->getAvatarThumb($item->created_by);
                        $profiurl = $jsl->profileURL($item->created_by);
                        if ($avatar)
                        {
                            echo "<a href='$profiurl'><img src='$avatar' style='float: left; margin: 5px;' /></a>";
                        }
                        ?>
                        <strong class="row-title"><?php echo $item->text; ?></strong>
                        <p class="small">
                            <?php
                            if ($date_rel) :
                                ?>
                                <span class="hasTip" title="<?php echo $date; ?>" style="cursor: help;">
                                    <?php echo UserActivityHelper::relativeDateTime($item->created); ?>
                                </span>
                                <?php
                            else :
                                echo $date;
                            endif;
                            ?>
                        </p>
                    </li>
                    <?php
                endforeach;
                ?>
    	<?php else : ?>
    		<li>
    			<?php echo JText::_('MOD_USERACTIVITY_PF_SITE_NO_MATCHING_RESULTS');?>
    		</li>
    	<?php endif; ?>
    </ul>
    <div class="clr" style="clear: both;"></div>
    <!-- End List -->

    <!-- Start Bottom Navigation -->
    <?php if ($count) : ?>
        <fieldset>
            <div class="fltlft">
                <button type="button" class="actbtn-prev-<?php echo $id; ?> btn button disabled" onclick="uaPrev<?php echo $id; ?>(this);">
                    &lt;
                </button>
                <button type="button" class="actbtn-next-<?php echo $id; ?> btn button <?php if ($limit >=  $data['total']) echo ' disabled'; ?>" onclick="uaNext<?php echo $id; ?>(this);">
                    &gt;
                </button>
            </div>
            <div class="clr" style="clear: both;"></div>
        </fieldset>
    <?php endif; ?>
    <!-- End Bottom Navigation -->

    <input type="hidden" name="id" value="<?php echo $id; ?>"/>
    <input type="hidden" value="<?php echo $start; ?>" name="limitstart"/>
    <input type="hidden" value="<?php echo $limit; ?>" name="limit"/>
    <input type="hidden" value="<?php echo $data['total']; ?>" name="total"/>
    <input type="hidden" value="0" name="busy"/>
    <?php echo JHtml::_('form.token'); ?>
</form>
