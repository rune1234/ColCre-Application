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


?>
<script>
<!--
function changeQuantity()
{
    var amount = jQuery('#currentamount').val();
    if (isNaN(amount)) { jQuery('#amountwarning').html('Amount is not a number'); return; }
    else jQuery('#amountwarning').html('');
    var availablebal = parseInt(jQuery('#availablebal').html());
    var newValue = availablebal - amount;
    var theWarn = '#000';
    if (newValue < 0) { theWarn = '#a00'; }
    jQuery('#newbalance').html(newValue).css({"color" : theWarn});
}
function validaWitWr()
{
     var values = {};
     jQuery('#amountwarning').html('');
     jQuery.each(jQuery('#cashoutform').serializeArray(), function(i, field) {  values[field.name ] = field.value; 
     if (isNaN(values['amount']) || values['amount'] == 0 || values['amount'].trim() == '')
     {
        jQuery('#amountwarning').html('Please enter the correct widthdrawal amount'); return false; 
     }
     else if ( parseInt(values['amount']) > parseInt(values['cashmoney']))
     {
        jQuery('#amountwarning').html('Your widthdrawal amount is greater than your available balance'); return false; 
     }
     return false;
}
//-->
</script>
<div>


</div>
<form method='post' id='cashoutform' onSubmit='return validaWitWr()'>
    <h4>Cash Out:</h4> 
    <?php
    if ($this->cash)
    {
         echo "<div style='background: #fff; margin: 5px; padding: 5px; width: 50%; color: #a00;'><p>$this->cash</p></div>";
    }
    ?>
    <p>What method?
        <select name='widthmethod'>
            <option value='1'>Paypal</option>
            <option value='2'>Bitcoin</option>
            <option value='3'>Banktransfer</option>
        </select>
    </p>
    <!--<p>Recently added withdrawal methods may take up to 5 days to process and will not appear in the dropdown menu until processing is complete.</p> <-- copied from elance -->
 
    <p><a href='<?php echo JRoute::_('index.php?option=com_colcrewallet&task=method');?>'>Add a Withdrawal Method</a></p>
<div>
    <!--<p>Available Balance 	US $<span id='availablebal'><?php echo $this->cash->money; ?></span></p> -->
    <p>Withdrawal Amount  <input type='text' name='amount' id='currentamount' onblur='changeQuantity()' id='widthamount' maxlength="4" size="4" style='width: 50px;' /> US $</p> 	
    <p>The Processing fee for Bitcoin, Paypal, Skrill is $5 + 1% of the amount.<br />The processing fee for bankwire transfer is: $50 + 1% of the amount.
        <br />Value of 1 Maker token is 1 US dollar.</p>
    
    <!-- <div>New Balance 	US $<span id='newbalance'><?php // echo $this->cash->money; ?></span></div>--><br />
<div style='color: #a00; font-weight: bold;' id='amountwarning'></div>
</div>
    <div style="vertical-align: text-top;">Note:
        <textarea style='width: 400px; height: 200px' name="cashoutmsg"></textarea>
    </div>
    <p><input type="submit" value="Submit" class='submit-button' /></p>
    <?php if ($this->cash != 0) { ?> 
    <input type='hidden' name='cashmoney' value='<?php echo $this->cash->money; ?>' />
    <?php } ?>
    <input type='hidden' name='getcash' value='1' />
    <input type='hidden' name='task' value='cashout' />
     <input type='hidden' name='option' value='com_colcrewallet' />
     <input type='hidden' name='user_id' value='<?php echo $this->user_id;?>' />
     <?php echo JHtml::_( 'form.token' ); ?>
     
</form>
