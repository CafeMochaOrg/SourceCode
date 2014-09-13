function sip_it(id)
{
	//alert(id);
	jQuery("#loading").show();
	jQuery("#loading").fadeTo("slow",0.5);
	var data={
			action:'sip_it',
			id:id
		};
		jQuery.post(ajaxurl,data,function(res){
			if(res=='false')
				alert('Sorry, you can only Sip it/Spit it Once');
			else
			{
				if(jQuery(".spit_it_up_"+id).html()=='')
					var spit_it_up=0;
				else
					var spit_it_up=parseInt(jQuery(".spit_it_up_"+id).html());
				//alert(spit_it_up);
				jQuery(".sip_it_up_"+id).html(res);
				var sip=parseInt(res)-spit_it_up;
				jQuery(".sip_up_"+id).html(sip);
				jQuery("#loading").hide();
			}
			//jQuery("#loading").fadeTo("slow",0.0);
		});
}
function spit_it(id)
{
	jQuery("#loading").show();
	jQuery("#loading").fadeTo("slow",0.5);
	var data={
			action:'spit_it',
			id:id
		};
		jQuery.post(ajaxurl,data,function(res){
			if(res=='false')
				alert('Sorry, you can only Sip it/Spit it Once');
			else
			{
				if(jQuery(".sip_it_up_"+id).html()=='')
					var sip_it_up=0;
				else
					var sip_it_up=parseInt(jQuery(".sip_it_up_"+id).html());
				jQuery(".spit_it_up_"+id).html(res);
				var sip=sip_it_up-parseInt(res);
				jQuery(".sip_up_"+id).html(sip);
				jQuery("#loading").hide();
			}
		});
}
function login_msg()
{
	alert("Please Login To Access This Feature");
}
function closed(id){
    //alert(id);
	var c=confirm('Do You Want To Delete This Message');
	if(c==false)
	return false;
    var data={
        action:'closed',
        id:id
    };
    jQuery.post(ajaxurl,data,function(res){
        //alert(res);
        //jQuery('.popup-contents-'+id).show();
        jQuery('#'+id).parents(".message_box").remove();
    });
}
function reply(id){
	//alert(id);
	var reply = document.getElementById('reply').value;
	//alert(reply);
		var data={
        action:'reply_notification',
        id:id,
		reply:reply
	};
    jQuery.post(ajaxurl,data,function(res){
        //alert(res);
        //jQuery('.popup-contents-'+id).show();
        jQuery('#reply_msg').html(res);
    });
	
	}
        function edit_event(jj){
	//alert("#editevent_"+value);
	//alert(jj);
	jQuery(jj).parents('.span9').find('.span9_as').slideToggle();
	}