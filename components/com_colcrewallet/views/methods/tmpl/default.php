<?php
echo "<div id='wallet-menu' class='row-fluid' style='width: 100%;'><ul>"
       /*  . "<li><a href='#'>Held Points</a></li>"*/
                . "<li><a href='".JRoute::_('index.php?option=com_colcrewallet')."'>Instructions</a></li>"
                . "<li><a href='".JRoute::_('index.php?option=com_colcrewallet&task=sendpoints')."'>Transfer</a></li>"
                 ."<li><a href='".JRoute::_('index.php?option=com_colcrewallet&task=assets')."'>My Assets</a></li>"
         ."<li><a href='".JRoute::_('index.php?option=com_colcrewallet&task=cashout')."'>Cash Out</a></li>"
        ."<li><a href='".JRoute::_('index.php?option=com_colcrewallet&task=transactions')."'>Transfer History</a></li>"
                . "</ul></div><div style='clear: both;'></div><br />";
                
                
                 
$usrname = isset($this->post['username']) ? $this->post['username'] : '';
$points = isset($this->post['points']) ? $this->post['points'] : '';
$payment = array();
$user = JFactory::getUser();
$df = JRequest::getVar('df', 0);
if ($df == 0)
{
    //print_r($this->address);
    foreach ($this->address as $tad)
    {
        if ($tad->payment_type == 2)
        {
            $payment['bitcoin'][] = $tad;
        }
        elseif($tad->payment_type == 4)
        {
            $payment['skrill'][] = $tad;
        }
        elseif($tad->payment_type == 1)
        {
            $payment['paypal'][] = $tad;
        }
    }
}
?>
<div><div class="lead page-header">Default & Backup Payment Account</div>
    <div class="container-fluid" style="margin-left: 5px;"><p>Payment Account will be used to automatically pay for your Make Whatever membership</p></div>
   
