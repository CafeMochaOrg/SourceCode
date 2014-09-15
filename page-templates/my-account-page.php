<?php
/**
 * Template Name: My Account Page Template
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
    wp_redirect(get_page_link(28));
}
global $current_user, $wpdb;
get_currentuserinfo();
$user_id = $current_user->ID;
$error_message = '';
if (isset($_POST['Update'])) {
    $moca = $_POST['moca'];
    $user_email = $_POST['user_email'];
    $array = serialize($moca);
    update_user_meta($current_user->ID, 'Choosed_Mocha', $array);

    $Information = $_POST['Information'];
    $name = $_POST['user_name'];
    $tablename = $wpdb->prefix . "users";
    $sql = $wpdb->prepare("UPDATE " . $tablename . " SET user_login='" . $name . "' WHERE ID=" . $user_id . "", $tablename);
    $wpdb->query($sql);
    if (!function_exists('wp_handle_upload'))
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
    $uploadedfile = $_FILES['photo'];
    //var_dump($uploadedfile);
    //echo $uploadedfile['name'];
    //die();
    if ($uploadedfile['name'] != '') {
        $upload_overrides = array('test_form' => false);
        $movefile = wp_handle_upload($uploadedfile, $upload_overrides);
        if ($movefile) {
            update_user_meta($current_user->ID, 'photo', $movefile['url']);
        }
    }
    $sql1 = $wpdb->prepare("UPDATE " . $tablename . " SET user_email='" . $user_email . "' WHERE ID=" . $user_id . "", $tablename);
    $wpdb->query($sql1);
    update_user_meta($current_user->ID, 'Alternative_User', $Information['u_name']);
    update_user_meta($current_user->ID, 'post_name', $Information['post_name']);
    update_user_meta($current_user->ID, 'Experience_In', $Information['Experience_In']);
    update_user_meta($current_user->ID, 'Other_Expertise', $Information['Other_Expertise']);
    update_user_meta($current_user->ID, 'Institiution', $Information['Institiution']);
    update_user_meta($current_user->ID, 'City', $Information['City']);
    update_user_meta($current_user->ID, 'State', $Information['State']);
    wp_redirect(get_bloginfo('siteurl') . '/my-account-2');
}
?>

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
                <div class="form_wrap_title">
                    <h2>Profile Information</h2>
                </div>
                <div class="form_cont">
                    <div class="form_left">
                        <div class="profile_pic_big"><img id="image" name="photo" src="<?php echo $current_user->photo; ?>" height="83px" width="83px" /></div>
                        <ul class="add_edit">
                            <li><a id="chng_photo" href="javascript:void(0)">Change Image</a></li>
                            <li></li>
                        </ul>
                        <script class="jsbin" src="<?php echo get_bloginfo('siteurl') . '/wp-content/themes/cafemocha/js/jquery.min.js'; ?>"></script>
                        <script class="jsbin" src="<?php echo get_bloginfo('siteurl') . '/wp-content/themes/cafemocha/js/upload/upload.js'; ?>"></script>
                        <script>
                            jQuery("#photo").hide();
                            jQuery("#chng_photo").click(function() {
                                jQuery("#photo").toggle();
                            });
                        </script>
                    </div>
                    <div class="form_right">
                        <h2>
                            <?php
                            $current_user = wp_get_current_user();
                            $post_name = $current_user->post_name;
                            if ($post_name == 1) {
                                echo $current_user->user_login;
                            } else {
                                echo $current_user->Alternative_User;
                            }
                            ?>
                        </h2>
                        <input type="file" name="photo" id="photo" onchange="readURL(this, '<?php echo $current_user->photo; ?>');"  style="display:none;" />
                    </div>
                </div>
                <!---->
                <div class="form_cont">
                    <div class="form_left">
                        <label>Click To Change Your Password : </label>
                    </div>

                    <div class="form_right">
                        <a href="<?php echo get_bloginfo('siteurl').'/change-password';?>" class='form_submit'>Change Password</a>
                    </div>
                </div>
                <div class="form_cont">
                    <div class="form_left">
                        <label>Choose Mochas To Show On Main Page:</label>
                    </div>
                    <!------------------------------------------------Choose Moca------------------------------------------------>
                    <div class="form_right">
                        <div class="select_all">Select All <input type="checkbox" id="selectall" /></div>
                        <div class="select_mochas">
                            <?php
                            @$moca = unserialize($current_user->Choosed_Mocha);
                            if ($moca == true) {//(count($moca)>0)
                                foreach ($moca as $m) {
                                    $term = get_term($m, 'category');
                                    echo "&nbsp; " . $term->name . " &nbsp;";
                                }
                            }
                            ?>
                            <script>
                            jQuery(function() {
                                jQuery("#selectall").click(function() {
                                    if (jQuery("#selectall").is(':checked')) {
                                        jQuery('.case').prop('checked', true);
                                    } else {
                                        jQuery('.case').prop('checked', false);
                                    }
                                });
                                jQuery(".case").click(function() {
                                    if (jQuery(".case").length == jQuery(".case:checked").length) {
                                        jQuery("#selectall").prop('checked', true);
                                    } else {
                                        jQuery("#selectall").prop('checked', false);
                                    }
                                });
                            });
                            </script>
                        </div>


                        <ul class="add_edit">
                            <li><a id="add" href="javascript:void(0)">Add / Remove</a></li>
                        </ul>


                        <div class="choose_mocha" id="edit_Choose_Mocha">
                            <?php $categories = get_categories(); ?>
                            <?php
                            foreach ($categories as $category) {
                                ?>
                                <label>
                                    <input class="case" type="checkbox" name="moca[<?php echo $category->term_id; ?>]" value="<?php echo $category->term_id; ?>" <?php
                                    if ($moca == true) {
                                        if (in_array($category->term_id, $moca))
                                            echo'checked="checked"';
                                    }
                                    ?> />
                                    <?php echo $category->name; ?></label>
                            <?php }
                            ?>
                        </div>

                        <script>
                            jQuery(document).ready(function() {
                                if (jQuery(".case").length == jQuery(".case:checked").length) {
                                    jQuery("#selectall").prop('checked', true);
                                } else {
                                    jQuery("#selectall").prop('checked', false);
                                }
                            });
                            jQuery("#edit_Choose_Mocha").hide();
                            jQuery("#add").click(function() {
                                jQuery("#edit_Choose_Mocha").toggle();
                            });
                        </script>
                    </div>
                    <!------------------------------------------------Choose Moca------------------------------------------------>
                </div>
                <!---->
            </div>
            <div class="form_wrap">
                <div class="form_wrap_title">
                    <h2>Optional Information</h2>
                </div>
                <!---->
                <div class="form_cont">
                    <div class="form_left">

                    </div>
                    <div class="form_right">
                        <input type="radio" name="Information[post_name]" value="1" <?php if ($current_user->post_name == '1') echo 'checked="checked"'; ?>>
                        Display Username
                        <?php if ($current_user->Alternative_User != "") { ?>
                            <label> or </label>
                            <input type="radio" name="Information[post_name]" value="0" <?php if ($current_user->post_name == '0') echo 'checked="checked"'; ?>>
                            Name 
                        <?php } ?>
                    </div>
                </div>
                <!---->
                <div class="form_cont">
                    <div class="form_left">
                        <label>User Email:</label>
                    </div>
                    <div class="form_right">
                        <input type="text" name="user_email" id="user_email" value="<?php echo $current_user->user_email; ?>" class="form_input" />
                    </div>
                </div>
                <div class="form_cont">
                    <div class="form_left">
                        <label>User Name:</label>
                    </div>
                    <div class="form_right">
                        <input type="text" name="user_name" id="Alternative_User" value="<?php echo $current_user->user_login; ?>" class="form_input" />
                    </div>
                </div>
                <div class="form_cont">
                    <div class="form_left">
                        <label>Name:</label>
                    </div>
                    <div class="form_right">
                        <input type="text" name="Information[u_name]" id="Alternative_User" value="<?php echo $current_user->Alternative_User; ?>" class="form_input" />
                    </div>
                </div>
                <!---->

                <div class="form_cont">
                    <div class="form_left">
                        <label>Experience In:</label>
                    </div>
                    <div class="form_right">
                        <input type="text" name="Information[Experience_In]" id="Experience_In" value="<?php echo $current_user->Experience_In; ?>"   class="form_input" />
                    </div>
                </div>
                <!---->
                <div class="form_cont">
                    <div class="form_left">
                        <label>Other Expertise:</label>
                    </div>
                    <div class="form_right">
                        <input type="text" name="Information[Other_Expertise]" id="Other_Expertise" value="<?php echo $current_user->Other_Expertise; ?>"   class="form_input" />
                    </div>
                </div>
                <!---->
                <div class="form_cont">
                    <div class="form_left">
                        <label>Institution:</label>
                    </div>
                    <div class="form_right">
                        <input type="text" name="Information[Institiution]" id="Institiution" value="<?php echo $current_user->Institiution; ?>" class="form_input" />
                    </div>
                </div>
                <!---->
                <div class="form_cont">
                    <div class="form_left">
                        <label>City:</label>
                    </div>
                    <div class="form_right">
                        <input type="text" name="Information[City]" id="City" value="<?php echo $current_user->City; ?>" class="form_input" />
                    </div>
                </div>
                <!---->
                <div class="form_cont">
                    <div class="form_left">
                        <label>State:</label>
                    </div>
                    <div class="form_right">
                        <input type="text" name="Information[State]" id="State" value="<?php echo $current_user->State; ?>" class="form_input" />
                    </div>
                </div>
                <!---->

            </div>
            <div class="form_wrap">
                <div class="form_wrap_title">
                    <h2>Block User</h2>
                </div>
                <div class="form_cont">
                    <div class="form_left">
                        <label>Name:</label>
                    </div>
                    <div class="form_right">
                        <input type="text" name="blck_nm" id="blck_nm" class="form_input" />
                        <input type="hidden" name="blck_id" id="blck_id" />
                        <input type="button" class="form_submit" value="Block" onclick="block()" />
                    </div>
                </div>
                <!---->
                <div class="form_cont">
                    <div class="form_left"> </div>
                    <div class="form_right">
                        <div class="block_cont">
                            <div class="block_cont_title">Name</div>
                            <div id="block_cont_find">
                                <select id="unblck_id" size="5">
                                    <?php
                                    $blocked_users = unserialize($current_user->blocked_users);
                                    if ($blocked_users != false) {
                                        foreach ($blocked_users as $b) {
                                            ?>
                                            <option id="<?php echo $b; ?>" value="<?php echo $b; ?>">
                                                <?php
                                                $user_info = get_userdata($b);
                                                echo $user_info->display_name;
                                                ?>
                                            </option>
                                            <?php
                                        }
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <!--<input type="hidden" name="unblck_id" id="unblck_id" />-->
                        <input type="button" class="form_submit" id="Unblock" value="Unblock" />
                    </div>
                </div>
                <!--------------------------------------------------------------------------------------------------------------------------------------->
                <script class="jsbin" src="<?php echo get_bloginfo('siteurl') . '/wp-content/themes/cafemocha/js/cafemocha/block.js'; ?>"></script>
                <script class="jsbin" src="<?php echo get_bloginfo('siteurl') . '/wp-content/themes/cafemocha/js/autocomplete/jquery-1.9.1.js'; ?>"></script>
                <script class="jsbin" src="<?php echo get_bloginfo('siteurl') . '/wp-content/themes/cafemocha/js/autocomplete/jquery-ui.js'; ?>"></script>
                <link rel="stylesheet" href="<?php echo get_bloginfo('siteurl') . '/wp-content/themes/cafemocha/js/autocomplete/style.css'; ?>"/>
                <link rel="stylesheet" href="<?php echo get_bloginfo('siteurl') . '/wp-content/themes/cafemocha/js/autocomplete/jquery-ui.css'; ?>"/>
                <script>
                            var availableTags = Array();
                            $(function() {
                                //var availableTags =Array();
<?php
$blocked_users = unserialize($current_user->blocked_users);
array_push($blocked_users, '1', $current_user->ID);
$users = get_users(array('exclude' => $blocked_users));
foreach ($users as $u) {
    ?>
                                    availableTags.push({value: "<?php echo $u->display_name; ?>", id: "<?php echo $u->id; ?>"})
    <?php
}
?>
                                $("#blck_nm").autocomplete({
                                    source: availableTags,
                                    select: function(event, ui) {
                                        document.getElementById("blck_id").value = ui.item.id;
                                    }
                                });
                            });
                            jQuery("#Unblock").click(function() {
                                //alert(jQuery(availableTags).first());
                                //alert(availableTags.get(1));
                                var id = jQuery("#unblck_id").val();
                                unblock(id);
                            });
                </script>
                <!--------------------------------------------------------------------------------------------------------------------------------------->
                <!---->
                <div class="form_cont">
                    <div class="form_left"></div>
                    <div class="form_right">
                        <input type="submit" class="form_submit" name="Update" value="Update" />
                    </div>
                </div>
            </div>
        </form>
        <!--End .form_wrap-->
        <!--Form Section-->
    </div>
    <!-- #content -->
</div>
<!-- #primary -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>
