<div class='category-list view-dashboard projectFrame' style='padding: 10px; margin: 10px;'><p>
        <a href='<?php echo JRoute::_('index.php?option=com_projectfork&view=dashboard&id='.$this->info->id.'&Itemid=124');?>'><?php echo ucwords($this->info->title);?></a> Members:</p>

    <img src="<?php JUri::base();?>images/user_accounts.png" alt="delete project" style="height: 70px; float: left; margin: 5px;"/>
    <p>Total: <span id='profmemcount'><?php echo $this->total;?></span></p>
    <div style="clear: both;"></div>
</div>
<?php

 

foreach ($this->usermembers as $usl)
{
    //print_r($usl);
    $user = $this->profile_data->lookupUser($usl->user_id);
   // print_r($user);
     echo "<div class='row-fluid matchBox' id='memerbox_$user->id' style='padding: 10px; margin: 10px; width: 40%; float: left;'>";
             //print_r($user);
             if ($user->thumb)
             {
                 echo "<img src='$user->thumb' alt='user avatar' style='float: left; margin-right: 5px;' />";
             }
             echo "<p><b>Name:</b> <a href='".JRoute::_('index.php?option=com_community&view=profile&userid='.$user->id.'&Itemid=103')."'>".ucwords($user->name)."</a>";
            // echo "</p>";
echo "<br /><span style='font-size: 10px;'>Member since ". date("m-d-Y", strtotime($user->registerDate)) ."</span>";
echo "<br /><a href='javascript:void(0)' onClick='deleteuser(".$this->info->id.", $user->id, \"".md5($this->info->id."michelle".$this->info->id)."\")'>Remove User</a></p>";

//echo "<p><a href='javascript:void(0)' onClick='banuser(".$this->info->id.", $user->id, \"".md5($this->info->id."michelle".$this->info->id)."\")'>Ban User</a> | <a href='javascript:void(0)' onClick='deleteuser(".$this->info->id.", $user->id, \"".md5($this->info->id."michelle".$this->info->id)."\")'>Delete User</a></p>";
switch ($usl->status):
    case 1: $status = 'Active';
    break;
    case 2: $status = 'Banned';
        break;
    default:
        $status = 'Removed';
endswitch;
echo "<div style='clear: both;'></div><p style='font-size: 11px;'>Status: $status</p>";
             echo "<div style='clear: both;'></div></div>";
}
echo "<div class='pagination'>". $this->pagination. "</div>";
?>
<div id="dialog" data-token="" data-option="" data-userid="" data-projid="">
    
    <p>Do you really want to delete this <span id='dialogmember'>user</span>?</p>
     <div style='width: 100%; text-align: center;'><input type='button' onClick='delProjMember()' value='Yes'  style='padding: 5px; background: #fff; width: 70px;' /> | <input type='button' value='No' class='projDelClos' style='padding: 5px; background: #fff; width: 70px;' />
</div>
</div>
<?php
$document = JFactory::getDocument();
$js = "var projectURL = '".JURI::root()."';";
$document->addScriptDeclaration($js);
$document->addCustomTag('<script src="'.JURI::root().'components/com_pfprojects/js/pfp.js" type="text/javascript"></script>');
?>
