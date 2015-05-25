var walletClass =
{
    validateData: function(MaxValue)
    {
        jQuery('.warn').html('');
        var username = jQuery('#username').val();
        var points= jQuery('#points').val();
        if (username.trim() == '')
        {
            jQuery('.warn').html("Invalid username");
            return false;
        }
        if (isNan(points))
        {
            jQuery('.warn').html("Invalid number of points");
            return false;
        }
        var ref = jQuery('#walletmsg').val();
        if ( ref.length  > MaxValue)
        {
            jQuery('.warn').html("Number of characters exceeds the limit");
            return false;
        }
        return true;
    },
    updateCharCount: function(outputing, label_id, MaxValue)
    {
        var RemainingChar, OverLimit, ExceedMsg;
        RemainingChar = "<b>Remaining Characters:</b> ";
        OverLimit = "<b>Number of characters over the limit:</b> ";
        ExceedMsg = "<b>Number of characters exceeds the limit</b>"; 
        var ref = outputing; 
        if (MaxValue -  ref.value.length)
        {
            jQuery("#label_"+label_id).html(MaxValue - ref.value.length)
            jQuery("#spanid_"+label_id).html(RemainingChar);
        }
        else
        {
            jQuery("#label_"+label_id).html((MaxValue - ref.value.length))
            jQuery("#spanid_"+label_id).html(OverLimit);
        }
   }
}
