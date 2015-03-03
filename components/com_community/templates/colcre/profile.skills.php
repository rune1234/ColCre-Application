<?php
/**
* @copyright (C) 2013 iJoomla, Inc. - All rights reserved.
* @license GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html)
* @author iJoomla.com <webmaster@ijoomla.com>
* @url https://www.jomsocial.com/license-agreement
* The PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript *are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0
* More info at https://www.jomsocial.com/license-agreement
*/
defined('_JEXEC') or die();
$validPassword = JText::sprintf( JText::_( 'JLIB_DATABASE_ERROR_VALID_AZ09', true ), JText::_( 'Password', true ), 4 );
$user = JFactory::getUser();
//redacron file ******************
?>
 <script><!--
      
    jQuery(document).ready(function($) {    jQuery('.catgbox').click(function(e) {  
            window.location.href = '<?php echo JRoute::_('index.php?option=com_community&view=profile&task=addskill&Itemid=103');?>&catgid='+ jQuery(this).data('catg'); });
});
     
--></script>
<div class="cLayout cProfile-Edit"> 
    <h1 class='colcreh'>Add Skills</h1><br />
     
    <div class='maincatgbox'>
        <?php
        //print_r($_POST);
        foreach ($skillCategories as $skctg)
        {
             echo "<div class='catgbox' data-catg='$skctg->id'>\n<div class='catgtitle' >".$skctg->category."</div></div>\n";
        }
        ?>
        <div style='clear: both;'></div>
    </div>
    
	 
		
</div>
  
<script type="text/javascript"><!--

	joms.jQuery( document ).ready( function(){

	joms.privacy.init();

	var tabContainers = joms.jQuery('.cTabsContent > div');

	var url = document.location.href;

	var filter = ':first';

	if(url.indexOf("#detailSet")!== -1)
	{
		filter = ':last';
	}

	joms.jQuery('.cPageTabs li a').click(function () {
		tabContainers.hide().filter(this.hash).fadeIn(500);
		joms.jQuery('.cPageTabs li').removeClass('cTabCurrent');
		joms.jQuery(this).closest('li').addClass('cTabCurrent');
		return false;
	}).filter(filter).click();

	});

function submitbutton(formId) {
	var r = new RegExp("[\<|\>|\"|\'|\%|\;|\(|\)|\&|\+|\-]", "i");

	//hide all the error messsage span 1st
	joms.jQuery('#name').removeClass('invalid');
	joms.jQuery('#jspassword').removeClass('invalid');
	joms.jQuery('#jspassword2').removeClass('invalid');
	joms.jQuery('#jsemail').removeClass('invalid');

	joms.jQuery('#errnamemsg').hide();
	joms.jQuery('#errnamemsg').html('&nbsp');

	joms.jQuery('#errpasswordmsg').hide();
	joms.jQuery('#errpasswordmsg').html('&nbsp');

	joms.jQuery('#errjsemailmsg').hide();
	joms.jQuery('#errjsemailmsg').html('&nbsp');

	joms.jQuery('#password').val(joms.jQuery('#jspassword').val());
	joms.jQuery('#password2').val(joms.jQuery('#jspassword2').val());

	// do field validation
	var isValid	= true;

	if (joms.jQuery('#name').val() == "") {
		isValid = false;
		joms.jQuery('#errnamemsg').html('<?php echo addslashes(JText::_( 'COM_COMMUNITY_PLEASE_ENTER_NAME', true ));?>');
		joms.jQuery('#errnamemsg').show();
		joms.jQuery('#name').addClass('invalid');
	}

	if(joms.jQuery('#jsemail').val() !=  joms.jQuery('#email').val())
	{
		regex=/^[a-zA-Z0-9._-]+@([a-zA-Z0-9.-]+\.)+[a-zA-Z0-9.-]{2,4}$/;
		isValid = regex.test(joms.jQuery('#jsemail').val());

		var fieldname = joms.jQuery('#jsemail').attr('name');;
		if(isValid == false){
			cvalidate.setMessage(fieldname, '', 'COM_COMMUNITY_INVALID_EMAIL');
			joms.jQuery('#jsemail').addClass('invalid');
		}
	}

	if(joms.jQuery('#password').val().length > 0 || joms.jQuery('#password2').val().length > 0) {
		//check the password only when the password is not empty!
		if(joms.jQuery('#password').val().length < 6 ){
			isValid = false;
			joms.jQuery('#jspassword').addClass('invalid');
			alert('<?php echo addslashes(JText::_( 'COM_COMMUNITY_PASSWORD_TOO_SHORT' ));?>');
		} else if (((joms.jQuery('#password').val() != "") || (joms.jQuery('#password2').val() != "")) && (joms.jQuery('#password').val() != joms.jQuery('#password2').val())){
			isValid = false;
			joms.jQuery('#jspassword').addClass('invalid');
			joms.jQuery('#jspassword2').addClass('invalid');
			var err_msg = "<?php echo addslashes(JText::_( 'COM_COMMUNITY_PASSWORD_NOT_SAME' )); ?>";
			alert(err_msg);
		}
	}

	if(isValid) {
		//replace the email value.
		joms.jQuery('#email').val(joms.jQuery('#jsemail').val());
		joms.jQuery('#' + formId).submit();
	}
}

// Password strenght indicator
var password_strength_settings = {
	'texts' : {
		1 : '<?php echo addslashes(JText::_('COM_COMMUNITY_PASSWORD_STRENGHT_L1')); ?>',
		2 : '<?php echo addslashes(JText::_('COM_COMMUNITY_PASSWORD_STRENGHT_L2')); ?>',
		3 : '<?php echo addslashes(JText::_('COM_COMMUNITY_PASSWORD_STRENGHT_L3')); ?>',
		4 : '<?php echo addslashes(JText::_('COM_COMMUNITY_PASSWORD_STRENGHT_L4')); ?>',
		5 : '<?php echo addslashes(JText::_('COM_COMMUNITY_PASSWORD_STRENGHT_L5')); ?>'
	}
}

joms.jQuery('#jspassword').password_strength(password_strength_settings);
//-->
</script>


