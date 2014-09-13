<?php
$parse_uri = explode('wp-content', $_SERVER['SCRIPT_FILENAME']);
//print_r($parse_uri);
require_once( $parse_uri[0] . 'wp-load.php' );
global $wpdb, $current_user;
get_currentuserinfo();
$auto=$_REQUEST['q'];
$user_id = $current_user->id;
$all_users = $wpdb->get_results("select * from  wp_users where user_login like '%" . strtolower($auto) . "%'");
$total_user = count($all_users);
$str = '';
$str.='[';
$i = 0;
foreach ($all_users as $all_users_val) {
    $i;
    $user_login = $all_users_val->user_login;
    $u_id = $all_users_val->ID;
    $str.='{"name":"' . $user_login . '","id":"' . $u_id . '"}';
    if ($total_user - 1 != $i) {
        $str.=',';
    }
    $i = $i + 1;
}
$str.=']';
echo $str;
?>