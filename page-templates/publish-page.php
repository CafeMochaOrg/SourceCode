<?php
/**
 * Template Name: Publish A Story Page Template
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
if (!is_user_logged_in()) {
    wp_redirect(get_bloginfo('siteurl'));
}
?>
<?php
global $current_user, $wpdb;
get_currentuserinfo();

if (isset($_POST['Publish'])) {
    session_start();
    $post_title = $_POST['title'];
    $captch = $_POST['captch'];
    $rr = $_SESSION['captcha'];
    //$posts = get_posts(array('post_type' => 'post', 'post_status' => 'publish','post_title' => $post_title));
//    echo'<pre>';
//    print_r($posts);
//    echo'</pre>';
    if ($_POST['mocas'] == 'select')
        $error_message = 'ERROR : Please Select A Mocha';
    else if ($_POST['title'] == '')
        $error_message = 'ERROR : Please Enter A Title ';
    else if ($_POST['tag'] == '')
        $error_message = 'ERROR : Please Enter Document Tag ';
//    else if (count($posts) > 0)
//        $error_message = 'ERROR : Title Already Exist';
    else if ($_POST['description'] == '')
        $error_message = 'ERROR : Please Enter A Description ';
    else if ($_POST['story'] == '')
        $error_message = 'ERROR : Please Write A Story ';
    else if ($captch != $rr)
        $error_message = 'ERROR : Captcha Code Mismatch ';

    if ($error_message == '') {
        $my_post = array(
            'post_status' => 'publish',
            'post_author' => $current_user->ID,
            'post_type' => 'post',
            'post_title' => $_POST['title'],
            'post_content' => $_POST['story'],
            'post_category' => array($_POST['mocas'])
        );
        // Insert the post into the database
        $post_id = wp_insert_post($my_post);
        //upload
        if (!function_exists('wp_handle_upload'))
            require_once( ABSPATH . 'wp-admin/includes/file.php' );
        $uploadedfile = $_FILES['document'];
        $upload_overrides = array('test_form' => false);
        $movefile = wp_handle_upload($uploadedfile, $upload_overrides);
        update_post_meta($post_id, 'attached_file', $movefile['url']);
        update_post_meta($post_id, 'mocas', $_POST['mocas']);
        update_post_meta($post_id, 'description', $_POST['description']);
        update_post_meta($post_id, 'anonymous', $_POST['checkbox']);
        update_post_meta($post_id, 'tag', $_POST['tag']);
        //////////////////////////////////////Insert a blank data in a wp_views///////////////////////////////////////////
        $in_data1 = array('user_id' => $current_user->ID, 'post_id' => $post_id, 'views' => 0);
        $wpdb->insert('wp_views', $in_data1);
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        $msg = 'You are successfully publish a post.';
        wp_redirect(get_bloginfo('siteurl'));
        exit();
    }else {
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
            <h2 class="title">My Account</h2>
        </div>

        <!--Short By-->
        <!--Form Section-->
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form_wrap">
                <div class="form_wrap_title"><h2>Publish a Story</h2></div>
                <div class="form_cont">
                    <?php
                    if ($m != "") {
                        ?>
                        <div class="success_message"><?php echo $m; ?></div>
                    <?php } elseif ($mm != "") { ?>
                        <div class="error_message"><?php echo $mm; ?></div>
                    <?php } ?>
                </div>
                <div class="form_cont">
                    <div class="form_left">
                        <label>Select A Mocha<span>*</span>:</label>
                    </div>
                    <div class="form_right">
                        <?php
                        $categories = get_all_category_ids();
                        ?>
                        <select class="form_select" name="mocas">
                            <option value="select">Select</option>
                            <?php
                            foreach ($categories as $category) {
                                if (!in_array($category, array(1))) {
                                    $cat = get_category($category);
                                    ?>
                                    <option value="<?php echo $category; ?>" <?php if ($_POST['mocas'] == $category) echo 'selected="selected"'; ?>><?php echo $cat->name; ?></option>
                                    <?php
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <!---->
                <div class="form_cont">
                    <div class="form_left">
                        <label>Title<span>*</span>:</label>
                    </div>
                    <div class="form_right">
                        <input type="text" class="form_input" name="title" value="<?php echo $_POST['title']; ?>" />
                    </div>
                </div>
                <div class="form_cont">
                    <div class="form_left">
                        <label>Tag<span>*</span>:</label>
                    </div>
                    <div class="form_right">
                        <input type="text" class="form_input" name="tag" value="<?php echo $_POST['tag']; ?>" />
                    </div>
                </div>
                <div class="form_cont">
                    <div class="form_left">
                        <label>Small Description /Abstract<span>*</span>:</label>
                    </div>
                    <div class="form_right">
                        <textarea class="form_textarea" name="description" ><?php echo $_POST['description']; ?></textarea>
                    </div>
                </div>
                <!---->
                <div class="form_cont">
                    <div class="form_left">
                        <label>Full Story<span>*</span>:</label>
                    </div>
                    <div class="form_right">
                        <textarea class="form_textarea" name="story"><?php echo $_POST['story']; ?></textarea>
                    </div>
                </div>
                <!---->
                <div class="form_cont">
                    <div class="form_left">
                        <label>Upload Document:</label>
                    </div>
                    <div class="form_right">
                        <div class="browse_cont">
                            <input type="file" name="document" id="document" />
                            <script class="jsbin" src="<?php echo get_bloginfo('siteurl') . '/wp-content/themes/cafemocha/js/jquery.min.js'; ?>"></script>
                            <script class="jsbin" src="<?php echo get_bloginfo('siteurl') . '/wp-content/themes/cafemocha/js/upload/upload.js'; ?>"></script>
                            
                        </div>
                    </div>
                </div>
                <div class="form_cont">
                    <div class="form_left">
                    </div>
                    <div class="form_right">
                        <input type="checkbox" name="checkbox" value="1" <?php
                        if ($_POST['checkbox'] != "") {
                            echo "checked='checked'";
                        }
                        ?>/> <label>Publish story as anonymous. <span></span></label>
                    </div>
                </div>
                <div class="form_cont">
                    <div class="form_left"><label>Enter the contents of image</label><label class="mandat"> *</label></div>
                    <div class="form_right"><input type="text" name="captch" id="captch" maxlength="6" size="6"  class="form_input" /></div>
                    <div class="form_right"><img id="captcha-image-new" name="captcha-image-new" src="<?php echo get_bloginfo('url') . '/wp-content/themes/cafemocha/captcha.php' ?>"/><div class="case_sensitive">Case Sensitive</div></div>
                    <div class="form_right"><a href="javascript:void();" onClick="javascript:refresh();"  id="change-image">Not readable? Change text.</a></div>
                </div>
                <!---->
                <div class="form_cont">
                    <div class="form_left"></div>
                    <div class="form_right">
                        <input type="submit" class="form_submit" id="form_submit" value="Publish" name="Publish" />
                    </div>
                </div>
                <!---->
            </div><!--End .form_wrap-->
        </form>

        <!--End .form_wrap-->
        <!--Form Section-->
    </div>
    <!-- #content -->
</div>
<!-- #primary -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>
