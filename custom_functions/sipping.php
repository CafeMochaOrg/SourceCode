<?php

///////////////////////////////////////////////////////Get Sipping by sipper/////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////Get Sipping by id/////////////////////////////////////////////////////////////////////////////////////////
function get_sipit($post_id)
{
	global $wpdb;
	$query="SELECT SUM(sip_it) as sip_it FROM wp_sipping WHERE post_id='".$post_id."'";
	$myrows = $wpdb->get_results($query);
	/*echo "<script>alert('".$myrows[0]->sip_it."');</script>";*/
	if(count($myrows)>0)
		return $myrows[0]->sip_it;
	else
		return 0;
}
function get_spitit($post_id)
{
	global $wpdb;
	$myrows = $wpdb->get_results( "SELECT SUM(spit_it) as spit_it FROM wp_sipping WHERE post_id='".$post_id."'" );
	if(count($myrows)>0)
		return $myrows[0]->spit_it;
	else
		return 0;
}
////////////////////////////////////////////////////////Siping Function//////////////////////////////////////////////////////////////////////////////////////////
function sipit($sipper_id,$post_id)
{
	global $wpdb;
        $categories = get_the_category($post_id);
        $category_id=$categories[0]->term_id;
	$myrows = $wpdb->get_results("SELECT sip_it FROM wp_sipping WHERE sipper_id='".$sipper_id."' AND post_id='".$post_id."'");
	if(count($myrows)>0)
	{
		/*echo "<script>alert('Sorry, you can only Sip it/Spit it Once');</script>";
		$sip_it=$myrows[0]->sip_it;
		$sip_it++;
		$wpdb->get_results("UPDATE wp_sipping SET sip_it='".$sip_it."' WHERE  sipper_id='".$sipper_id."' and post_id='".$post_id."'");
		
		return get_sipit($post_id);*/
		return 'false';
	}
	else
	{
		$query="SELECT post_author FROM wp_posts WHERE id='".$post_id."'";
		$myrows = $wpdb->get_results($query);
		//echo $query."           ".$myrows[0]->post_author;
		$query= "INSERT INTO wp_sipping (sipper_id,post_id,owner_id,sip_it,category) VALUES ('".$sipper_id."','".$post_id."','".$myrows[0]->post_author."',1,'".$category_id."')";
		$wpdb->get_results($query);
		return get_sipit($post_id);
	}
}
function spitit($sipper_id,$post_id)
{
	global $wpdb;
	$myrows = $wpdb->get_results("SELECT spit_it FROM wp_sipping WHERE sipper_id='".$sipper_id."' AND post_id='".$post_id."'");
	if(count($myrows)>0)
	{
		/*echo "<script>alert('Sorry, you can only Sip it/Spit it Once');</script>";
		$spit_it=$myrows[0]->spit_it;
		$spit_it++;
		$wpdb->get_results("UPDATE wp_sipping SET spit_it='".$spit_it."' WHERE  sipper_id='".$sipper_id."' and post_id='".$post_id."'");
		return get_spitit($post_id);*/
		return 'false';
	}
	else
	{
		$query="SELECT post_author FROM wp_posts WHERE id='".$post_id."'";
		$myrows = $wpdb->get_results($query);
		//echo $query."           ".$myrows[0]->post_author;
		$query= "INSERT INTO wp_sipping (sipper_id,post_id,owner_id,spit_it) VALUES ('".$sipper_id."','".$post_id."','".$myrows[0]->post_author."',1)";
		$wpdb->get_results($query);
		return get_spitit($post_id);
	}
}
?>