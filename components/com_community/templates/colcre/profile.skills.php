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
//redacron file ******************
?>
<script><!--
    jQuery(document).ready(function($) {  
        jQuery('.token-input-input-token').click(function() { jQuery('.SkillInput').focus(); } );
        jQuery('.catgbox').click(function(e) 
        {  
            var centerHeight = (( jQuery(this).outerHeight()) /1) + 100;
            var centerWidth = '30%';//Math.max(0, (( jQuery(window).width() - jQuery(this).outerWidth()) / 2));
             var ff = jQuery('#addskillbox');
             ff.fadeIn().css({'top': centerHeight, 'left': centerWidth });
             title = jQuery(this).children('div.catgtitle').html();
             jQuery('#addskillbox > h1').html("Add Category for "  + title);
             ff.prepend('<a class="close2"><img src="<?php echo JUri::base();?>images/close-bl.png" class="btn_close" title="Close Window" alt="Close" /></a>');; 
             
             jQuery('body').append('<div id="fade"></div>'); //Add the fade layer to bottom of the body tag.
             jQuery('#fade').fadeIn();
             jQuery('.close2').click(function(){ removeLayer(); });
        } );
});
    function removeLayer() { jQuery('#addskillbox').fadeOut(function() { jQuery('#fade, a.close2').remove(); } ); return false; }
     

    --></script>
<div class="cLayout cProfile-Edit"> 
    <h1 class='colcreh'>Add Skills</h1><br />
    <div ng-app="myProj"><div ng-controller="taskControl">
     <div class="control-group"><div class="control-label control-group">Adding skills to your profile will help us match them to projects that need people just like you:
               <div class="task-group" ng-repeat="task in tasks">
                                <input type='hidden' ng-repeat='chosenSK in skillChosen[task.id]' value='{{chosenSK.id}}' id='skiinp_{{task.id}}_{{chosenSK.id}}' name="taskform[{{task.id}}][SkillInput][]" />
                    <ul class="token-input-list">
             
 <li class="token-input-token" ng-repeat='chosenSK in skillChosen[task.id]'><p>{{chosenSK.skill}}</p> <span class="token-input-delete-token" ng-click='deleteSKill(task.id, chosenSK.id)'>×</span></li>
    
     <li class="token-input-input-token" ng-click='focusOnInput(task.id)'>
         <input type='text' id='skillInput{{task.id}}' class="SkillInput" style='width: 400px !important; border: 0 none !important;' ng-keyup="skillPress($event.altKey, task.id)" />
                
  
     </li></ul><div style='position: relative; margin-left: 50px;'><ul class='resultsList' id='resultsList{{task.id}}'><li ng-click='chooseSkill(task.id, skill.id, skill.skill)' ng-repeat='skill in skillResults[task.id]'>{{skill.skill}}</li></ul></div>
               </div></div></div>
    </div></div>
    <div class='maincatgbox'>
        <?php
        foreach ($skillCategories as $skctg)
        {
             echo "<div class='catgbox'>\n<div class='catgtitle'>".$skctg->category."</div></div>\n";
        }
        ?>
        <div style='clear: both;'></div>
    </div>
    
	 
		<div class="cTabsContent space-24">
			<div id="basicSet" class="section"> <!-- Profile Basic Setting -->
				<form name="jsform-profile-edit" id="frmSaveProfile" action="<?php echo CRoute::getURI(); ?>" method="POST" class="cForm community-form-validate" autocomplete="off">
					 


					<ul class="cFormList cFormHorizontal cResetList">
						 
						<li>
							<div class="form-field">
								<input type="hidden" name="action" value="profile" />
								<?php echo JHTML::_( 'form.token' ); ?>
								<input type="submit" name="frmSubmit" onclick="submitbutton('frmSaveProfile'); return false;" class="btn btn-primary" value="<?php echo JText::_('COM_COMMUNITY_SAVE_BUTTON'); ?>" />
							</div>
						</li>
					</ul>
				</form>
			</div> <!-- end basic setting -->

			
	</div> <!-- .end: .cTabsContent-->
</div>
<div id="addskillbox"><h1>Add Skill to</h1>
    <form onSubmit='return addUserSkill()'><table>
            <tr><td valign='top'>Skill: </td><td><input style='width: 320px;' type='text' name='skill' /></td></tr>
            <tr><td valign='top'>Description:  </td><td><textarea name='skilldesc' style='width: 320px; height: 200px;'></textarea></td></tr>
        <tr><td valign='top'>Skill Tags: </td><td><textarea name='skilltags' style='width: 320px; height: 100px;'></textarea></td></tr>
        </table> 
    <input type='hidden' name='skillcatg' value='' />
    <input type='submit' value='Submit' /> 
    </form>    
    
</div>    
<script type="text/javascript">

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
</script>


