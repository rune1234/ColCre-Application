<?php
function getAvatar($userid, & $db)
{
    if (!is_numeric($userid) || $userid == 0) return false;
    $query = "SELECT avatar FROM #__community_users WHERE userid = $userid LIMIT 1";
     
    $avatar = $db->setQuery($query)->loadResult();
    return ($avatar) ? $avatar : false;
}
if ($this->proposals)
{
    ?>
<div class="projectFrame" style="padding: 0px; padding-left: 10px; margin: 10px;">
    <h3>Project <?php echo ucwords($this->project);?> Proposals:</h3>
</div>    
<?php
foreach($this->proposals as $pr)
{  
    ?>
<div class="projectFrame" style="padding: 10px; margin: 10px;"><?php  
$avatar = getAvatar($pr->user_id, $this->db);
if ($avatar)
{
    echo "<div style='float: left; margin: 5px;'><a target='blank' href='".JRoute::_('index.php?option=com_community&view=profile&userid='. $pr->user_id )."'><img style='height: 100px' src='".JUri::base().$avatar."' alt='user picture'></a></div>";
}
echo "<p>From: <a target='blank' href='".JRoute::_('index.php?option=com_community&view=profile&userid='. $pr->user_id )."'>".$pr->from_name."</a>";
echo "<br /><span style='font-size: 10px;'>Posted on: ".date('m-d-Y', $pr->posted_on)."</span></p>";
echo "<p>".$pr->proposal."<br /><br />";

echo $pr->howwould."</p>";
echo "<div style='clear: both'><a href=\"javascript:void(0)\" class=\"btn\" onClick=\"joms.messaging.loadComposeWindow('".$pr->user_id."')\"><img src='".JUri::base()."images/msg_icon.png' style='height: 30px;' />Message User</a></div>";
echo "<div class='clearfix'></div><hr /><p><a href=\"javascript:void(0)\" class=\"btn btn-mini btn-info\" onClick=\"msgOwner()\">Accept Proposal</a> <a href=\"javascript:void(0)\" class=\"btn btn-mini btn-info\" onClick=\"msgOwner()\">Decline Proposal</a></p>";
?><div class='clearfix'></div></div>
    <?php
}

echo "<div class='pagination'>".$this->pagination->getPagesLinks()."</div>";
}
else
{ ?>
<div class="projectFrame" style="padding: 10px; margin: 10px;">
    There are no proposals for project <?php echo ucwords($this->project);?>.
</div>    
<?php
}
   $document = JFactory::getDocument();
   $js = "var projectURL = '".JURI::root()."';";
   $document->addScriptDeclaration($js);
   $document->addCustomTag('<script src="'.JURI::root().'components/com_pfprojects/js/pfp.js" type="text/javascript"></script>');
   $js = JURI::root() . 'components/com_community/assets/window-1.0.min.js';

     echo '<script type="text/javascript" src="' . $js . '"></script>';
      $uribase = JURI::base(true). "/components/com_community/templates/default/css/style.css";
    $document->addStyleSheet($uribase);
   //<a href="javascript:void(0);" class="btn" onclick="joms.messaging.loadComposeWindow('871')"><span>Send Message</span></a>
?>

