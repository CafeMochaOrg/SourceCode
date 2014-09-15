<?php
/**
 * Template Name: Profile Page Template
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

require_once(get_template_directory() . '/custom_functions/sipping.php');
global $current_user;
get_currentuserinfo();
$user_id = $current_user->id;
if ($_GET['user_id']) {
    $user_id = $_GET['user_id'];
    $user_photo = get_user_meta($user_id, 'photo', TRUE);

    $post_name = get_user_meta($user_id, 'post_name', TRUE);
    if ($post_name == 1) {
        $user = get_userdata($user_id);
        $user_name = $user->user_login;
    } else {
        $user_name = get_user_meta($user_id, 'Alternative_User', TRUE);
    }
} else {
    global $current_user;
    get_currentuserinfo();
    $user_id = $current_user->id;
    $user_photo = $current_user->photo;
    $post_name = get_user_meta($user_id, 'post_name', TRUE);
    if ($post_name == 1) {
        $user = get_userdata($user_id);
        $user_name = $user->user_login;
    } else {
        $user_name = get_user_meta($user_id, 'Alternative_User', TRUE);
    }
}
?>
<script class="jsbin" src="<?php echo get_bloginfo('siteurl') . '/wp-content/themes/cafemocha/js/cafemocha/sip.js'; ?>"></script>
<div id="primary" class="site-content">
    <!--<div id="loading" style="background:#EEE;position:absolute;"><img id="image" name="photo" height="200px" width="200px" src="<?php //echo get_bloginfo('siteurl') . '/wp-content/uploads/2013/09/ajax-loader_fine.gif';           ?>"/></div>
    <script>jQuery("#loading").hide();</script>-->
    <div id="content" role="main">
        <!--        <div class="short_by">
                    <ul>
                        <li><a href="<?php //echo get_page_link(92) . '?sectionby=d'  ?>">Daily</a></li>
                        <li><a href="<?php //echo get_page_link(92) . '?sectionby=w'  ?>">Weekly</a></li>
                        <li><a href="<?php //echo get_page_link(92) . '?sectionby=m'  ?>">Monthly</a></li>
                    </ul>
                </div>-->
        <!--Short By-->
        <div class="short_by">
            <h2 class="title"><?php echo $user_name; ?> Profile Page</h2>
        </div>
        <!--Short By-->
        <!--Form Section-->
        <?php if ($user_photo) { ?>
            <div class="form_wrap">
                <div class="form_cont">
                    <div class="form_left">
                        <div class="profile_pic_big"><img id="image" name="photo" src="<?php echo $user_photo; ?>" height="83px" width="83px" /></div>
                    </div>
                    <div class="form_right"><h2><?php echo $user_name; ?></h2></div>
                </div>
            </div>
        <?php } ?>
        <!--End .form_wrap-->
        <script>
            jQuery(document).ready(function() {
                jQuery('.userupload').click(function() {

                    jQuery('.user_sip').slideUp();
                    jQuery('.user_cmt').slideUp();
                    jQuery('.user_spt').slideUp();
                    jQuery('.user_upload').slideToggle();
                    jQuery('#u').text('Uploaded');

                });
                jQuery('.usersipped').click(function() {

                    jQuery('.user_upload').slideUp();
                    jQuery('.user_cmt').slideUp();
                    jQuery('.user_spt').slideUp();
                    jQuery('.user_sip').slideToggle();
                    jQuery('#u').text('Sipped');
                });
                jQuery('.usercommented').click(function() {
                    jQuery('.user_upload').slideUp();
                    jQuery('.user_sip').slideUp();
                    jQuery('.user_spt').slideUp();
                    jQuery('.user_cmt').slideToggle();
                    jQuery('#u').text('Commented');

                });
                jQuery('.userspit').click(function() {

                    jQuery('.user_upload').slideUp();
                    jQuery('.user_sip').slideUp();
                    jQuery('.user_cmt').slideUp();
                    jQuery('.user_spt').slideToggle();
                    jQuery('#u').text('Spit');
                });

                jQuery('.user_cmt').slideUp();
                jQuery('.user_sip').slideUp();
                jQuery('.user_spt').slideUp();

            });

        </script>
        <input type="button" name="userupload" id="userupload" class="userupload" value="Stories uploaded" />
        <input type="button" name="usersipped" id="usersipped" class="usersipped" value="Stories sipped" />
        <input type="button" name="userspit" id="userspit" class="userspit" value="Stories spit" />
        <input type="button" name="usercommented" id="usercommented" class="usercommented" value="Stories commented" />
        <?php
        if (isset($_GET['sectionby'])) {
            ?>
            <!--------------------------------------------------------------List of upload by this user-----Start---------------------------------------------------------------->
            <div class="user_upload" id="user_upload">
                <!--Single Post Section-->
                <?php
                global $wpdb;
                $query = "SELECT * FROM wp_posts where post_type='post' and post_author=$user_id ORDER BY post_date";
                $rs = $wpdb->get_results($query);

                $post_array = get_posts(array('post_type' => 'post', 'post_status' => 'publish', 'author' => $user_id));
                if (count($post_array) != 0) {
                    ?>
                    <div id="profile_heading">
                        Stories that  <?php echo $user_name; ?>  uploaded
                    </div>
                    <?php
                }
                $start_date;
                $end_date;
                $last_date = mysql2date('d-m-Y', $rs[0]->post_date);
                $date = date('d-m-Y');
                $x = 0;
                $end_date = $date;
                //x_month_range($start_date, $end_date, $date);
                while (strtotime($last_date) <= strtotime($end_date)) {
                    $x++;
                    $query = "SELECT * FROM wp_posts WHERE post_type='post' and post_author=$user_id and post_status='publish'";
                    //////////////////////////////////sectionby//////////////////////////////////////////
                    if (isset($_GET['sectionby'])) {
                        if ($_GET['sectionby'] == 'd') {
                            $query = $query . " and post_date like '%" . date("Y-m-d", strtotime($date)) . "%'";
                            $end_date = $date;
                        } elseif ($_GET['sectionby'] == 'w') {
                            x_week_range($start_date, $end_date, $date);
                            $query = $query . " and post_date BETWEEN '" . date("Y-m-d", strtotime($start_date)) . "' AND '" . date("Y-m-d", strtotime($end_date) + 86400) . "'";
                        } elseif ($_GET['sectionby'] == 'm') {
                            x_month_range($start_date, $end_date, $date);
                            $query = $query . " and post_date BETWEEN '" . date("Y-m-d", strtotime($start_date)) . "' AND '" . date("Y-m-d", strtotime($end_date) + 86400) . "'";
                        } else {
                            $query = $query . " and post_date like '%" . date("Y-m-d", strtotime($date)) . "%'";
                            $end_date = $date;
                        }
                    }
                    else
                        $query = $query . " and post_date like '%" . date("Y-m-d", strtotime($date)) . "%'";
                    if (isset($_GET['sectionby'])) {
                        if ($_GET['sectionby'] == 'd')
                            $previousdate = date('d-m-Y', strtotime('-1 days', strtotime($date)));
                        elseif ($_GET['sectionby'] == 'w') {
                            x_week_range($pre_start_date, $pre_end_date, date('d-m-Y', strtotime('-7 days', strtotime($date))));
                            $previousdate = $pre_end_date;
                        } elseif ($_GET['sectionby'] == 'm')
                            $previousdate = date('d-m-Y', strtotime('-1 month', strtotime($date)));
                        else
                            $previousdate = date('d-m-Y', strtotime('-1 days', strtotime($date)));
                    }
                    else
                        $previousdate = date('d-m-Y', strtotime('-1 days', strtotime($date)));

                    $rs = $wpdb->get_results($query);
                    //echo $query."<br><br>";
                    $date = $previousdate;
                    foreach ($rs as $r) {
                        $post_datetime = $r->post_date;
                        $explode = explode(' ', $post_datetime);
                        $newDate = $explode[0];
                        $post_date = date('M d,Y', strtotime($post_datetime));
                        $meta = get_post_meta($r->ID);
                        $args = array(
                            'post_id' => $r->ID, // use post_id, not post_ID
                        );
                        $comments = get_comments($args);
                        $views = $wpdb->get_results("select SUM(views) as views from wp_views where post_id='$r->ID'");
                        $view = $views[0]->views;
                        $term_name = get_term_by('id', $meta[mocas][0], 'category')->slug;
                        if ($meta[anonymous][0] == "") {
                            ?>
                            <div class="normal_post_war">
                                <div class="top_rating_cont">
                                    <!--Categori Image-->
                                    <div class="top_post_pic_cont"><?php $userdata = get_userdata($r->post_author); ?>
                                        <div class="top_cat_pic"><img src="<?php
                                            if (z_taxonomy_image_url($meta[mocas][0]) != false)
                                                echo z_taxonomy_image_url($meta[mocas][0]);
                                            else
                                                echo get_site_url() . "/wp-content/themes/cafemocha/custom-images/no-image.jpg";
                                            ?>" height="48px" width="48px"></div>
                                    </div>
                                    <!--Categori Image-->
                                    <!-- Post Description -->
                                    <div class="top_post_des">
                                        <a href="<?php echo get_permalink($r->ID); ?>"><?php echo $r->post_title; ?></a>
                                        <?php
                                        if ($meta[attached_file][0] != '') {
                                            ?>
                                            <a href="<?php //echo $meta[attached_file][0];             ?>"></a>
                                        <?php }
                                        ?>
                                    </div>
                                    <!-- Post Description -->
                                    <!-- Sip -->
                                    <div class="top_post_sip">
                                        <ul>
                                            <?php if (is_user_logged_in()) { ?>
                                                <li><a href="javascript:void(0)" class="vote_up" onclick="sip_it('<?php echo $r->ID; ?>')"></a>Sip it</li>
                                                <li><a href="javascript:void(0)" class="vote_down" onclick="spit_it('<?php echo $r->ID; ?>')"></a>Spit it</li>
                                            <?php } else { ?>
                                                <li><a href="javascript:void(0)" class="vote_up" onclick="login_msg()"></a>Sip it</li>
                                                <li><a href="javascript:void(0)" class="vote_down" onclick="login_msg()"></a>Spit it</li>
                                            <?php } ?>
                                        </ul>
                                    </div>
                                    <!-- Sip -->
                                </div>
                                <!--End .top_rating_cont-->
                                <div class="normal_rating_bottom_cont">
                                    <ul>
                                        <li><a href="<?php echo get_bloginfo('url') . '/category/' . $term_name; ?>"><?php echo get_term_by('id', $meta[mocas][0], 'category')->name; ?> </a></li>
                                        <li class="author">
                                            <div class="publishby">
                                                <p>Published By</p>
                                                <a href="<?php
                                                if ($meta[anonymous][0] == "") {
                                                    echo get_bloginfo('url') . '/profile-page-template/?user_id=' . $r->post_author;
                                                } else {
                                                    echo 'javascript:void();';
                                                }
                                                ?>"><?php
                                                       if ($meta[anonymous][0] != "") {
                                                           echo 'Anonymous';
                                                       } else {
                                                           $post_name = $userdata->post_name;
                                                           if ($post_name == 1) {
                                                               echo $userdata->user_login;
                                                           } else {
                                                               echo $userdata->Alternative_User;
                                                           }
                                                       }
                                                       ?></a>
                                            </div>
                                            <p>Post Date</p>
                                            <a><?php echo $post_date; ?></a>
                                        </li>
                                        <?php
                                        $sip_it = get_sipit($r->ID);
                                        $spit_it = get_spitit($r->ID);
                                        $sip = $sip_it - $spit_it;
                                        ?>
                                        <li style="position:relative;"><a href="#" class="sip sip_up_<?php echo $r->ID; ?>"><?php echo $sip; ?></a>
                                            <ul style="display:none;">

                                                <li class="sip_pop"><p>Sip it</p><a href="#" class="sip_it_up_<?php echo $r->ID; ?>"><?php echo $sip_it; ?></a></li>
                                                <li class="spit_pop"><p>Spit it</p><a href="#" class="spit_it_up_<?php echo $r->ID; ?>"><?php echo $spit_it; ?></a></li>
                                            </ul>
                                        </li>
                                        <li><a href="#" class="view"><?php
                                                if ($view != "") {
                                                    echo $view;
                                                } else {
                                                    echo '0';
                                                }
                                                ?> views</a></li>
                                        <li><a href="<?php echo get_permalink($r->ID); ?>" class="comment"><?php echo count($comments); ?> comments</a></li>
                                    </ul>
                                </div>
                                <!--End .top_rating_bottom_cont-->
                            </div>
                            <!-- Post Section -->
                            <?php
                        }
                    }
                }
                ?>
                <!--Single Post Section-->
                <!--Single Post Section-->

                <!--Single Post Section-->
            </div><!--End .user_upload-->
            <!--------------------------------------------------------------List of upload by this user-----End---------------------------------------------------------------->
            <!--------------------------------------------------------------List of Sip it by this user-----Start---------------------------------------------------------------->
            <div id="user_sipping" class="user_sip">
                <?php
                //$query = "SELECT * FROM wp_posts p,wp_sipping s WHERE p.id=s.post_id AND p.post_type='post' AND s.sip_it=1 AND s.sipper_id='" . $user_id . "' ORDER BY (sip_it+spit_it) DESC";
                global $wpdb;
                $query = "SELECT * FROM wp_posts p,wp_sipping s WHERE p.id=s.post_id AND p.post_type='post' AND s.sip_it=1 AND s.sipper_id='" . $user_id . "' ORDER BY post_date";
                $rs = $wpdb->get_results($query);
                if (count($rs) != 0) {
                    ?>
                    <div id="profile_heading">
                        Stories that  <?php echo $user_name; ?>  sipped
                    </div>
                    <?php
                }
                $start_date;
                $end_date;
                $last_date = mysql2date('d-m-Y', $rs[0]->post_date);
                $date = date('d-m-Y');
                $x = 0;
                $end_date = $date;
                //x_month_range($start_date, $end_date, $date);
                while (strtotime($last_date) <= strtotime($end_date)) {
                    $x++;
                    $query = "SELECT * FROM wp_posts p,wp_sipping s WHERE p.id=s.post_id AND p.post_type='post' AND s.sip_it=1 AND s.sipper_id='" . $user_id . "'";
                    //////////////////////////////////sectionby//////////////////////////////////////////
                    if (isset($_GET['sectionby'])) {
                        if ($_GET['sectionby'] == 'd') {
                            $query = $query . " and post_date like '%" . date("Y-m-d", strtotime($date)) . "%'";
                            $end_date = $date;
                        } elseif ($_GET['sectionby'] == 'w') {
                            x_week_range($start_date, $end_date, $date);
                            $query = $query . " and post_date BETWEEN '" . date("Y-m-d", strtotime($start_date)) . "' AND '" . date("Y-m-d", strtotime($end_date) + 86400) . "'";
                        } elseif ($_GET['sectionby'] == 'm') {
                            x_month_range($start_date, $end_date, $date);
                            $query = $query . " and post_date BETWEEN '" . date("Y-m-d", strtotime($start_date)) . "' AND '" . date("Y-m-d", strtotime($end_date) + 86400) . "'";
                        } else {
                            $query = $query . " and post_date like '%" . date("Y-m-d", strtotime($date)) . "%'";
                            $end_date = $date;
                        }
                    }
                    else
                        $query = $query . " and post_date like '%" . date("Y-m-d", strtotime($date)) . "%'";
                    if (isset($_GET['sectionby'])) {
                        if ($_GET['sectionby'] == 'd')
                            $previousdate = date('d-m-Y', strtotime('-1 days', strtotime($date)));
                        elseif ($_GET['sectionby'] == 'w') {
                            x_week_range($pre_start_date, $pre_end_date, date('d-m-Y', strtotime('-7 days', strtotime($date))));
                            $previousdate = $pre_end_date;
                        } elseif ($_GET['sectionby'] == 'm')
                            $previousdate = date('d-m-Y', strtotime('-1 month', strtotime($date)));
                        else
                            $previousdate = date('d-m-Y', strtotime('-1 days', strtotime($date)));
                    }
                    else
                        $previousdate = date('d-m-Y', strtotime('-1 days', strtotime($date)));

                    $rs = $wpdb->get_results($query);
                    //echo $query."<br><br>";
                    $date = $previousdate;
                    foreach ($rs as $r) {
                        $post_datetime = $r->post_date;
                        $explode = explode(' ', $post_datetime);
                        $newDate = $explode[0];
                        $post_date = date('M d,Y', strtotime($post_datetime));
                        $meta = get_post_meta($r->ID);
                        $args = array(
                            'post_id' => $r->ID, // use post_id, not post_ID
                        );
                        $comments = get_comments($args);
                        $views = $wpdb->get_results("select SUM(views) as views from wp_views where post_id='$r->ID'");
                        $view = $views[0]->views;
                        $term_name = get_term_by('id', $meta[mocas][0], 'category')->slug;
                        ?>
                        <div class="user_comment">
                            <!--Single Post Section-->
                            <div class="normal_post_war_two">
                                <div class="top_rating_cont">
                                    <!--Categori Image-->
                                    <div class="top_post_pic_cont"><?php $userdata = get_userdata($r->post_author); ?>
                                        <div class="top_cat_pic"><img src="<?php
                                            if (z_taxonomy_image_url($meta[mocas][0]) != false)
                                                echo z_taxonomy_image_url($meta[mocas][0]);
                                            else
                                                echo get_site_url() . "/wp-content/themes/cafemocha/custom-images/no-image.jpg";
                                            ?>" height="48px" width="48px"></div>
                                    </div>
                                    <!--Categori Image-->
                                    <!-- Post Description -->
                                    <div class="top_post_des">
                                        <h2><a href="<?php echo get_permalink($r->ID); ?>"><?php echo $r->post_title; ?></a></h2>
                                        <p><?php echo $meta[story][0]; ?></p>
                                        <?php if ($meta[attached_file][0] != '') {
                                            ?>
                                            <a href="<?php //echo $meta[attached_file][0];            ?>"></a>
                                        <?php }
                                        ?>
                                    </div>
                                    <!-- Post Description -->
                                    <!-- Sip -->
                                    <div class="top_post_sip">
                                        <ul>
            <?php if (is_user_logged_in()) { ?>
                                                <li><a href="javascript:void(0)" class="vote_up" onclick="sip_it('<?php echo $r->ID; ?>')"></a>Sip it</li>
                                                <li><a href="javascript:void(0)" class="vote_down" onclick="spit_it('<?php echo $r->ID; ?>')"></a>Spit</li>
            <?php } else { ?>
                                                <li><a href="javascript:void(0)" class="vote_up" onclick="login_msg()"></a>Sip it</li>
                                                <li><a href="javascript:void(0)" class="vote_down" onclick="login_msg()"></a>Spit</li>
            <?php } ?>
                                        </ul>
                                    </div>
                                    <!-- Sip -->
                                </div>
                                <!--End .top_rating_cont-->
                                <div class="normal_rating_bottom_cont_two">
                                    <ul>
                                        <li><a href="<?php echo get_bloginfo('url') . '/category/' . $term_name; ?>"><?php echo get_term_by('id', $meta[mocas][0], 'category')->name; ?> </a></li>
                                        <li class="author">
                                            <div class="publishby">
                                                <p>Published By</p>
                                                <a href="<?php
                                                if ($meta[anonymous][0] == "") {
                                                    echo get_bloginfo('url') . '/profile-page-template/?user_id=' . $r->post_author;
                                                } else {
                                                    echo 'javascript:void();';
                                                }
                                                ?>"><?php
                                                       if ($meta[anonymous][0] != "") {
                                                           echo 'Anonymous';
                                                       } else {
                                                           $post_name = $userdata->post_name;
                                                           if ($post_name == 1) {
                                                               echo $userdata->user_login;
                                                           } else {
                                                               echo $userdata->Alternative_User;
                                                           }
                                                       }
                                                       ?></a>
                                            </div>
                                            <p>Post Date</p>
                                            <a><?php echo $post_date; ?></a>
                                        </li>
                                        <?php
                                        $sip_it = get_sipit($r->ID);
                                        $spit_it = get_spitit($r->ID);
                                        $sip = $sip_it - $spit_it;
                                        ?>
                                        <li style="position:relative;"><a href="#" class="sip sip_up_<?php echo $r->ID; ?>"><?php echo $sip; ?></a>
                                            <ul style="display:none;">
                                                <li class="sip_pop"><p>Sip it</p><a href="#" class="sip_it_up_<?php echo $r->ID; ?>"><?php echo $sip_it; ?></a></li>
                                                <li class="spit_pop"><p>Spit it</p><a href="#" class="spit_it_up_<?php echo $r->ID; ?>"><?php echo $spit_it; ?></a></li>
                                            </ul>
                                        </li>
                                        <li><a href="#" class="view"><?php
                                                if ($view != "") {
                                                    echo $view;
                                                } else {
                                                    echo '0';
                                                }
                                                ?> views</a></li>
                                        <li><a href="#" class="comment"><?php echo count($comments); ?> comments</a></li>
                                    </ul>
                                </div>
                                <!--End .top_rating_bottom_cont-->
                            </div>
                            <!--Single Post Section-->
                        </div><!--End .user_upload-->
                        <?php
                    }
                }
                ?>
            </div>
            <!--------------------------------------------------------------List of Sip it by this user-----End---------------------------------------------------------------->

            <!--------------------------------------------------------------List of Spit it by this user-----Start---------------------------------------------------------------->
            <div id="user_spit" class="user_spt">
                <?php
                //$query = "SELECT * FROM wp_posts p,wp_sipping s WHERE p.id=s.post_id AND p.post_type='post' AND s.spit_it=1 AND s.sipper_id='" . $user_id . "' ORDER BY (sip_it+spit_it) DESC";
                global $wpdb;
                $query = "SELECT * FROM wp_posts p,wp_sipping s WHERE p.id=s.post_id AND p.post_type='post' AND s.spit_it=1 AND s.sipper_id='" . $user_id . "' ORDER BY post_date";
                $rs = $wpdb->get_results($query);
                if (count($rs) != 0) {
                    ?>
                    <div id="profile_heading">
                        Stories that  <?php echo $user_name; ?>  spit
                    </div>
                    <?php
                }
                $start_date;
                $end_date;
                $last_date = mysql2date('d-m-Y', $rs[0]->post_date);
                $date = date('d-m-Y');
                $x = 0;
                $end_date = $date;
                //x_month_range($start_date, $end_date, $date);
                while (strtotime($last_date) <= strtotime($end_date)) {
                    $x++;
                    $query = "SELECT * FROM wp_posts p,wp_sipping s WHERE p.id=s.post_id AND p.post_type='post' AND s.spit_it=1 AND s.sipper_id='" . $user_id . "'";
                    //////////////////////////////////sectionby//////////////////////////////////////////
                    if (isset($_GET['sectionby'])) {
                        if ($_GET['sectionby'] == 'd') {
                            $query = $query . " and post_date like '%" . date("Y-m-d", strtotime($date)) . "%'";
                            $end_date = $date;
                        } elseif ($_GET['sectionby'] == 'w') {
                            x_week_range($start_date, $end_date, $date);
                            $query = $query . " and post_date BETWEEN '" . date("Y-m-d", strtotime($start_date)) . "' AND '" . date("Y-m-d", strtotime($end_date) + 86400) . "'";
                        } elseif ($_GET['sectionby'] == 'm') {
                            x_month_range($start_date, $end_date, $date);
                            $query = $query . " and post_date BETWEEN '" . date("Y-m-d", strtotime($start_date)) . "' AND '" . date("Y-m-d", strtotime($end_date) + 86400) . "'";
                        } else {
                            $query = $query . " and post_date like '%" . date("Y-m-d", strtotime($date)) . "%'";
                            $end_date = $date;
                        }
                    }
                    else
                        $query = $query . " and post_date like '%" . date("Y-m-d", strtotime($date)) . "%'";
                    if (isset($_GET['sectionby'])) {
                        if ($_GET['sectionby'] == 'd')
                            $previousdate = date('d-m-Y', strtotime('-1 days', strtotime($date)));
                        elseif ($_GET['sectionby'] == 'w') {
                            x_week_range($pre_start_date, $pre_end_date, date('d-m-Y', strtotime('-7 days', strtotime($date))));
                            $previousdate = $pre_end_date;
                        } elseif ($_GET['sectionby'] == 'm')
                            $previousdate = date('d-m-Y', strtotime('-1 month', strtotime($date)));
                        else
                            $previousdate = date('d-m-Y', strtotime('-1 days', strtotime($date)));
                    }
                    else
                        $previousdate = date('d-m-Y', strtotime('-1 days', strtotime($date)));

                    $rs = $wpdb->get_results($query);
                    //echo $query."<br><br>";
                    $date = $previousdate;
                    foreach ($rs as $r) {
                        $post_datetime = $r->post_date;
                        $explode = explode(' ', $post_datetime);
                        $newDate = $explode[0];
                        $post_date = date('M d,Y', strtotime($post_datetime));
                        $meta = get_post_meta($r->ID);
                        $args = array(
                            'post_id' => $r->ID, // use post_id, not post_ID
                        );
                        $comments = get_comments($args);
                        $views = $wpdb->get_results("select SUM(views) as views from wp_views where post_id='$r->ID'");
                        $view = $views[0]->views;
                        $term_name = get_term_by('id', $meta[mocas][0], 'category')->slug;
                        ?>
                        <div class="user_comment">
                            <!--Single Post Section-->
                            <div class="normal_post_war_two">
                                <div class="top_rating_cont">
                                    <!--Categori Image-->
                                    <div class="top_post_pic_cont"><?php $userdata = get_userdata($r->post_author); ?>
                                        <div class="top_cat_pic"><img src="<?php
                                            if (z_taxonomy_image_url($meta[mocas][0]) != false)
                                                echo z_taxonomy_image_url($meta[mocas][0]);
                                            else
                                                echo get_site_url() . "/wp-content/themes/cafemocha/custom-images/no-image.jpg";
                                            ?>" height="48px" width="48px"></div>
                                    </div>
                                    <!--Categori Image-->
                                    <!-- Post Description -->
                                    <div class="top_post_des">
                                        <h2><a href="<?php echo get_permalink($r->ID); ?>"><?php echo $r->post_title; ?></a></h2>
                                        <p><?php echo $meta[story][0]; ?></p>
                                        <?php if ($meta[attached_file][0] != '') {
                                            ?>
                                            <a href="<?php //echo $meta[attached_file][0];           ?>"></a>
                                        <?php }
                                        ?>
                                    </div>
                                    <!-- Post Description -->
                                    <!-- Sip -->
                                    <div class="top_post_sip">
                                        <ul>
            <?php if (is_user_logged_in()) { ?>
                                                <li><a href="javascript:void(0)" class="vote_up" onclick="sip_it('<?php echo $r->ID; ?>')"></a>Sip it</li>
                                                <li><a href="javascript:void(0)" class="vote_down" onclick="spit_it('<?php echo $r->ID; ?>')"></a>Spit it</li>
            <?php } else { ?>
                                                <li><a href="javascript:void(0)" class="vote_up" onclick="login_msg()"></a>Sip it</li>
                                                <li><a href="javascript:void(0)" class="vote_down" onclick="login_msg()"></a>Spit it</li>
            <?php } ?>
                                        </ul>
                                    </div>
                                    <!-- Sip -->
                                </div>
                                <!--End .top_rating_cont-->
                                <div class="normal_rating_bottom_cont_two">
                                    <ul>
                                        <li><a href="<?php echo get_bloginfo('url') . '/category/' . $term_name; ?>"><?php echo get_term_by('id', $meta[mocas][0], 'category')->name; ?> </a></li>
                                        <li class="author">
                                            <div class="publishby">
                                                <p>Published By</p>
                                                <a href="<?php
                                                if ($meta[anonymous][0] == "") {
                                                    echo get_bloginfo('url') . '/profile-page-template/?user_id=' . $r->post_author;
                                                } else {
                                                    echo 'javascript:void();';
                                                }
                                                ?>"><?php
                                                       if ($meta[anonymous][0] != "") {
                                                           echo 'Anonymous';
                                                       } else {
                                                           $post_name = $userdata->post_name;
                                                           if ($post_name == 1) {
                                                               echo $userdata->user_login;
                                                           } else {
                                                               echo $userdata->Alternative_User;
                                                           }
                                                       }
                                                       ?></a>
                                            </div>
                                            <p>Post Date</p>
                                            <a><?php echo $post_date; ?></a>
                                        </li>
                                        <?php
                                        $sip_it = get_sipit($r->ID);
                                        $spit_it = get_spitit($r->ID);
                                        $sip = $sip_it - $spit_it;
                                        ?>
                                        <li style="position:relative;"><a href="#" class="sip sip_up_<?php echo $r->ID; ?>"><?php echo $sip; ?></a>
                                            <ul style="display:none;">
                                                <li class="sip_pop"><p>Sip it</p><a href="#" class="sip_it_up_<?php echo $r->ID; ?>"><?php echo $sip_it; ?></a></li>
                                                <li class="spit_pop"><p>Spit it</p><a href="#" class="spit_it_up_<?php echo $r->ID; ?>"><?php echo $spit_it; ?></a></li>
                                            </ul>
                                        </li>
                                        <li><a href="#" class="view"><?php
                                                if ($view != "") {
                                                    echo $view;
                                                } else {
                                                    echo '0';
                                                }
                                                ?> views</a></li>
                                        <li><a href="#" class="comment"><?php echo count($comments); ?> comments</a></li>
                                    </ul>
                                </div>
                                <!--End .top_rating_bottom_cont-->
                            </div>
                            <!--Single Post Section-->
                        </div><!--End .user_upload-->
                        <?php
                    }
                }
                ?>
            </div>
            <!--------------------------------------------------------------List of Spit it by this user-----End----------------------------------------------------------->

            <!--------------------------------------------------------------List of comments by this user-----Start---------------------------------------------------------------->
            <div id="user_cmt" class="user_cmt">
                <?php
                //$query = "SELECT * FROM wp_posts p,wp_comments c WHERE p.id=c.comment_post_ID AND p.post_type='post' AND c.user_id=$current_user->id GROUP BY c.comment_post_ID";


                global $wpdb;
                $query = "SELECT * FROM wp_posts p,wp_comments c WHERE p.id=c.comment_post_ID AND p.post_type='post' AND c.user_id=$current_user->id GROUP BY c.comment_post_ID ORDER BY post_date";
                $rs = $wpdb->get_results($query);
                if (count($rs) != 0) {
                    ?>
                    <div id="profile_heading">
                        Stories that  <?php echo $user_name; ?>  commented on
                    </div>
                    <?php
                }
                $start_date;
                $end_date;
                $last_date = mysql2date('d-m-Y', $rs[0]->post_date);
                $date = date('d-m-Y');
                $x = 0;
                $end_date = $date;
                //x_month_range($start_date, $end_date, $date);
                while (strtotime($last_date) <= strtotime($end_date)) {
                    $x++;
                    $query = "SELECT * FROM wp_posts p,wp_comments c WHERE p.id=c.comment_post_ID AND p.post_type='post' AND c.user_id=$current_user->id  ";
                    //////////////////////////////////sectionby//////////////////////////////////////////
                    if (isset($_GET['sectionby'])) {
                        if ($_GET['sectionby'] == 'd') {
                            $query = $query . " and post_date like '%" . date("Y-m-d", strtotime($date)) . "%'";
                            $end_date = $date;
                        } elseif ($_GET['sectionby'] == 'w') {
                            x_week_range($start_date, $end_date, $date);
                            $query = $query . " and post_date BETWEEN '" . date("Y-m-d", strtotime($start_date)) . "' AND '" . date("Y-m-d", strtotime($end_date) + 86400) . "'";
                        } elseif ($_GET['sectionby'] == 'm') {
                            x_month_range($start_date, $end_date, $date);
                            $query = $query . " and post_date BETWEEN '" . date("Y-m-d", strtotime($start_date)) . "' AND '" . date("Y-m-d", strtotime($end_date) + 86400) . "'";
                        } else {
                            $query = $query . " and post_date like '%" . date("Y-m-d", strtotime($date)) . "%'";
                            $end_date = $date;
                        }
                    }
                    else
                        $query = $query . " and post_date like '%" . date("Y-m-d", strtotime($date)) . "%'";
                    if (isset($_GET['sectionby'])) {
                        if ($_GET['sectionby'] == 'd')
                            $previousdate = date('d-m-Y', strtotime('-1 days', strtotime($date)));
                        elseif ($_GET['sectionby'] == 'w') {
                            x_week_range($pre_start_date, $pre_end_date, date('d-m-Y', strtotime('-7 days', strtotime($date))));
                            $previousdate = $pre_end_date;
                        } elseif ($_GET['sectionby'] == 'm')
                            $previousdate = date('d-m-Y', strtotime('-1 month', strtotime($date)));
                        else
                            $previousdate = date('d-m-Y', strtotime('-1 days', strtotime($date)));
                    }
                    else
                        $previousdate = date('d-m-Y', strtotime('-1 days', strtotime($date)));
                    $query = $query . "GROUP BY c.comment_post_ID";
                    $rs = $wpdb->get_results($query);
                    //echo $query."<br><br>";
                    $date = $previousdate;
                    //echo $query."<br>".count($myrows);
                    //$posts_array = get_posts(array('post_type' => 'publishment', 'post_status' => 'publish', 'author'=> $current_user->id));
                    foreach ($rs as $r) {
                        $post_datetime = $r->post_date;
                        $explode = explode(' ', $post_datetime);
                        $newDate = $explode[0];
                        $post_date = date('M d,Y', strtotime($post_datetime));
                        $meta = get_post_meta($r->ID);
                        $args = array(
                            'post_id' => $r->ID, // use post_id, not post_ID
                        );
                        $comments = get_comments($args);
                        $views = $wpdb->get_results("select SUM(views) as views from wp_views where post_id='$r->ID'");
                        $view = $views[0]->views;
                        $term_name = get_term_by('id', $meta[mocas][0], 'category')->slug;
                        ?>
                        <div class="user_comment">
                            <!--Single Post Section-->
                            <div class="normal_post_war_two">
                                <div class="top_rating_cont">
                                    <!--Categori Image-->
                                    <div class="top_post_pic_cont"><?php $userdata = get_userdata($r->post_author); ?>
                                        <div class="top_cat_pic"><img src="<?php
                                            if (z_taxonomy_image_url($meta[mocas][0]) != false)
                                                echo z_taxonomy_image_url($meta[mocas][0]);
                                            else
                                                echo get_site_url() . "/wp-content/themes/cafemocha/custom-images/no-image.jpg";
                                            ?>" height="48px" width="48px"></div>
                                    </div>
                                    <!--Categori Image-->
                                    <!-- Post Description -->
                                    <div class="top_post_des">
                                        <h2><a href="<?php echo get_permalink($r->ID); ?>"><?php echo $r->post_title; ?></a></h2>
                                        <p><?php echo $meta[story][0]; ?></p>
                                        <?php if ($meta[attached_file][0] != '') {
                                            ?>
                                            <a href="<?php //echo $meta[attached_file][0];           ?>"></a>
                                        <?php }
                                        ?>
                                    </div>
                                    <!-- Post Description -->
                                    <!-- Sip -->
                                    <div class="top_post_sip">
                                        <ul>
            <?php if (is_user_logged_in()) { ?>
                                                <li><a href="javascript:void(0)" class="vote_up" onclick="sip_it('<?php echo $r->ID; ?>')"></a>Sip it</li>
                                                <li><a href="javascript:void(0)" class="vote_down" onclick="spit_it('<?php echo $r->ID; ?>')"></a>Spit it</li>
            <?php } else { ?>
                                                <li><a href="javascript:void(0)" class="vote_up" onclick="login_msg()"></a>Sip it</li>
                                                <li><a href="javascript:void(0)" class="vote_down" onclick="login_msg()"></a>Spit it</li>
            <?php } ?>
                                        </ul>
                                    </div>
                                    <!-- Sip -->
                                </div>
                                <!--End .top_rating_cont-->
                                <div class="normal_rating_bottom_cont_two">
                                    <ul>
                                        <li><a href="<?php echo get_bloginfo('url') . '/category/' . $term_name; ?>"><?php echo get_term_by('id', $meta[mocas][0], 'category')->name; ?> </a></li>
                                        <li class="author">
                                            <div class="publishby">
                                                <p>Published By</p>
                                                <a href="<?php
                                                if ($meta[anonymous][0] == "") {
                                                    echo get_bloginfo('url') . '/profile-page-template/?user_id=' . $r->post_author;
                                                } else {
                                                    echo 'javascript:void();';
                                                }
                                                ?>"><?php
                                                       if ($meta[anonymous][0] != "") {
                                                           echo 'Anonymous';
                                                       } else {
                                                           $post_name = $userdata->post_name;
                                                           if ($post_name == 1) {
                                                               echo $userdata->user_login;
                                                           } else {
                                                               echo $userdata->Alternative_User;
                                                           }
                                                       }
                                                       ?></a>
                                            </div>
                                            <p>Post Date</p>
                                            <a><?php echo $post_date; ?></a>
                                        </li>
                                        <?php
                                        $sip_it = get_sipit($r->ID);
                                        $spit_it = get_spitit($r->ID);
                                        $sip = $sip_it - $spit_it;
                                        ?>
                                        <li style="position:relative;"><a href="#" class="sip sip_up_<?php echo $r->ID; ?>"><?php echo $sip; ?></a>
                                            <ul style="display:none;">
                                                <li class="sip_pop"><p>Sip it</p><a href="#" class="sip_it_up_<?php echo $r->ID; ?>"><?php echo $sip_it; ?></a></li>
                                                <li class="spit_pop"><p>Spit it</p><a href="#" class="spit_it_up_<?php echo $r->ID; ?>"><?php echo $spit_it; ?></a></li>
                                            </ul>
                                        </li>
                                        <li><a href="#" class="view"><?php
                                                if ($view != "") {
                                                    echo $view;
                                                } else {
                                                    echo '0';
                                                }
                                                ?> views</a></li>
                                        <li><a href="#" class="comment"><?php echo count($comments); ?> comments</a></li>
                                    </ul>
                                </div>
                                <!--End .top_rating_bottom_cont-->
                            </div>
                            <!--Single Post Section-->
                        </div><!--End .user_upload-->
                        <?php
                    }
                }
                ?>
            </div>
            <!--------------------------------------------------------------List of comments by this user-----End---------------------------------------------------------------->
            <?php
        } else {
            ?>
            <!--------------------------------------------------------------List of upload by this user-----Start---------------------------------------------------------------->
            <div class="user_upload" id="user_upload">
                <!--Single Post Section-->
                <?php
                $post_array = get_posts(array('post_type' => 'post', 'post_status' => 'publish', 'author' => $user_id));
                if (count($post_array) != 0) {
                    ?>
                    <div id="profile_heading">
                        Stories That  <?php echo $user_name; ?>  Uploaded
                    </div>
                    <?php
                }
                foreach ($post_array as $r) {
                    $post_datetime = $r->post_date;
                    $explode = explode(' ', $post_datetime);
                    $newDate = $explode[0];
                    $post_date = date('M d,Y', strtotime($post_datetime));
                    $meta = get_post_meta($r->ID);
                    $args = array(
                        'post_id' => $r->ID, // use post_id, not post_ID
                    );
                    $comments = get_comments($args);
                    $views = $wpdb->get_results("select SUM(views) as views from wp_views where post_id='$r->ID'");
                    $view = $views[0]->views;
                    $term_name = get_term_by('id', $meta[mocas][0], 'category')->slug;
                    if ($meta[anonymous][0] == "") {
                        ?>
                        <div class="normal_post_war">
                            <div class="top_rating_cont">
                                <!--Categori Image-->
                                <div class="top_post_pic_cont"><?php $userdata = get_userdata($r->post_author); ?>
                                    <div class="top_cat_pic"><img src="<?php
                                        if (z_taxonomy_image_url($meta[mocas][0]) != false)
                                            echo z_taxonomy_image_url($meta[mocas][0]);
                                        else
                                            echo get_site_url() . "/wp-content/themes/cafemocha/custom-images/no-image.jpg";
                                        ?>" height="48px" width="48px"></div>
                                </div>
                                <!--Categori Image-->
                                <!-- Post Description -->
                                <div class="top_post_des">
                                    <a href="<?php echo get_permalink($r->ID); ?>"><?php echo $r->post_title; ?></a>
                                    <?php
                                    if ($meta[attached_file][0] != '') {
                                        ?>
                                        <a href="<?php //echo $meta[attached_file][0];            ?>"></a>
                                    <?php }
                                    ?>
                                </div>
                                <!-- Post Description -->
                                <!-- Sip -->
                                <div class="top_post_sip">
                                    <ul>
            <?php if (is_user_logged_in()) { ?>
                                            <li><a href="javascript:void(0)" class="vote_up" onclick="sip_it('<?php echo $r->ID; ?>')"></a>Sip it</li>
                                            <li><a href="javascript:void(0)" class="vote_down" onclick="spit_it('<?php echo $r->ID; ?>')"></a>Spit it</li>
            <?php } else { ?>
                                            <li><a href="javascript:void(0)" class="vote_up" onclick="login_msg()"></a>Sip it</li>
                                            <li><a href="javascript:void(0)" class="vote_down" onclick="login_msg()"></a>Spit it</li>
            <?php } ?>
                                    </ul>
                                </div>
                                <!-- Sip -->
                            </div>
                            <!--End .top_rating_cont-->
                            <div class="normal_rating_bottom_cont">
                                <ul>
                                    <li><a href="<?php echo get_bloginfo('url') . '/category/' . $term_name; ?>"><?php echo get_term_by('id', $meta[mocas][0], 'category')->name; ?> </a></li>
                                    <li class="author">
                                        <div class="publishby">
                                            <p>Published By</p>
                                            <a href="<?php
                                            if ($meta[anonymous][0] == "") {
                                                echo get_bloginfo('url') . '/profile-page-template/?user_id=' . $r->post_author;
                                            } else {
                                                echo 'javascript:void();';
                                            }
                                            ?>"><?php
                                                   if ($meta[anonymous][0] != "") {
                                                       echo 'Anonymous';
                                                   } else {
                                                       $post_name = $userdata->post_name;
                                                       if ($post_name == 1) {
                                                           echo $userdata->user_login;
                                                       } else {
                                                           echo $userdata->Alternative_User;
                                                       }
                                                   }
                                                   ?></a>
                                        </div>
                                        <p>Post Date</p>
                                        <a><?php echo $post_date; ?></a>
                                    </li>
                                    <?php
                                    $sip_it = get_sipit($r->ID);
                                    $spit_it = get_spitit($r->ID);
                                    $sip = $sip_it - $spit_it;
                                    ?>
                                    <li style="position:relative;"><a href="#" class="sip sip_up_<?php echo $r->ID; ?>"><?php echo $sip; ?></a>
                                        <ul style="display:none;">

                                            <li class="sip_pop"><p>Sip it</p><a href="#" class="sip_it_up_<?php echo $r->ID; ?>"><?php echo $sip_it; ?></a></li>
                                            <li class="spit_pop"><p>Spit it</p><a href="#" class="spit_it_up_<?php echo $r->ID; ?>"><?php echo $spit_it; ?></a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#" class="view"><?php
                                            if ($view != "") {
                                                echo $view;
                                            } else {
                                                echo '0';
                                            }
                                            ?> views</a></li>
                                    <li><a href="<?php echo get_permalink($r->ID); ?>" class="comment"><?php echo count($comments); ?> comments</a></li>
                                </ul>
                            </div>
                            <!--End .top_rating_bottom_cont-->
                        </div>
                        <!-- Post Section -->
                        <?php
                    }
                }
                ?>
                <!--Single Post Section-->
                <!--Single Post Section-->

                <!--Single Post Section-->
            </div><!--End .user_upload-->
            <!--------------------------------------------------------------List of upload by this user-----End---------------------------------------------------------------->
            <!--------------------------------------------------------------List of Sip it by this user-----Start---------------------------------------------------------------->
            <div id="user_sipping" class="user_sip">
                <?php
                $query = "SELECT * FROM wp_posts p,wp_sipping s WHERE p.id=s.post_id AND p.post_type='post' AND s.sip_it=1 AND s.sipper_id='" . $user_id . "' ORDER BY (sip_it+spit_it) DESC";
                //echo $query;
                $posts_array = $wpdb->get_results($query);
                if (count($posts_array) != 0) {
                    ?>
                    <div id="profile_heading">
                        Stories That  <?php echo $user_name; ?>  Sipped
                    </div>
                    <?php
                }
                //echo $query."<br>".count($myrows);
                //$posts_array = get_posts(array('post_type' => 'publishment', 'post_status' => 'publish', 'author'=> $current_user->id));
                foreach ($posts_array as $r) {
                    $post_datetime = $r->post_date;
                    $explode = explode(' ', $post_datetime);
                    $newDate = $explode[0];
                    $post_date = date('M d,Y', strtotime($post_datetime));
                    $meta = get_post_meta($r->ID);
                    $args = array(
                        'post_id' => $r->ID, // use post_id, not post_ID
                    );
                    $comments = get_comments($args);
                    $views = $wpdb->get_results("select SUM(views) as views from wp_views where post_id='$r->ID'");
                    $view = $views[0]->views;
                    $term_name = get_term_by('id', $meta[mocas][0], 'category')->slug;
                    ?>
                    <div class="normal_post_war">
                        <!--Single Post Section-->
                        <div class="normal_post_war">
                            <div class="top_rating_cont">
                                <!--Categori Image-->
                                <div class="top_post_pic_cont"><?php $userdata = get_userdata($r->post_author); ?>
                                    <div class="top_cat_pic"><img src="<?php
                                        if (z_taxonomy_image_url($meta[mocas][0]) != false)
                                            echo z_taxonomy_image_url($meta[mocas][0]);
                                        else
                                            echo get_site_url() . "/wp-content/themes/cafemocha/custom-images/no-image.jpg";
                                        ?>" height="48px" width="48px"></div>
                                </div>
                                <!--Categori Image-->
                                <!-- Post Description -->
                                <div class="top_post_des">
                                    <h2><a href="<?php echo get_permalink($r->ID); ?>"><?php echo $r->post_title; ?></a></h2>
                                    <p><?php echo $meta[story][0]; ?></p>
                                    <?php if ($meta[attached_file][0] != '') {
                                        ?>
                                        <a href="<?php //echo $meta[attached_file][0];            ?>"></a>
        <?php }
        ?>
                                </div>
                                <!-- Post Description -->
                                <!-- Sip -->
                                <div class="top_post_sip">
                                    <ul>
                                        <?php if (is_user_logged_in()) { ?>
                                            <li><a href="javascript:void(0)" class="vote_up" onclick="sip_it('<?php echo $r->ID; ?>')"></a>Sip it</li>
                                            <li><a href="javascript:void(0)" class="vote_down" onclick="spit_it('<?php echo $r->ID; ?>')"></a>Spit it</li>
                                        <?php } else { ?>
                                            <li><a href="javascript:void(0)" class="vote_up" onclick="login_msg()"></a>Sip it</li>
                                            <li><a href="javascript:void(0)" class="vote_down" onclick="login_msg()"></a>Spit it</li>
        <?php } ?>
                                    </ul>
                                </div>
                                <!-- Sip -->
                            </div>
                            <!--End .top_rating_cont-->
                            <div class="normal_rating_bottom_cont">
                                <ul>
                                    <li><a href="<?php echo get_bloginfo('url') . '/category/' . $term_name; ?>"><?php echo get_term_by('id', $meta[mocas][0], 'category')->name; ?> </a></li>
                                    <li class="author">
                                        <div class="publishby">
                                            <p>Published By</p>
                                            <a href="<?php
                                            if ($meta[anonymous][0] == "") {
                                                echo get_bloginfo('url') . '/profile-page-template/?user_id=' . $r->post_author;
                                            } else {
                                                echo 'javascript:void();';
                                            }
                                            ?>"><?php
                                                   if ($meta[anonymous][0] != "") {
                                                       echo 'Anonymous';
                                                   } else {
                                                       $post_name = $userdata->post_name;
                                                       if ($post_name == 1) {
                                                           echo $userdata->user_login;
                                                       } else {
                                                           echo $userdata->Alternative_User;
                                                       }
                                                   }
                                                   ?></a>
                                        </div>
                                        <p>Post Date</p>
                                        <a><?php echo $post_date; ?></a>
                                    </li>
                                    <?php
                                    $sip_it = get_sipit($r->ID);
                                    $spit_it = get_spitit($r->ID);
                                    $sip = $sip_it - $spit_it;
                                    ?>
                                    <li style="position:relative;"><a href="#" class="sip sip_up_<?php echo $r->ID; ?>"><?php echo $sip; ?></a>
                                        <ul style="display:none;">
                                            <li class="sip_pop"><p>Sip it</p><a href="#" class="sip_it_up_<?php echo $r->ID; ?>"><?php echo $sip_it; ?></a></li>
                                            <li class="spit_pop"><p>Spit it</p><a href="#" class="spit_it_up_<?php echo $r->ID; ?>"><?php echo $spit_it; ?></a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#" class="view"><?php
                                            if ($view != "") {
                                                echo $view;
                                            } else {
                                                echo '0';
                                            }
                                            ?> views</a></li>
                                    <li><a href="#" class="comment"><?php echo count($comments); ?> comments</a></li>
                                </ul>
                            </div>
                            <!--End .top_rating_bottom_cont-->
                        </div>
                        <!--Single Post Section-->
                    </div><!--End .user_upload-->
                    <?php
                }
                ?>
            </div>
            <!--------------------------------------------------------------List of Sip it by this user-----End---------------------------------------------------------------->

            <!--------------------------------------------------------------List of Spit it by this user-----Start---------------------------------------------------------------->
            <div id="user_spit" class="user_spt">
                <?php
                $query = "SELECT * FROM wp_posts p,wp_sipping s WHERE p.id=s.post_id AND p.post_type='post' AND s.spit_it=1 AND s.sipper_id='" . $user_id . "' ORDER BY (sip_it+spit_it) DESC";
                $posts_array = $wpdb->get_results($query);
                if (count($posts_array) != 0) {
                    ?>
                    <div id="profile_heading">
                        Stories That  <?php echo $user_name; ?>  Spit
                    </div>
                    <?php
                }
                //echo $query."<br>".count($myrows);
                //$posts_array = get_posts(array('post_type' => 'publishment', 'post_status' => 'publish', 'author'=> $current_user->id));
                foreach ($posts_array as $r) {
                    $post_datetime = $r->post_date;
                    $explode = explode(' ', $post_datetime);
                    $newDate = $explode[0];
                    $post_date = date('M d,Y', strtotime($post_datetime));
                    $meta = get_post_meta($r->ID);
                    $args = array(
                        'post_id' => $r->ID, // use post_id, not post_ID
                    );
                    $comments = get_comments($args);
                    $views = $wpdb->get_results("select SUM(views) as views from wp_views where post_id='$r->ID'");
                    $view = $views[0]->views;
                    $term_name = get_term_by('id', $meta[mocas][0], 'category')->slug;
                    ?>
                    <div class="user_comment">
                        <!--Single Post Section-->
                        <div class="normal_post_war_two">
                            <div class="top_rating_cont">
                                <!--Categori Image-->
                                <div class="top_post_pic_cont"><?php $userdata = get_userdata($r->post_author); ?>
                                    <div class="top_cat_pic"><img src="<?php
                                        if (z_taxonomy_image_url($meta[mocas][0]) != false)
                                            echo z_taxonomy_image_url($meta[mocas][0]);
                                        else
                                            echo get_site_url() . "/wp-content/themes/cafemocha/custom-images/no-image.jpg";
                                        ?>" height="48px" width="48px"></div>
                                </div>
                                <!--Categori Image-->
                                <!-- Post Description -->
                                <div class="top_post_des">
                                    <h2><a href="<?php echo get_permalink($r->ID); ?>"><?php echo $r->post_title; ?></a></h2>
                                    <p><?php echo $meta[story][0]; ?></p>
                                    <?php if ($meta[attached_file][0] != '') {
                                        ?>
                                        <a href="<?php //echo $meta[attached_file][0];            ?>"></a>
        <?php }
        ?>
                                </div>
                                <!-- Post Description -->
                                <!-- Sip -->
                                <div class="top_post_sip">
                                    <ul>
                                        <?php if (is_user_logged_in()) { ?>
                                            <li><a href="javascript:void(0)" class="vote_up" onclick="sip_it('<?php echo $r->ID; ?>')"></a>Sip it</li>
                                            <li><a href="javascript:void(0)" class="vote_down" onclick="spit_it('<?php echo $r->ID; ?>')"></a>Spit it</li>
                                        <?php } else { ?>
                                            <li><a href="javascript:void(0)" class="vote_up" onclick="login_msg()"></a>Sip it</li>
                                            <li><a href="javascript:void(0)" class="vote_down" onclick="login_msg()"></a>Spit it</li>
        <?php } ?>
                                    </ul>
                                </div>
                                <!-- Sip -->
                            </div>
                            <!--End .top_rating_cont-->
                            <div class="normal_rating_bottom_cont_two">
                                <ul>
                                    <li><a href="<?php echo get_bloginfo('url') . '/category/' . $term_name; ?>"><?php echo get_term_by('id', $meta[mocas][0], 'category')->name; ?> </a></li>
                                    <li class="author">
                                        <div class="publishby">
                                            <p>Published By</p>
                                            <a href="<?php
                                            if ($meta[anonymous][0] == "") {
                                                echo get_bloginfo('url') . '/profile-page-template/?user_id=' . $r->post_author;
                                            } else {
                                                echo 'javascript:void();';
                                            }
                                            ?>"><?php
                                                   if ($meta[anonymous][0] != "") {
                                                       echo 'Anonymous';
                                                   } else {
                                                       $post_name = $userdata->post_name;
                                                       if ($post_name == 1) {
                                                           echo $userdata->user_login;
                                                       } else {
                                                           echo $userdata->Alternative_User;
                                                       }
                                                   }
                                                   ?></a>
                                        </div>
                                        <p>Post Date</p>
                                        <a><?php echo $post_date; ?></a>
                                    </li>
                                    <?php
                                    $sip_it = get_sipit($r->ID);
                                    $spit_it = get_spitit($r->ID);
                                    $sip = $sip_it - $spit_it;
                                    ?>
                                    <li style="position:relative;"><a href="#" class="sip sip_up_<?php echo $r->ID; ?>"><?php echo $sip; ?></a>
                                        <ul style="display:none;">
                                            <li class="sip_pop"><p>Sip it</p><a href="#" class="sip_it_up_<?php echo $r->ID; ?>"><?php echo $sip_it; ?></a></li>
                                            <li class="spit_pop"><p>Spit it</p><a href="#" class="spit_it_up_<?php echo $r->ID; ?>"><?php echo $spit_it; ?></a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#" class="view"><?php
                                            if ($view != "") {
                                                echo $view;
                                            } else {
                                                echo '0';
                                            }
                                            ?> views</a></li>
                                    <li><a href="#" class="comment"><?php echo count($comments); ?> comments</a></li>
                                </ul>
                            </div>
                            <!--End .top_rating_bottom_cont-->
                        </div>
                        <!--Single Post Section-->
                    </div><!--End .user_upload-->
                    <?php
                }
                ?>
            </div>
            <!--------------------------------------------------------------List of Spit it by this user-----End----------------------------------------------------------->

            <!--------------------------------------------------------------List of comments by this user-----Start---------------------------------------------------------------->
            <div id="user_cmt" class="user_cmt">
                <?php
                $query = "SELECT * FROM wp_posts p,wp_comments c WHERE p.id=c.comment_post_ID AND p.post_type='post' AND c.user_id=$current_user->id GROUP BY c.comment_post_ID";
                $postsarray = $wpdb->get_results($query);
                if (count($postsarray) != 0) {
                    ?>
                    <div id="profile_heading">Stories That  <?php echo $user_name; ?>  Commented on</div>
                    <?php
                }
                //echo $query."<br>".count($myrows);
                //$posts_array = get_posts(array('post_type' => 'publishment', 'post_status' => 'publish', 'author'=> $current_user->id));
                foreach ($postsarray as $r) {
                    $post_datetime = $r->post_date;
                    $explode = explode(' ', $post_datetime);
                    $newDate = $explode[0];
                    $post_date = date('M d,Y', strtotime($post_datetime));
                    $meta = get_post_meta($r->ID);
                    $args = array(
                        'post_id' => $r->ID, // use post_id, not post_ID
                    );
                    $comments = get_comments($args);
                    $views = $wpdb->get_results("select SUM(views) as views from wp_views where post_id='$r->ID'");
                    $view = $views[0]->views;
                    $term_name = get_term_by('id', $meta[mocas][0], 'category')->slug;
                    ?>
                    <div class="user_comment">
                        <!--Single Post Section-->
                        <div class="normal_post_war_two">
                            <div class="top_rating_cont">
                                <!--Categori Image-->
                                <div class="top_post_pic_cont"><?php $userdata = get_userdata($r->post_author); ?>
                                    <div class="top_cat_pic"><img src="<?php
                                        if (z_taxonomy_image_url($meta[mocas][0]) != false)
                                            echo z_taxonomy_image_url($meta[mocas][0]);
                                        else
                                            echo get_site_url() . "/wp-content/themes/cafemocha/custom-images/no-image.jpg";
                                        ?>" height="48px" width="48px"></div>
                                </div>
                                <!--Categori Image-->
                                <!-- Post Description -->
                                <div class="top_post_des">
                                    <h2><a href="<?php echo get_permalink($r->ID); ?>"><?php echo $r->post_title; ?></a></h2>
                                    <p><?php echo $meta[story][0]; ?></p>
                                    <?php if ($meta[attached_file][0] != '') {
                                        ?>
                                        <a href="<?php //echo $meta[attached_file][0];         ?>"></a>
        <?php }
        ?>
                                </div>
                                <!-- Post Description -->
                                <!-- Sip -->
                                <div class="top_post_sip">
                                    <ul>
                                        <?php if (is_user_logged_in()) { ?>
                                            <li><a href="javascript:void(0)" class="vote_up" onclick="sip_it('<?php echo $r->ID; ?>')"></a>Sip it</li>
                                            <li><a href="javascript:void(0)" class="vote_down" onclick="spit_it('<?php echo $r->ID; ?>')"></a>spit it</li>
                                        <?php } else { ?>
                                            <li><a href="javascript:void(0)" class="vote_up" onclick="login_msg()"></a>Sip it</li>
                                            <li><a href="javascript:void(0)" class="vote_down" onclick="login_msg()"></a>spit it</li>
        <?php } ?>
                                    </ul>
                                </div>
                                <!-- Sip -->
                            </div>
                            <!--End .top_rating_cont-->
                            <div class="normal_rating_bottom_cont_two">
                                <ul>
                                    <li><a href="<?php echo get_bloginfo('url') . '/category/' . $term_name; ?>"><?php echo get_term_by('id', $meta[mocas][0], 'category')->name; ?> </a></li>
                                    <li class="author">
                                        <div class="publishby">
                                            <p>Published By</p>
                                            <a href="<?php
                                            if ($meta[anonymous][0] == "") {
                                                echo get_bloginfo('url') . '/profile-page-template/?user_id=' . $r->post_author;
                                            } else {
                                                echo 'javascript:void();';
                                            }
                                            ?>"><?php
                                                   if ($meta[anonymous][0] != "") {
                                                       echo 'Anonymous';
                                                   } else {
                                                       $post_name = $userdata->post_name;
                                                       if ($post_name == 1) {
                                                           echo $userdata->user_login;
                                                       } else {
                                                           echo $userdata->Alternative_User;
                                                       }
                                                   }
                                                   ?></a>
                                        </div>
                                        <p>Post Date</p>
                                        <a><?php echo $post_date; ?></a>
                                    </li>
                                    <?php
                                    $sip_it = get_sipit($r->ID);
                                    $spit_it = get_spitit($r->ID);
                                    $sip = $sip_it - $spit_it;
                                    ?>
                                    <li style="position:relative;"><a href="#" class="sip sip_up_<?php echo $r->ID; ?>"><?php echo $sip; ?></a>
                                        <ul style="display:none;">
                                            <li class="sip_pop"><p>Sip it</p><a href="#" class="sip_it_up_<?php echo $r->ID; ?>"><?php echo $sip_it; ?></a></li>
                                            <li class="spit_pop"><p>Spit it</p><a href="#" class="spit_it_up_<?php echo $r->ID; ?>"><?php echo $spit_it; ?></a></li>
                                        </ul>
                                    </li>
                                    <li><a href="#" class="view"><?php
                                            if ($view != "") {
                                                echo $view;
                                            } else {
                                                echo '0';
                                            }
                                            ?> views</a></li>
                                    <li><a href="#" class="comment"><?php echo count($comments); ?> comments</a></li>
                                </ul>
                            </div>
                            <!--End .top_rating_bottom_cont-->
                        </div>
                        <!--Single Post Section-->
                    </div><!--End .user_upload-->
                    <?php
                }
                ?>
            </div>
            <!--------------------------------------------------------------List of comments by this user-----End---------------------------------------------------------------->
<?php } ?>
        <!--Form Section-->
    </div>
    <!-- #content -->
</div>
<!-- #primary -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>