</div>
<div class="row-fluid"><div class="lead page-header">Your Accounts</div>
     <!--<div class="container-fluid creditbar" style="margin-left: 5px;">
    <div class="span3">Bitcoin Account</div><div class="span3"></div>
    
    </div>-->
    <?php
    if (isset($payment['bitcoin'])) 
    {
       $newbitcoin = 'new';
    ?>
    <div class="container-fluid creditbar" ><p><b>Your Bitcoin Accounts:</b></p>
        <?php 
        foreach ($payment['bitcoin'] as $bitc)
        {
             echo "<div style='display: -webkit-flex; display: flex;' id='paytype_".$bitc->id."'><div style='width: 200px;'>Address: $bitc->address</div><div style='margin-left: 100px;'>"
                    . "<a href='".JRoute::_('index.php?option=com_colcrewallet&task=method&df=5&id='.$bitc->id)."'>Edit</a> | <a href='javascript:void(0)' id='paytyplnk_".$bitc->id."' data-token= '".md5($bitc->id."idtype".$bitc->payment_type.$user->id)."' data-typeid='".$bitc->payment_type."' class='methodDel'>Delete</a></div></div>";
        }
?></div>
    <?php
    } else $newbitcoin = '';
    ?>
     <div class="container-fluid creditbar" style="margin-left: 5px;">
    <div class="span4"><a href='<?php echo JRoute::_('index.php?option=com_colcrewallet&task=method&df=5');?>'>Enter <?php echo $newbitcoin." ";?>Bitcoin Account Information</a></div>
    </div>
    <!--<div class="container-fluid creditbar" style="margin-left: 5px;">
    <div class="span3">Paypal Accounts</div><div class="span3"></div>
    </div>
    -->
     <?php
    if (isset($payment['paypal'])) 
    {
       $newpaypal = 'new ';
    ?>
    <div class="container-fluid creditbar" ><p><b>Your Paypal Accounts:</b></p>
        <?php 
        foreach ($payment['paypal'] as $bitc)
        {
             
            echo "<div style='display: -webkit-flex; display: flex;' id='paytype_".$bitc->id."'><div style='width: 300px;'>E-Mail Address: $bitc->address</div><div style='margin-left: 100px;'><a href='".JRoute::_('index.php?option=com_colcrewallet&task=method&df=1&id='.$bitc->id)."'>Edit</a> | <a href='javascript:void(0)' id='paytyplnk_".$bitc->id."' data-token= '".md5($bitc->id."idtype".$bitc->payment_type.$user->id)."' data-typeid='".$bitc->payment_type."' class='methodDel'>Delete</a></div></div>";
        }
?></div>
    <?php
    } else $newpaypal = '';
    ?>
    
    <div class="container-fluid creditbar" style="margin-left: 5px;">
        <div class="span4"><a href='<?php echo JRoute::_('index.php?option=com_colcrewallet&task=method&df=1');?>'>Enter <?php echo $newpaypal;?>Paypal Account</a></div>
    </div>
    <!--<div class="container-fluid creditbar" style="margin-left: 5px;">
    <div class="span3">Skrill Accounts</div><div class="span3"></div>
    </div>-->
          <?php
    if (isset($payment['skrill'])) 
    {
       $newskrill = 'new ';
    ?>
    <div class="container-fluid creditbar" ><p><b>Your Skrill Accounts:</b></p>
        <?php 
        foreach ($payment['skrill'] as $bitc)
        {
             
            echo "<div style='display: -webkit-flex; display: flex;' id='paytype_".$bitc->id."'><div style='width: 300px;'>E-Mail Address: $bitc->address</div><div style='margin-left: 100px;'><a href='".JRoute::_('index.php?option=com_colcrewallet&task=method&df=2&id='.$bitc->id)."'>Edit</a> | <a href='javascript:void(0)' id='paytyplnk_".$bitc->id."' data-token= '".md5($bitc->id."idtype".$bitc->payment_type.$user->id)."' data-typeid='".$bitc->payment_type."' class='methodDel'>Delete</a> </div></div>";
        }
?></div>
    <?php
    } else $newskrill = '';
    ?>
    <div class="container-fluid creditbar" style="margin-left: 5px;">
    <div class="span4"><a href='<?php echo JRoute::_('index.php?option=com_colcrewallet&task=method&df=2');?>'>Enter <?php echo $newskrill;?>Skrill Account</a></div>
    </div>
 
   <!--<div class="container-fluid creditbar" style="margin-left: 5px;">
    <div class="span3">Credit Card Accounts</div><div class="span3"></div>
    </div>
     <div class="container-fluid creditbar" style="margin-left: 5px;">
    <div class="span4"><a href='<?php echo JRoute::_('index.php?option=com_colcrewallet&task=method&df=3');?>'>Enter Credit Card Account</a></div>
    </div>
    <div class="container-fluid creditbar" style="margin-left: 5px;">
    <div class="span3">Bank Account</div><div class="span3"></div>
    </div>
   -->
        <?php
    if (isset($this->banks)) 
    {
       $newskrill = 'new ';
    ?>
    <div class="container-fluid creditbar" ><p><b>Your Bank Accounts:</b></p>
        <?php 
        foreach ($this->banks as $bitc)
        {
             
            echo "<div style='display: -webkit-flex; display: flex;' id='paytype_".$bitc->id."'><div style='width: 300px;'>Account: $bitc->account</div><div style='margin-left: 100px;'><a href='".JRoute::_('index.php?option=com_colcrewallet&task=method&df=4&id='.$bitc->id)."'>Edit</a> | <a href='javascript:void(0)' id='paytyplnk_".$bitc->id."' data-token= '".md5($bitc->id."idtype"."3".$user->id)."' data-typeid='3' class='methodDel'>Delete</a> </div></div>";
        }
?></div>
    <?php
    } else $newskrill = '';
    ?>
   
     <div class="container-fluid creditbar" style="margin-left: 5px;">
    <div class="span4"><a href='<?php echo JRoute::_('index.php?option=com_colcrewallet&task=method&df=4');?>'>Enter <?php echo $newskrill;?>Bank Account Information</a></div>
    </div>
</div>

 <div id="dialog" title="Delete Method" data-userid='<?php echo $user->id;?>' data-jsondel=''>
     <p>Do you really want to delete this payment method?</p>
     <div style='width: 100%; text-align: center;'><input type='button' value='Yes' class='methodDelYes' style='padding: 5px; background: #fff; width: 70px;' /> | <input type='button' value='No' class='projDelClos' style='padding: 5px; background: #fff; width: 70px;' />
</div></div>

<?php
$document = JFactory::getDocument();
$js = "var projectURL = '".JURI::root()."';";
    $document->addScriptDeclaration($js);
$document->addCustomTag('<script src="'.JURI::root().'libraries/projectfork/js/jquery-ui.dialog.js" type="text/javascript"></script>');
$document->addCustomTag('<script src="'.JURI::root().'components/com_pfprojects/js/pfp.js" type="text/javascript"></script>');
?>