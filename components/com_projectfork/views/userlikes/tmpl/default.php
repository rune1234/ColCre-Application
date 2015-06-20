
<?php

 
echo "<div class='category-list view-dashboard projectFrame' style='padding: 10px; margin: 10px;'><p>"
. "<a href='".JRoute::_('index.php?option=com_projectfork&view=dashboard&id='.$this->info->id.'&Itemid=124')."'>".ucwords($this->info->title)."</a> Likes:</p></div>";
foreach ($this->userlikes as $usl)
{
    //echo $usl->user_id;
    $user = $this->profile_data->lookupUser($usl->user_id);
    
     echo "<div class='row-fluid matchBox' style='padding: 10px; margin: 10px; width: 96%;'>";
             //print_r($user);
             if ($user->thumb)
             {
                 echo "<img src='$user->thumb' alt='user avatar' style='float: left; margin-right: 5px;' />";
             }
             echo "<p><b>Name:</b> <a href='".JRoute::_('index.php?option=com_community&view=profile&userid='.$user->user_id.'&Itemid=103')."'>".ucwords($user->name)."</a>";
            // echo "</p>";
echo "<br />Member since ". date("m-d-Y", strtotime($user->registerDate)) ."</p>";
             echo "<div style='clear: both;'></div></div>";
}
echo "<div class='pagination'>". $this->pagination. "</div>";
?>