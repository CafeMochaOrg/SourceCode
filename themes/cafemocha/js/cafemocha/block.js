/*jQuery(".bloked_users").click(function(){
	jQuery(".bloked_clicked_users").addClass("bloked_users");
	jQuery(".bloked_clicked_users").removeClass("bloked_clicked_users");
	jQuery(this).addClass("bloked_clicked_users");
	jQuery(this).removeClass("bloked_users");
	var id=jQuery(this).attr("id");
	jQuery("#unblck_id").val(id);
});*/
			
/*jQuery("#Unblock").click(function(){
	//alert(jQuery(availableTags).first());
	var id=jQuery("#unblck_id").val();
	unblock(id);
});*/
			
			
function block()
{
	var blck_nm=document.getElementById("blck_nm").value;
	var blk_id=document.getElementById("blck_id").value;
		var data={
			action:'block_user',
			blk_id:blk_id
		};
		jQuery.post(ajaxurl,data,function(res){
			jQuery("#block_cont_find").html(res);
		});
}
function unblock(id)
{
	//alert(id);
	/*var blck_nm=document.getElementById("blck_nm").value;
	var blk_id=document.getElementById("blck_id").value;*/
	var data={
		action:'unblock_user',
		id:id
	};
	jQuery.post(ajaxurl,data,function(res){
		jQuery("#block_cont_find").html(res);
	});
}