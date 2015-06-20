jQuery(document).ready(function($) 
{  
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
    //**********************************************************************************
    jQuery( ".methodDel" ).click(function() //used to delete payment methods
    {     
         jQuery( "#dialog" ).dialog( "open" );
         var ygor = {};
         ygor.id = jQuery(this).attr('id').replace('paytyplnk_','');
         ygor.typeid = jQuery(this).data('typeid');
         ygor.token = jQuery(this).data('token');
         ygor.userid = jQuery('#dialog').data('userid');
         jQuery('#dialog').data('jsondel', JSON.stringify(ygor));
    });
    jQuery('.methodDelYes').click(function()//user chose to delete the payment type
    {
         var ygor = jQuery('#dialog').data('jsondel');
         var data = JSON.parse(ygor);
         delPayMethod(data);
         jQuery( "#dialog" ).dialog( "close" );
    });  
    //************************************************************************************
    //used to delete tasks:
    jQuery( ".taskDel" ).click(function() //used to delete payment methods
    {      var ygor = {};
          ygor.id = jQuery(this).attr('id').replace('taskDel_', '');
          ygor.url = jQuery(this).data('url');
          ygor.token = jQuery(this).data('token');
          ygor.userid = jQuery(this).data('userid');
          jQuery('#dialog').data('jsondel', JSON.stringify(ygor));
          jQuery( "#dialog" ).dialog( "open" );
     });
    jQuery(".TaskDelYes").click(function()
    {
          var ygor = deleteTask(jQuery('#dialog').data('jsondel'));
          var data = JSON.parse(ygor);
    });
    //************************************************************************************
    
        jQuery('input[type="text"].SkillInput').keyup(function () { _appUrl = jQuery(this).val(); getSkills(_appUrl); });
        jQuery('.token-input-input-token').click(function() { jQuery('.SkillInput').focus(); } );
         
}); 

// END OF EVENTS ******************************************************************************************
function banuser(projid, userid, token)
{
      deleteuser(projid, userid, token, 1);
}
function deleteuser(projid, userid, token, option)
{
    if (isNaN(userid) || userid == 0) { alert('invalid user'); return; }
    if (isNaN(projid) || projid == 0) { alert('invalid project'); return; }
    if (typeof(option) == 'undefined') option = 0;
    jQuery( "#dialog" ).dialog( "open" );
    jQuery('#dialog').data('token', token);
    jQuery('#dialog').data('option', option);
    jQuery('#dialog').data('userid', userid);
    jQuery('#dialog').data('token', token);
    jQuery('#dialog').data('projid', projid);
}
function delProjMember()
{
    jQuery('#memerbox_'+ jQuery('#dialog').data('userid')).remove();
    jQuery('#dialog').dialog('close');
     var total = jQuery('#profmemcount').html();
    if (parseInt(total) > 0)
    {
        total = total - 1;
        jQuery('#profmemcount').html(total);
    }
    var token = jQuery('#dialog').data('token');
     var option_2 = jQuery('#dialog').data('option');
     var userid = jQuery('#dialog').data('userid');
     var projid = jQuery('#dialog').data('projid');
     var $fragment_refresh = {
		url: projectURL,
		type: 'POST',
		data: { option: 'com_projectfork', option_2: option_2, task:'removeuser', token: token, projid: projid, userid: userid},
		success: function( data ) 
                {  
                    data = JSON.parse(data); 
                    if (data.error == 1) alert(data.msg); 
                } 
    };
    jQuery.ajax( $fragment_refresh );
}
function delPayMethod(data)//user must be able to message a project's owner
{
    jQuery('#paytype_' + data.id).remove();
    var $fragment_refresh = {
		url: projectURL,
		type: 'GET',
		data: { option: 'com_colcrewallet', task:'deletemethod', id: data.id, type: data.typeid, token: data.token, userid:data.userid},
		success: function( data ) 
                { // alert(data);
                    data = JSON.parse(data); 
                    if (data.error == 1) alert(data.msg); 
                } 
    };
    jQuery.ajax( $fragment_refresh );
}
function deleteTask(data)//user must be able to message a project's owner
{
    //if (projDat == '') return;
    var data = JSON.parse(data);
    jQuery('#projtrDel_' + data.id).remove();
    var $fragment_refresh = {
		url: projectURL,
		type: 'POST',
		data: { option: 'com_projectfork', task:'deleteTask', id:data.id, created_by: data.userid, token: data.token},
		success: function( data_2 ) { if (typeof(data.url) !== 'undefined') window.location.href = data.url; }
    };
    jQuery.ajax( $fragment_refresh );
}
function deleteProject(projDat, projectPage)//user must be able to message a project's owner
{
    //if (projDat == '') return;
    var data = JSON.parse(projDat);
    jQuery('#projtrDel_' + data.id).remove();
    var $fragment_refresh = {
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
function acceptProposal(project, proposal)
{
    jQuery('#accdec_' + proposal).html('<p><b>Proposal Accepted</b></p>');
    $fragment_refresh = {
		url: projectURL,
		type: 'POST',
		data: { option: 'com_pfprojects', task:'acceptproposal', proposal: proposal, project: project},
		success: function( data ) { alert(data); } };
    jQuery.ajax( $fragment_refresh );
}
function rejectProposal(project, proposal)
{
    jQuery('#accdec_' + proposal).html('<p><b>Proposal Declined</b></p>');
    $fragment_refresh = {
		url: projectURL,
		type: 'POST',
		data: { option: 'com_pfprojects', task:'rejecroposal', proposal: proposal, project: project},
		success: function( data ) { alert(data); } };
    jQuery.ajax( $fragment_refresh );
}
