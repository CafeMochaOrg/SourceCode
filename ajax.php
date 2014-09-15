<?php
add_action('wp_ajax_block_user', 'block_user');
add_action('wp_ajax_nopriv_block_user', 'block_user');

function block_user() {
    global $wpdb;
	global $current_user;
	get_currentuserinfo();
    $blk_id = $_POST['blk_id'];
	$blocked_users=unserialize($current_user->blocked_users);
	if($blocked_users==false)
		$blocked_users=array();
	if(!in_array($blk_id,$blocked_users))
	{
		array_push($blocked_users,$blk_id);
		update_user_meta($current_user->ID, 'blocked_users', serialize($blocked_users));
	}
	
	get_currentuserinfo();
	$blocked_users=unserialize($current_user->blocked_users);
		?>
		<select id="unblck_id" size="5">
					<?php
	if($blocked_users!=false)
	{
						foreach($blocked_users as $b)
						{?>
							<option id="<?php echo $b;?>" value="<?php echo $b;?>"><?php $user_info = get_userdata($b); echo $user_info->display_name;?></option><?php
						}
	}
					?>
		</select>
		<?
    die;
}


add_action('wp_ajax_unblock_user', 'unblock_user');
add_action('wp_ajax_nopriv_unblock_user', 'unblock_user');

function unblock_user() {
    global $wpdb;
	global $current_user;
	get_currentuserinfo();
    $id = $_POST['id'];
	//echo "id - ".$id;
	$blocked_users=unserialize($current_user->blocked_users);
	//var_dump($blocked_users);
	//echo "<br>".in_array($id,$blocked_users);
	if($blocked_users==false)
		$blocked_users=array();
	if(in_array($id,$blocked_users))
	{
		$key=array_search($id, $blocked_users);
		//echo "id - ".$id."key - ".$key;
		unset($blocked_users[$key]);
		$arr=array();
		foreach($blocked_users as $b)
		{
			array_push($arr,$b);
		}
		//var_dump($arr);
		update_user_meta($current_user->ID, 'blocked_users', serialize($arr));
	}
	
	get_currentuserinfo();
	$blocked_users=unserialize($current_user->blocked_users);
		?>
		<select id="unblck_id" size="5">
					<?php
	if($blocked_users!=false)
	{
						foreach($blocked_users as $b)
						{?>
							<option id="<?php echo $b;?>" value="<?php echo $b;?>"><?php $user_info = get_userdata($b); echo $user_info->display_name;?></option><?php
						}
	}
					?>
		</select>
		<?
    die;
}





add_action('wp_ajax_sip_it', 'sip_it');
add_action('wp_ajax_nopriv_sip_it', 'sip_it');

function sip_it()
{
	require_once('custom_functions/sipping.php');
	global $current_user;
	get_currentuserinfo();
    $post_id = $_POST['id'];
	$sipper_id=$current_user->id;
	$rtrn=sipit($sipper_id,$post_id);
	echo $rtrn;
	die;
}

/*function sip_it() {
    global $wpdb;
	global $current_user;
	get_currentuserinfo();
    $post_id = $_POST['id'];
	$sip_it = intval(get_post_meta($post_id,'sip_it',true));
	$sip_it++;
	update_post_meta($post_id,'sip_it',$sip_it);
	echo $sip_it;
    die;
}*/

add_action('wp_ajax_spit_it', 'spit_it');
add_action('wp_ajax_nopriv_spit_it', 'spit_it');

function spit_it()
{
	require_once('custom_functions/sipping.php');
	global $current_user;
	get_currentuserinfo();
    $post_id = $_POST['id'];
	$sipper_id=$current_user->id;
	$rtrn=spitit($sipper_id,$post_id);
	echo $rtrn;
	die;
}
/*function spit_it() {
    global $wpdb;
	global $current_user;
	get_currentuserinfo();
    $post_id = $_POST['id'];
	$spit_it = intval(get_post_meta($post_id,'spit_it',true));
	$spit_it++;
	update_post_meta($post_id,'spit_it',$spit_it);
	echo $spit_it;
    die;
}*/



