<?php

echo "<div id='wallet-menu' class='row-fluid' style='width: 100%;'><ul>"
       /*  . "<li><a href='#'>Held Points</a></li>"*/
                . "<li><a href='".JRoute::_('index.php?option=com_colcrewallet')."'>Instructions</a></li>"
                . "<li><a href='".JRoute::_('index.php?option=com_colcrewallet&task=sendpoints')."'>Transfer</a></li>"
                 ."<li><a href='".JRoute::_('index.php?option=com_colcrewallet&task=assets')."'>My Assets</a></li>"
         ."<li><a href='".JRoute::_('index.php?option=com_colcrewallet&task=cashout')."'>Cash Out</a></li>"
        ."<li><a href='".JRoute::_('index.php?option=com_colcrewallet&task=transactions')."'>Transfer History</a></li>"
                . "</ul></div><div style='clear: both;'></div><br />";
                
                
                ?>
<p><b>My Assets:</b></p> 
<div class="row-fluid">
     
     <?php 
     $pt = array('Make Whatever Tokens', 'Dollars','Bitcoins');
     foreach ($this->rows as $rw) 
     {
     echo " <div class=\"container-fluid\" style='margin-bottom: 5px; background: #fff; padding-top: 7px;'><div class=\"span4\">".$pt[$rw->payment_type - 1]." Points: $rw->points</div></div>";
     }
?>
        
    </div>