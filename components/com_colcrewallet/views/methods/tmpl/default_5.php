<?php
 $id = JRequest::getInt('id');
?>
<form method="post">
    <div class="row-fluid">
        <div class="lead page-header">Enter Your Bitcoin Address</div>
    <div class="container-fluid creditbar" style="margin-left: 5px;">
            <div class="span3">Bitcoin Address:</div>
         <input type="text" name="address" value="<?php echo $this->address;?>" />
    <br /><input type="submit" value="submit" class="btn" />
        </div>
    </div>
    <input type="hidden" name="payment_type" value="2" />
    <input type='hidden' name='task' value='method' />
    <input type='hidden' name='id' value='<?php echo $id; ?>' />
     <input type='hidden' name='option' value='com_colcrewallet' />
     <input type='hidden' name='user_id' value='<?php echo $this->user_id;?>' />
       <?php echo JHtml::_( 'form.token' ); ?>
</form>