<?php
/**
 * Template Name: Publish A Moca Template
 *
 * Description: A page template that provides a key component of WordPress as a CMS
 * by meeting the need for a carefully crafted introductory page. The front page template
 * in Twenty Twelve consists of a page content area for adding text, images, video --
 * anything you'd like -- followed by front-page-only widgets in one or two columns.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
get_header();
global $wpdb;
session_start();
if (isset($_POST['moca_submit'])) {
    $captch = $_POST['captch'];
    $rr = $_SESSION['captcha'];
    /* print_r($_FILES["photo"]);

      die; */
    if ($_POST["term_name"] == '')
        $error_message = 'Please Enter A Mocha Name';
    elseif ($_POST["term_desc"] == '')
        $error_message = 'Please Enter A Mocha Name';
    else if ($captch != $rr)
        $error_message = 'ERROR : Captcha Code Mismatch ';
    if ($error_message == '') {
        if (!function_exists('wp_handle_upload'))
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
        $uploadedfile = $_FILES['photo'];
        $upload_overrides = array('test_form' => false);
        $movefile = wp_handle_upload($uploadedfile, $upload_overrides);
        $msg = 'Please Enter A Moca Name';
        if ($movefile) {
            $in_data1 = array('cat_name' => $_POST["term_name"], 'cat_desc' => $_POST["term_desc"], 'cat_img' => $movefile['url']);
            $wpdb->insert('wp_category', $in_data1);
        }
        $msg = 'Your Mocha is successfully Created.Please wait for admin approval';
        wp_redirect(get_bloginfo('siteurl'));
        exit();
    } else {
        set_transient('errormsg', $error_message, 30);
    }

    set_transient('msg', $msg, 30);
}
$m = get_transient('msg');
$mm = get_transient('errormsg');
delete_transient('msg');
delete_transient('errormsg');
?>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js"></script>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#form_submit').click(function() {
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
<div id="primary" class="site-content">
    <div id="content" role="main">
        <!--Short By-->
        <div class="short_by">
            <h2 class="title">Publish a Mocha</h2>
        </div>
        <!--Short By-->
        <!--Form Section-->
        <div class="form_wrap">
            <?php
            if ($m != "") {
                ?>
                <div class="success_message"><?php echo $m; ?></div>
            <?php } elseif ($mm != "") { ?>
                <div class="error_message"><?php echo $mm; ?></div>
            <?php } ?>

            <form action="" method="post" enctype="multipart/form-data">
                <div class="form_cont">
                    <div class="form_left">
                        <label>Mocha Title <span>*</span>:</label>
                    </div>
                    <div class="form_right">
                        <input type="text" name="term_name" maxlength="10" />
                    </div>
                </div>
                <div class="form_cont">
                    <div class="form_left">
                        <label>Mocha Description <span>*</span>:</label>
                    </div>
                    <div class="form_right">
                        <textarea name="term_desc" rows="4" cols="20"></textarea>
                    </div>
                </div>
                <!---->
                <div class="form_cont">
                    <div class="form_left">
                        <label>Please Upload A Profile Picture : - <span>*</span></label>
                    </div>
                    <div class="form_right">
                        <div class="profile_pic">
                            <img id="image" src="<?php echo get_bloginfo('siteurl') . '/wp-content/themes/cafemocha/custom-images/no-image.jpg'; ?>" height="48px" width="48px" />
                        </div>
                        <div class="browse_cont">
                            <input type="file" name="photo" id="photo"  />
                        </div>
                    </div>
                </div>
                <script class="jsbin" src="<?php echo get_bloginfo('siteurl') . '/wp-content/themes/cafemocha/js/jquery.min.js'; ?>"></script>
                <script class="jsbin" src="<?php echo get_bloginfo('siteurl') . '/wp-content/themes/cafemocha/js/upload/upload.js'; ?>"></script>
                <!---->
                <div class="form_cont">
                    <div class="form_left"><label>Enter the contents of image</label><label class="mandat"> *</label></div>
                    <div class="form_right"><input type="text" name="captch" id="captch" maxlength="6" size="6"  class="form_input" /></div>
                    <div class="form_right"><img id="captcha-image-new" name="captcha-image-new" src="<?php echo get_bloginfo('url') . '/wp-content/themes/cafemocha/captcha.php' ?>"/><div class="case_sensitive">Case Sensitive</div></div>
                    <div class="form_right"><a href="javascript:void();" onClick="javascript:refresh();"  id="change-image">Not readable? Change text.</a></div>
                </div>
        </div>
        <div class="form_cont">
            <div class="form_left"></div>
            <div class="form_right">
                <input type="submit" name="moca_submit" class="form_submit" id="form_submit" value="Create Mocha" />
            </div>
        </div>
        </form>
    </div>
    <!--End .form_wrap-->
    <!--Form Section-->
</div>
<!-- #content -->

<!-- #primary -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>
