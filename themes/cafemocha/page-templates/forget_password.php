<?php
/**
 * Template Name: forget password Templets
 * Description: A Page Template that adds a sidebar to pages
 *
 * @package WordPress
 * @subpackage Twenty_Eleven
 * @since Twenty Eleven 1.0
 */
ob_start();
get_header();
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

        function code() {
            $symbols = array('2', '3', '4', '5', '6', '7', '8', '9', 'A', 'C', 'E', 'G', 'H', 'K', 'M', 'N', 'P', 'R', 'S', 'U', 'V', 'W', 'Z', 'Y', 'Z');
            $captcha_word = '';
            for ($i = 0; $i <= 4; $i++) {
                $captcha_word .= $symbols[rand(0, 24)];
            }
            return $captcha_word;
        }

        if (isset($_POST['user_register_submit'])) {

            $register = $_POST['register'];
            $user_email = trim(stripslashes($register['email']));
            $date = date('d-m-Y');
            if ($user_email == '')
                $error_message = 'ERROR : Please Enter A Email';
            else if (!is_email($user_email))
                $error_message = 'ERROR : Check The Format Of The Email';

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
                    $code = code();
                    update_user_meta($userid, 'code', $code);
//                    wp_update_user(array('ID' => $userid, 'user_pass' => $user_password));
//                    $msg = 'Your registration is successfully completed';
//                    set_transient('msg', $msg, 30);
                    //////////// user mail send/////////
                    $subject = "Forget Password";
                    $temp = "";
                    $temp.="<p>Hello $username,</p>";
                    $temp.="Please Click The Below Link To Change Your Password :";
                    $temp.="<p>Link To  : <a href='".get_bloginfo('siteurl').'/forget?code='.$code."'>Change Password</a></p>";
                    $temp.="<br>";
                    $temp.="<p>Thank You & Best regard</p>";
                    $temp.="<br>";
                    $temp.="<p>Cafemocha</p>";
                    // send_mail_func($student_email, $subject, $temp);
                    //$headers = "From: " . get_bloginfo("name") . '<' . get_option('admin_email') . '>' . "\r\n";
                     $headers = 'From: Cafemocha<' . get_option('admin_email') . '>' . "\r\n";
                    $headers .= "MIME-Version: 1.0\r\n";
                    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
                    wp_mail($user_email, $subject, $temp, $headers);
                    ///end///
                    $msga = 'Your Password Successfully Updated . Please check Your Mail for new password.';
                    set_transient('msg', $msga, 30);
                }else{
                    $error_message = 'ERROR : Email Not Exist';
                    set_transient('error_msg', $error_message, 30);
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
                    <div class="form_left"><label>E-mail<span>*</span>:</label></div>
                    <div class="form_right"><input type="text" name="register[email]" id="email" value="<?php echo $register['email']; ?>" class="form_input" /></div>
                </div>
               
                <div class="form_cont">
                    <div class="form_left"></div>
                    <div class="form_right"><input type="submit" class="form_submit" id="user_register_submit" name="user_register_submit" value="Send" /></div>
                </div>
            </form>
        </div>
    </div><!-- #content -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>