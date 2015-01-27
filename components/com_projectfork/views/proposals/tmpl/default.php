<?php
function getAvatar($userid, & $db)
{
    if (!is_numeric($userid) || $userid == 0) return false;
    $query = "SELECT avatar FROM #__community_users WHERE userid = $userid LIMIT 1";
     
    $avatar = $db->setQuery($query)->loadResult();
    return ($avatar) ? $avatar : false;
}
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
echo "<p>".$pr->proposal."<br />";
echo $pr->howwould."</p>";
echo "<div class='clearfix'></div><hr /><p>Accept Proposal | Decline Proposal</p>";
?><div class='clearfix'></div></div>
    <?php
}

echo "<div class='pagination'>".$this->pagination->getPagesLinks()."</div>";

?>
</div>