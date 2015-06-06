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

<div style="padding: 10px; background: #fff; margin: 10px;"><div><h1>Cash-in</h1>

<p>To get the fun started its always good with money in the wallet. Here you can buy Makertokens for your wallet. We are very early in the startup process, so we separate our system with any reel payment system, and process the transfer manually. As soon as possible we will implement more smooth transfer methods.   
    You can cash-in money to your account by a making money transfer to one of the following account, we will then transfer the Makertoken to your wallet.</p>
<p>We will then process it, deduct fee, and put it in your wallet.</p>
<p>The Processing fee for Bitcoin, Paypal, Skrill is $5 + 1% of the amount.
The processing fee for bankwire transfer is: $5 + 1% of the amount.
1 Maker token is cost 1 US dollar.</p></div>
    <div style="background: #888; color: #fff; padding: 10px; margin: 5px; margin-bottom: 10px; border-radius: 5px;"><p><strong>Bank Transfer:</strong></p>
<p>SWIFT: ALBADKKK</p>
<p>IBAN : DK7853610000345973</p>
<p>Company: Nordic Innovation</p>
<p>Account: 5361034597</p>
</div>
<p>Its IMPORTANT to put in your USERNAME in the messages field. We use that for identifying you with the money transfer.</p>
<div style="background: #888; color: #fff; padding: 10px; margin: 5px; margin-bottom: 10px; border-radius: 5px;"><p><strong>Paypal:</strong></p>
    <p>Account: <b>cashin@makewhatever.org</b></p>
    <p>Its IMPORTANT to put in your <a href="<?php echo JRoute::_('index.php?option=com_colcrewallet&task=method&df=1');?>">USERNAME</a> in the messages field. We use that for identifying you with the money transfer.</p>
</div>
<div style="background: #888;  color: #fff; padding: 10px; margin: 5px; margin-bottom: 10px; border-radius: 5px;">
    <p><strong>Bitcoin:</strong></p>
    <p>Address: <b>1LKoZJDgmR2t2PaFCvWPEPLQHkFvCrqUEh</b> </p>
    <p>Its IMPORTANT to REGISTER the <a href="<?php echo JRoute::_('index.php?option=com_colcrewallet&task=method&df=5');?>">BITCOIN ADDRESS</a> your are transferring from. We use that for identifying you with the bitcoin transfer.</p>
</div>
</div>