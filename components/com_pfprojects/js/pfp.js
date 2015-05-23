jQuery(document).ready(function($) {  
    jQuery( "#dialog" ).dialog({
      autoOpen: false,
      show: {
        effect: "blind",
        duration: 1000
      },
      hide: {
        effect: "explode",
        duration: 1000
      }
    });
    jQuery(".projDelClos").click(function() {jQuery( "#dialog" ).dialog( "close" ); });
    jQuery(".projDelYes, .projPagDelYes").click(function() 
    { 
        var projectPage = false;
        if ( jQuery(this).attr('class') == 'projPagDelYes') { projectPage = true; }
         
        deleteProject( jQuery('#dialog').data('jsondel'), projectPage); 
        jQuery( "#dialog" ).dialog( "close" ); 
    });
    jQuery( ".projectDelete, .projPagDel" ).click(function() 
    {     
          
          jQuery('#dialogtitle').html(jQuery(this).data('projtitle'));
          var ygor = {};
          ygor.token =  jQuery(this).data('token');
          ygor.userid =  jQuery(this).data('userid');
          ygor.id = jQuery(this).attr('id').replace('projDel_', '');
          jQuery('#dialog').data('jsondel', JSON.stringify(ygor));
        
          jQuery( "#dialog" ).dialog( "open" );
    });
        jQuery('input[type="text"].SkillInput').keyup(function () { _appUrl = jQuery(this).val(); getSkills(_appUrl); });
        jQuery('.token-input-input-token').click(function() { jQuery('.SkillInput').focus(); } );
         
}); 
function deleteProject(projDat, projectPage)//user must be able to message a project's owner
{
    //if (projDat == '') return;
    var data = JSON.parse(projDat);
    jQuery('#projtrDel_' + data.id).remove();
    $fragment_refresh = {
		url: projectURL,
		type: 'POST',
		data: { option: 'com_projectfork', task:'deleteproject', project_id:data.id, created_by: data.userid, token: data.token},
		success: function( data ) { if (projectPage === true) { window.location.href = jQuery('#dialog').data('url'); } }
    };
    jQuery.ajax( $fragment_refresh );
}
function msgOwner()//user must be able to message a project's owner
{
    var project_id = jQuery("#project_id").val();
    var created_by = jQuery("#created_by").val();
    var user_id = jQuery("#user_id").val();
    var proposal = jQuery("#proposal").val();
    var howWould = jQuery("#howwould").val();
    $fragment_refresh = {
		url: projectURL,
		type: 'POST',
		data: { option: 'com_projectfork', task:'msgOwner', project_id:project_id, created_by: created_by, user_id: user_id, proposal:proposal, howwould: howWould},
		success: function( data ) {   data = JSON.parse(data);
                     
                    jQuery('#propoDIV').html(data.proposal).css({'padding' : '5px', 'border' : '1px solid #bbb', 'margin' : '5px', 'background': '#fff'});
                    jQuery('#howwDIV').html(data.howwould).css({'padding' : '5px', 'border' : '1px solid #bbb', 'margin' : '5px', 'background': '#fff'});
                    jQuery('#submitPropos').remove();
        }
    };
    jQuery.ajax( $fragment_refresh );
}

function getSkills(skill)
{
    jQuery(".resultsList").html('');
    $fragment_refresh = {
		url: tasksURL,
		type: 'POST',
		data: { option: 'com_pfprojects', task:'getskills', skill: skill},
		success: function( data ) { jQuery("ul.resultsList").html(''); trade = JSON.parse(data); 
                    for (tr in trade) 
                    { 
                        
                        jQuery("ul.resultsList").css("display", "block").append(jQuery('<li data-skill="' +trade[tr].id +'"></li>').append(trade[tr].skill) ); }
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