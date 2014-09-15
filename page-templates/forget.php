<?php
/**
 * Template Name: forget Templets
 * Description: A Page Template that adds a sidebar to pages
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
ob_start();
get_header();
?>
<?php
$user_code = $_GET['code'];
$user = get_users(array('meta_query' => array(array('key' => 'code', 'value' => $user_code, 'compare' => '='))));
if (count($user) > 0) {
  
   $user_email=$user[0]->user_email;
    ?>
    <div id="primary" class="site-content">
        <div id="content" role="main">
            <div class="short_by">
                <h2 class="title">Forget Password</h2>
            </div>
            <?php
            $error_message = '';
//$success_message = '';
            global $wpdb;

            if (isset($_POST['user_register_submit'])) {

                $register = $_POST['register'];
                $user_password = $register['password'];
                $conform_password = $register['conform_password'];

                $date = date('d-m-Y');
                if (empty($user_password))
                    $error_message = 'ERROR : Please Enter password';
                else if ($conform_password == "")
                    $error_message = 'ERROR : Please conform the password';
                else if ($conform_password != $user_password)
                    $error_message = 'ERROR : Passwords dose not match';
                if ($error_message != '') {
                    set_transient('error_msg', $error_message, 30);
                    set_transient('register', $register);
                    //wp_redirect(get_permalink());
                    //exit;
                } else {
                    if (email_exists($user_email)) {
                        $error_message = 'ERROR : This Email Already Exist';
                        $user = get_user_by_email($user_email);
                        $userid = $user->ID;
                        $username = $user->user_login;
                        wp_update_user(array('ID' => $userid, 'user_pass' => $user_password));
//                        $msg = 'Your registration is successfully completed';
//                        set_transient('msg', $msg, 30);
//                        //////////// user mail send/////////
//                        $subject = "Sign Up Confirmation";
//                        $temp = "";
//                        $temp.="<p>Hello $username,</p>";
//                        $temp.="Your Password Successfully Updated.";
//                        $temp.="<p>Your New Password Is : $user_password</p>";
//                        $temp.="<br>";
//                        $temp.="<p>Thank You & Best regard</p>";
//                        $temp.="<br>";
//                        $temp.="<p>Cafe mocha</p>";
//                        // send_mail_func($student_email, $subject, $temp);
//                        $headers = "From: " . get_bloginfo("name") . '<' . get_option('admin_email') . '>' . "\r\n";
//                        $headers .= "MIME-Version: 1.0\r\n";
//                        $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
//                        wp_mail($user_email, $subject, $temp, $headers);
//                        ///end///
                        $msga = 'Your Password Successfully Updated . ';
                        set_transient('msg', $msga, 30);
                    }
                }
            }
            $m = get_transient('msg');
            $mm = get_transient('error_msg');
            $register = get_transient('register');
            delete_transient('msg');
            delete_transient('error_msg');
            delete_transient('register');
            ?>
            <?php
            if ($m != "") {
                ?>
                <div class="success_message"><?php echo $m; ?></div>
            <?php } ?>
            <?php
            if ($mm != "") {
                ?>
                <div class="error_message"><?php echo $mm; ?></div>
            <?php } ?>
            <div class="form_wrap">
                <form action="" method="post" enctype="multipart/form-data">

                    <div class="form_cont">
                        <div class="form_left"><label>Password<span>*</span>:</label></div>
                        <div class="form_right"><input type="password" name="register[password]" id="password" value="<?php echo $register['password']; ?>" class="form_input" /></div>
                    </div>
                    <div class="form_cont">
                        <div class="form_left"><label>Conform Password<span>*</span>:</label></div>
                        <div class="form_right"><input type="password" name="register[conform_password]" id="conform_password" value="<?php echo $register['conform_password']; ?>" class="form_input" /></div>
                    </div>

                    <div class="form_cont">
                        <div class="form_left"></div>
                        <div class="form_right"><input type="submit" class="form_submit" id="user_register_submit" name="user_register_submit" value="Save" /></div>
                    </div>
                </form>
            </div>
        </div><!-- #content -->
    </div><!-- #primary -->
<?php
} else {
    wp_redirect(get_bloginfo('siteurl'));
    exit();
}
?>
<?php get_sidebar(); ?>
<?php get_footer(); ?>