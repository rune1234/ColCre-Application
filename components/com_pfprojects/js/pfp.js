jQuery(document).ready(function($) {  
        jQuery('input[type="text"].SkillInput').keyup(function () { _appUrl = jQuery(this).val(); getSkills(_appUrl); });
        jQuery('.token-input-input-token').click(function() { jQuery('.SkillInput').focus(); } );
         
});
function getSkills(skill)
{
    jQuery(".resultsList").html('');
    $fragment_refresh = {
		url: tasksURL,
		type: 'POST',
		data: { option: 'com_pfprojects', task:'getskills', skill: skill},
		success: function( data ) { jQuery("ul.resultsList").html(''); trade = JSON.parse(data); 
                    for (tr in trade) 
                    { jQuery("ul.resultsList").css("display", "block").append(jQuery('<li data-skill="' +trade[tr].id +'"></li>').append(trade[tr].skill) ); }
                        jQuery(".resultsList li").click(function () 
                        {   
                           if(typeof (jQuery("#projskills_"+ jQuery(this).attr('data-skill')).val() ) !=='string' ) {
                               jQuery('input[type="text"].SkillInput').val('');
                           jQuery('.form-inline').append("<input type='hidden' id='projskills_"+jQuery(this).attr('data-skill')+"' name='projskills[]' value='" + jQuery(this).attr('data-skill') +"' />");
                           jQuery('.token-input-input-token').before(
                                   '<li data-skillid="'+jQuery(this).attr('data-skill')+'" class="token-input-token"><p>'+ jQuery(this).html().replace(/&amp;/g, "&")+'</p> <span class="token-input-delete-token">×</span></li>');//provide the input box with the name
                           jQuery("ul.resultsList").fadeOut();
                           //allow users to remove skill entries:
                           jQuery('.token-input-delete-token').click(function() 
                           { 
                               jQuery('#projskills_' + jQuery(this).parent().attr('data-skillid')).remove();//remove the input box
                               jQuery(this).parent().remove(); 
                           } ); }
                       });
               } };
    jQuery.ajax( $fragment_refresh );
}