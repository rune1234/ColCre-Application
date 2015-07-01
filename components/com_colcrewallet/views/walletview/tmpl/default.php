<?php
echo "<div id='wallet-menu' class='row-fluid' style='width: 100%;'><ul>"
       /*  . "<li><a href='#'>Held Points</a></li>"*/
                . "<li><a href='".JRoute::_('index.php?option=com_colcrewallet')."'>Instructions</a></li>"
                . "<li><a href='".JRoute::_('index.php?option=com_colcrewallet&task=sendpoints')."'>Transfer</a></li>"
                 ."<li><a href='".JRoute::_('index.php?option=com_colcrewallet&task=assets')."'>My Assets</a></li>"
         ."<li><a href='".JRoute::_('index.php?option=com_colcrewallet&task=cashout')."'>Cash Out</a></li>"
        ."<li><a href='".JRoute::_('index.php?option=com_colcrewallet&task=transactions')."'>Transfer History</a></li>"
                . "</ul></div><div style='clear: both;'></div><br />";
                
                
 $pt = array('Make Whatever Tokens', 'Dollars','Bitcoins', 'KBHTF');
foreach($this->rows as $rw)
{  //print_r($rw);
    if ($rw->recipientData->id == $this->userid)
    {
        $background = "background: #ffd !important;";
    } else $background = "background: #fff !important;";
    echo "<div class='matchBox' style='padding: 10px; $background'>";
    
     
     echo "<p><b>Amount: </b>".ucwords($rw->points);
     if (isset($rw->payment_type) && (int)$rw->payment_type > 0) echo "<br /><b>Type:</b> ".$pt[$rw->payment_type -1];
   if ($rw->status == 'received') 
    {
        echo "<br /><b>Sender: </b><a href='index.php?option=com_community&view=profile&userid=".$rw->recipientData->id."&Itemid=104'>".ucwords($rw->recipientData->name)."</a> (".$rw->recipientData->username.")";
    }
    elseif ($rw->type == 1) 
    {
        echo "<br /><b>Name: </b><a href='index.php?option=com_community&view=profile&userid=".$rw->recipientData->id."&Itemid=104'>".ucwords($rw->recipientData->name)."</a> (".$rw->recipientData->username.")";
    }
    else
    {
        echo "<br /><b>Project: </b><a target='self' href='".JRoute::_('index.php?option=com_projectfork&view=dashboard&id='.$rw->recipientData->id.'&Itemid=124')."'>".ucwords($rw->recipientData->title)."</a>";
    }
    if ($rw->recipientData->id == $this->userid)
    {
        echo "<br /><b>Sender: </b><a href='index.php?option=com_community&view=profile&userid=".$rw->userData->id."&Itemid=104'>".ucwords($rw->userData->name)."</a> (".$rw->userData->username.")";
    }
    //echo "<br /><b>Status:</b> ".$rw->status;
    echo "<br /><b>Last Update:</b> ".date('m-d-Y', $rw->date_added);
    echo "</p>";
    echo "</div>";
}
echo "<div class='pagination'>".$this->pagination."</div>";
?>