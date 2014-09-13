<?php

/**

 * Template Name: registration Templets

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

            <h2 class="title">Create Account</h2>

        </div>

        <?php

        $error_message = '';

        $success_message = '';

        global $wpdb;

        session_start();

//echo $imge1=bloginfo('template_directory').'/custom-images/user-F-01.jpg';

        if (isset($_POST['user_register_submit'])) {



            $register = $_POST['register'];

            $user_name = trim(stripslashes($register['name']));

            $user_username = trim(stripslashes($register['username']));

            $user_password = $register['password'];

            $conform_password = $register['conform_password'];

            $user_email = trim(stripslashes($register['email']));

            $dob = trim(stripslashes($register['dob']));

            $phone_no = trim(stripslashes($register['phone_no']));

            $captch = trim(stripslashes($register['captch']));

            $rr = $_SESSION['captcha'];

            $photo = $_FILES['photo'];

            $user_F_01 = trim(stripslashes($register['user_F_01']));



            $post_name = $register['post_name'];

            $date = date('d-m-Y');

            if ($photo['error'] != 0) {

                if ($user_F_01 == "") {

                    $error_message = 'ERROR : Please Select Avatar Image';

                }

            }

            if ($user_name == '')

                $error_message = 'ERROR : Please Enter A User Name';

            else if (username_exists($user_username))

                $error_message = 'ERROR : User Name Already Exists';

            else if ($post_name == '')

                $error_message = 'ERROR : Please Select Post name';

            else if ($user_email == '')

                $error_message = 'ERROR : Please Enter A Email';

            else if (!is_email($user_email))

                $error_message = 'ERROR : Check The Format Of The Email';

            else if (email_exists($user_email))

                $error_message = 'ERROR : This Email Already Exists';

            else if (empty($user_password))

                $error_message = 'ERROR : Please Enter A Password';

            else if ($user_name == $user_password)

                $error_message = 'ERROR : Please Select A Password Other Than User Name';

            else if ($conform_password == "")

                $error_message = 'ERROR : Please Confirm the Password';

            else if ($conform_password != $user_password)

                $error_message = 'ERROR : Passwords do not Match';

            else if ($captch != $rr)

                $error_message = 'ERROR : Captcha Does not Match';

            if ($error_message != '') {

                set_transient('error_msg', $error_message, 30);

                set_transient('register', $register);

                wp_redirect(get_permalink());

                exit;

            } else {

                if (!function_exists('wp_handle_upload'))

                    require_once( ABSPATH . 'wp-admin/includes/file.php' );

                $uploadedfile = $_FILES['photo'];

                $upload_overrides = array('test_form' => false);

                $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

                if ($movefile) {

                    

                    $user_id = wp_create_user($user_name, $user_password, $user_email);

                    if($movefile['url']=="") {

                        update_user_meta($user_id, 'photo', $user_F_01);

                    } else {

                        update_user_meta($user_id, 'photo', $movefile['url']);

                    }

                    update_user_meta($user_id, 'post_name', $register['post_name']);

                    update_user_meta($user_id, 'Alternative_User', $register['Alternative_User']);

                    update_user_meta($user_id, 'email', $register['email']);

                    update_user_meta($user_id, 'Title', $register['Title']);

                    update_user_meta($user_id, 'Experience_In', $register['Experience_In']);

                    update_user_meta($user_id, 'Other_Expertise', $register['Other_Expertise']);

                    update_user_meta($user_id, 'Institiution', $register['Institiution']);

                    update_user_meta($user_id, 'City', $register['City']);

                    update_user_meta($user_id, 'State', $register['State']);

                    update_user_meta($user_id, 'Choosed_Mocha', serialize(array()));

                    update_user_meta($user_id, 'blocked_users', serialize(array()));

                    $msg = 'Your registration is successfully completed';

                    set_transient('msg', $msg, 30);

                    //////////// user mail send/////////

                    $subject = "Thank You for Registering with CafeMocha";

                    $temp = "";

                    $temp.="<p>Dear $user_name,</p>";

                    $temp.="THANK YOU! By registering with CafeMocha, not only are you able to publish your creative content for the world to see, but you are also helping to revolutionize our broken education system--a system in which students are not able to be innovative.";

                    $temp.="<p>The date you registered: $date</p>";

                    $temp.="<br>";

                    $temp.="<p>Thank You & Best Regards,</p>";

                    $temp.="<br>";

                    $temp.="<p>CafeMocha</p>";

                    // send_mail_func($student_email, $subject, $temp);

                    $headers = "From: " . get_bloginfo("name") . '<' . get_option('admin_email') . '>' . "\r\n";

                    $headers .= "MIME-Version: 1.0\r\n";

                    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

                    wp_mail($user_email, $subject, $temp, $headers);

                    ///end///

                    $creds = array();



                    $creds['user_login'] = $user_name;

                    $creds['user_password'] = $user_password;

                    $user = wp_signon($creds, true);



                    if (is_wp_error($user)) {

                        $error_message = 'ERROR: Invalid username or password';

                    } else {

                        if (isset($_POST['user_login_remember']) && $_POST['user_login_remember'] != "") {

                            setcookie("common_login_username", $user_name, time() + (60 * 60 * 24 * 7));

                            setcookie("common_login_userpass", $user_password, time() + (60 * 60 * 24 * 7));

                        } else {

                            setcookie("common_login_username", $user_name, time() - (60 * 60 * 24 * 7));

                            setcookie("common_login_userpass", $user_password, time() - (60 * 60 * 24 * 7));

                        }

                        global $wpdb;



                        wp_safe_redirect(get_bloginfo('siteurl'));

                    }

                } else {

                    $msg = 'Photo uploaded unsuccessfully';

                    set_transient('msg', $msg, 30);

                    wp_redirect(get_bloginfo('url') . '/registration');

                    exit();

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

        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>

        <script type="text/javascript">

            jQuery(document).ready(function() {

                jQuery('#user_register_submit').click(function() {

                    var name = jQuery('#name').val();

                    var msg = jQuery('#msg').val();

                    var captcha = jQuery('#captcha').val();



                    if (name.length == 0) {

                        jQuery('#name').addClass('error');

                    }

                    else {

                        jQuery('#name').removeClass('error');

                    }



                    if (msg.length == 0) {

                        jQuery('#msg').addClass('error');

                    }

                    else {

                        jQuery('#msg').removeClass('error');

                    }



                    if (captcha.length == 0) {

                        jQuery('#captcha').addClass('error');

                    }

                    else {

                        jQuery('#captcha').removeClass('error');

                    }



                    if (name.length != 0 && msg.length != 0 && captcha.length != 0) {

                        return true;

                    }

                    return false;

                });



                var capch = '< ?php echo $cap; ?>';

                if (capch != 'notEq') {

                    if (capch == 'Eq') {

                        jQuery('.cap_status').html("Your form is successfully Submitted ").fadeIn('slow').delay(3000).fadeOut('slow');

                    } else {

                        jQuery('.cap_status').html("Human verification Wrong!").addClass('cap_status_error').fadeIn('slow');

                    }

                }







            });



            function refresh()

            {

                document.getElementById('captcha-image-new').src = '<?php echo get_bloginfo('url') . '/wp-content/themes/cafemocha/captcha.php' ?>?' + Math.random();

                document.getElementById('captcha').focus();

            }

        </script>

	<strong><p class="form_right" style="text-decoration: underline; color:#FFCC00; font-size:large;">Profile Picture</p></strong>


        <div class="form_wrap">

            <form action="" method="post" enctype="multipart/form-data">

                <div class="form_cont">

                    <div class="form_left">

                        <label>Please Upload A Profile Picture:</label>

                    </div>

                    <div class="form_right">

                        <div class="profile_pic">

                            <img id="image" src="<?php echo get_bloginfo('siteurl') . '/wp-content/themes/cafemocha/custom-images/no-image.jpg'; ?>" height="48px" width="48px" />

                        </div>

                        <div class="browse_cont">

                            <input type="file" name="photo" id="photo" />



                        </div>

                    </div>

                </div>

                <div class="form_cont">

                    <div class="form_left">

                        <label>Or Choose Avatar Image<span>*</span>:</label>

                    </div>

                    <div class="form_right">

                        <div class="choose_avatar">

                            <div class="profile_pic">

                                <img id="image_ava" src="<?php echo get_bloginfo('siteurl') . '/wp-content/themes/cafemocha/custom-images/user-F-01.jpg'; ?>" height="48px" width="48px" />

                            </div>

                            <div class="browse_cont">

                                <input type="radio" name="register[user_F_01]" <?php if($register['user_F_01']== get_bloginfo('siteurl') . '/wp-content/themes/cafemocha/custom-images/user-F-01.jpg'){echo 'checked="checked"';}?> value="<?php echo get_bloginfo('siteurl') . '/wp-content/themes/cafemocha/custom-images/user-F-01.jpg'; ?>"/>

                            </div>



                        </div>

                        <div class="choose_avatar"> 

                            <div class="profile_pic">

                                <img id="image_ava" src="<?php echo get_bloginfo('siteurl') . '/wp-content/themes/cafemocha/custom-images/user-F-02.jpg'; ?>" height="48px" width="48px" />

                            </div>

                            <div class="browse_cont">

                                <input type="radio" name="register[user_F_01]" <?php if($register['user_F_01']== get_bloginfo('siteurl') . '/wp-content/themes/cafemocha/custom-images/user-F-02.jpg'){echo 'checked="checked"';}?> value="<?php echo get_bloginfo('siteurl') . '/wp-content/themes/cafemocha/custom-images/user-F-02.jpg'; ?>" />

                            </div>

                        </div>



                        <div class="choose_avatar">

                            <div class="profile_pic">

                                <img id="image_ava" src="<?php echo get_bloginfo('siteurl') . '/wp-content/themes/cafemocha/custom-images/user-M-01.jpg'; ?>" height="48px" width="48px" />

                            </div>

                            <div class="browse_cont">

                                <input type="radio" name="register[user_F_01]" <?php if($register['user_F_01']== get_bloginfo('siteurl') . '/wp-content/themes/cafemocha/custom-images/user-M-01.jpg'){echo 'checked="checked"';}?> value="<?php echo get_bloginfo('siteurl') . '/wp-content/themes/cafemocha/custom-images/user-M-01.jpg'; ?>" />

                            </div>

                        </div>



                        <div class="choose_avatar">

                            <div class="profile_pic">

                                <img id="image_ava" src="<?php echo get_bloginfo('siteurl') . '/wp-content/themes/cafemocha/custom-images/user-M-02.jpg'; ?>" height="48px" width="48px" />

                            </div>

                            <div class="browse_cont">

                                <input type="radio" name="register[user_F_01]" <?php if($register['user_F_01']== get_bloginfo('siteurl') . '/wp-content/themes/cafemocha/custom-images/user-M-02.jpg'){echo 'checked="checked"';}?> value="<?php echo get_bloginfo('siteurl') . '/wp-content/themes/cafemocha/custom-images/user-M-02.jpg'; ?>" />

                            </div>

                        </div>







                    </div><!--form_right-->



                </div>

                <script class="jsbin" src="<?php echo get_bloginfo('siteurl') . '/wp-content/themes/cafemocha/js/jquery.min.js'; ?>"></script>

                <script class="jsbin" src="<?php echo get_bloginfo('siteurl') . '/wp-content/themes/cafemocha/js/upload/upload.js'; ?>"></script>

	
	<p>&nbsp</p>
	<p>&nbsp</p>

	<strong><p class="form_right" style="text-decoration: underline; color:#FFCC00; font-size:large;">Required</p></strong>



                <div class="form_cont">

                    <div class="form_left">

                        <label>User Name<span>*</span>:</label>

                    </div>

                    <div class="form_right"><input type="text" name="register[name]" id="user_name" value="<?php echo $register['name']; ?>" class="form_input" /></div>

                </div>

                <div class="form_cont">

                    <div class="form_left"><label>Use Real Name on Post? (Recommended)<span>*</span>: </label></div>

                    <div class="form_right">

                        <input type="radio" name="register[post_name]" value="0" <?php if ($register['post_name'] == '0') echo 'checked="checked"'; ?>>Yes

                        <input type="radio" name="register[post_name]" value="1" <?php if ($register['post_name'] == '1') echo 'checked="checked"'; ?>>No

                    </div>

                </div>

                <div class="form_cont">

                    <div class="form_left"><label>Real Name:</label></div>

                    <div class="form_right"><input type="text" name="register[Alternative_User]" id="Alternative_User" value="<?php echo $register['Alternative_User']; ?>" class="form_input" /></div>

                </div>

                <div class="form_cont">

                    <div class="form_left"><label>E-mail<span>*</span>:</label></div>

                    <div class="form_right"><input type="text" name="register[email]" id="email" value="<?php echo $register['email']; ?>" class="form_input" /></div>

                </div>

                <div class="form_cont">

                    <div class="form_left"><label>Password<span>*</span>:</label></div>

                    <div class="form_right"><input type="password" name="register[password]" id="password" value="<?php echo $register['password']; ?>" class="form_input" /></div>

                </div>

                <div class="form_cont">

                    <div class="form_left"><label>Confirm Password<span>*</span>:</label></div>

                    <div class="form_right"><input type="password" name="register[conform_password]" id="conform_password" value="<?php echo $register['conform_password']; ?>" class="form_input" /></div>

                </div>
		
<p>&nbsp</p>
		<p>&nbsp</p>

		<p>&nbsp</p>

                
		<strong><p class="form_right" style=" font-size: large; text-decoration: underline; color:#FFCC00;">Optional</p></strong>

<div class="form_cont">

                    <div class="form_left"><label>Title:</label></div>

                    <div class="form_right"><input type="text" name="register[Title]" id="Title" value="<?php echo $register['Title']; ?>" class="form_input"  autocomplete="off"/></div>

                </div>

                <div class="form_cont">

                    <div class="form_left"><label>Experience In:</label></div>

                    <div class="form_right"><input type="text" name="register[Experience_In]" id="Experience_In" value="<?php echo $register['Experience_In']; ?>"  class="form_input" /></div>

                </div>

                <div class="form_cont">

                    <div class="form_left"><label>Other Expertise:</label></div>

                    <div class="form_right"><input type="text" name="register[Other_Expertise]" id="Other_Expertise" value="<?php echo $register['Other_Expertise']; ?>"  class="form_input" /></div>

                </div>

                <div class="form_cont">

                    <div class="form_left"><label>Institiution:</label></div>

                    <div class="form_right"><input type="text" name="register[Institiution]" id="Institiution" value="<?php echo $register['Institiution']; ?>"  class="form_input" /></div>

                </div>

                <div class="form_cont">

                    <div class="form_left"><label>City:</label></div>

                    <div class="form_right"><input type="text" name="register[City]" id="City" value="<?php echo $register['City']; ?>"  class="form_input" /></div>

                </div>

                <div class="form_cont">

                    <div class="form_left"><label>State:</label></div>

                    <div class="form_right"><input type="text" name="register[State]" id="State" value="<?php echo $register['State']; ?>"  class="form_input" /></div>

                </div>
		
		<p>&nbsp</p>
		<p>&nbsp</p>

		<p>&nbsp</p>

		<strong><p class="form_right" style="text-decoration: underline; color:#FFCC00; font-size:large;">Captcha</p></strong>

	
                <div class="form_cont">

                    <div class="form_left"><label>Enter the Contents of Image</label><label class="mandat"><span>*</span>:</label></div>

                    <div class="form_right"><input type="text" name="register[captch]" id="captcha" maxlength="6" size="6"  class="form_input" /></div>

                    <div class="form_right"><img id="captcha-image-new" name="captcha-image-new" src="<?php echo get_bloginfo('url') . '/wp-content/themes/cafemocha/captcha.php' ?>"/><div class="case_sensitive">Case Sensitive</div></div>

                    <div class="form_right"><a href="javascript:void();" onClick="javascript:refresh();"  id="change-image">Not readable? Change text.</a></div>

                </div>

                <div class="form_cont">

                    <div class="form_left"></div>

                    <div class="form_right"><input type="submit" class="form_submit" id="user_register_submit" name="user_register_submit" value="Create Account" /></div>

                </div>

            </form>



        </div>

    </div><!-- #content -->

</div><!-- #primary -->

<style type="text/css">
.form_left label span{color:#ea8b1f; margin-left:10px; font-size:14px; }
.form_right{ width:56%;}
.form_left{ width:41%;} 
</style>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
