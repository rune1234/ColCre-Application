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
$maxValue = 120;
 if (! $this->result) {
     echo "<div class='warn'>".$this->msg."</div>";
     //echo "My Points: ".$this->mypoints;
?>

<form method='post' onSubmit="return walletClass.validateData(<?=$maxValue;?>)">
    <h4>Transfer to:</h4>
    <p>Username: <input type="text" name="username" id="username" value='<?php echo $usrname;?>' /></p>
    <p>Amount: <input type="text" name="points" id="points" value='<?php echo $points;?>' /></p>
    <p>Type?
        <select name='method'>
            <option value='1'>Make Whatever Tokens</option>
            <option value='2'>Dollars</option>
            <option value='3'>Bitcoins</option>
            <option value="4">KBHTF</option>
        </select>
    </p>
    <div style="vertical-align: text-top;">Note:
        <textarea style='width: 400px; height: 200px' name="message" id='walletmsg' oninput="walletClass.updateCharCount(this, 1, <?=$maxValue;?>)" 
                  onpaste="walletClass.updateCharCount(this, 1,<?=$maxValue;?>)" onkeyup="walletClass.updateCharCount(this, 1,<?=$maxValue;?>)"></textarea>
    </div>
    <div>
        <p><span id="spanid_1"></span><span id="label_1"></span></p>
    </div>
    <p><input type="submit" value="Submit" class='submit-button' /></p>
    <input type='hidden' name='sendingpoints' value='1' />
    <input type='hidden' name='task' value='sendpoints' />
     <input type='hidden' name='option' value='com_colcrewallet' />
     <input type='hidden' name='user_id' value='' />
     <?php echo JHtml::_( 'form.token' ); ?>
</form>
<?php
 }
 else
 {
    $mainframe = JFactory::getApplication();
     $mainframe->redirect(JRoute::_('index.php?option=com_colcrewallet'), $this->msg );
 }
?>
<script type="text/javascript">

if(typeof jQuery == 'undefined'){
        document.write('<script type="text/javascript" src="http://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.7.1.min.js"></'+'script>');
  }

</script>
<script type="text/javascript" src="<?php echo JUri::base(); ?>/components/com_colcrewallet/js/essentials.js"></script>