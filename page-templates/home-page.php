<?php

/**

 * Template Name: Home Page Template

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

?>

<script class="jsbin" src="<?php echo get_bloginfo('siteurl') . '/wp-content/themes/cafemocha/js/cafemocha/sip.js'; ?>">



</script>

<script type="text/javascript">

    var flag = 0;

    jQuery(document).ready(function()

    {

        function last_msg_funtion()

        {

            var ID = jQuery(".chima:last").attr("id");

            //alert("id - "+ID);

            jQuery('div#last_msg_loader').html('<img src="<?php echo get_bloginfo('url') . '/wp-content/themes/cafemocha/custom-images/loaderB32.gif' ?>">');

            var data = {

                action: 'loader',

                id: ID

            };

            jQuery.post(ajaxurl, data, function(res) {

                if (res !== "") {

                    jQuery(".chima:last").after(res);

                }

                jQuery('div#last_msg_loader').empty();

                flag = 0;

            });

        }

        ;



        jQuery(window).scroll(function() {

            if (jQuery(window).scrollTop() + 2 >= jQuery(document).height() - jQuery(window).height() && flag == 0) {

                flag = 1;

                last_msg_funtion();

            }

        });

    });

</script>

<style type="text/css">
.home #menu-item-34 a span{background: url("http://cafemocha.org/wp-content/themes/cafemocha/custom-images/menu_hover_small.png") no-repeat scroll right 0 rgba(0, 0, 0, 0);
    display: block;
    line-height: 30px;
    margin: 0;
    padding: 0 16px 0 0;}
	
.home #menu-item-34 a {
    background: url("http://cafemocha.org/wp-content/themes/cafemocha/custom-images/menu_hover_big.png") no-repeat scroll 0 0 rgba(0, 0, 0, 0);
    color: #000000;
    display: block;
    padding: 0 0 0 16px;
}
</style>

<div id="primary" class="site-content">

    <div id="content" role="main">

        <!-- Post Section -->

        <!--Short By-->

        <div class="short_by">

            <ul>

                <li>Arrange Most Recent By-</li>

                <li><a href="<?php echo site_url() . '/?sectionby=d' ?>">Daily</a></li>

                <li><a href="<?php echo site_url() . '/?sectionby=w' ?>">Weekly</a></li>

                <li><a href="<?php echo site_url() . '/?sectionby=m' ?>">Monthly</a></li>

            </ul>

        </div>

        <!--Short By-->

        <!--Slid Arrow-->

        <!--<div class="slid_div">

            <ul>

                <li><a href="#" class="slid_left"></a></li>

                <li><a href="#" class="slid_right"></a></li>

            </ul>

        </div>-->

        <!--Slid Arrow-->

        <?php //echo get_site_url()."/wp-content/themes/cafemocha/js/bxslider/jquery.bxslider.min.js";   ?> 

                <!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>-->



        <script src="<?php echo get_site_url(); ?>/wp-content/themes/cafemocha/js/bxslider/jquery.bxslider.min.js"></script>

        <link href="<?php echo get_site_url(); ?>/wp-content/themes/cafemocha/js/bxslider/jquery.bxslider.css" rel="stylesheet" />



        <?php

        if (is_user_logged_in()) {

            global $current_user;

            get_currentuserinfo();

            @$moca = unserialize($current_user->Choosed_Mocha);

            if ($moca == false) {//(count($moca)>0)

                $moca = array('0' => '0');

                $posts_array = get_posts(array('post_type' => 'post', 'post_status' => 'publish', 'numberposts' => 5));

            } else {

                $posts_array = get_posts(array('post_type' => 'post', 'post_status' => 'publish', 'numberposts' => 5, 'meta_query' => array(array('key' => 'mocas', 'value' => $moca, 'compare' => 'IN'))));

            }

        } else {

            $posts_array = get_posts(array('post_type' => 'post', 'post_status' => 'publish', 'numberposts' => 5));

        }

        ?>

        <div class='slider_label'>Trending Stories</div>

        <ul class="bxslider">

            <?php

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

                $post_author = $r->post_author;

                if (is_user_logged_in()) {



                    $user_block = unserialize($current_user->blocked_users);



                    if (count($user_block) == 0) {

                        $user_block = array();

                        $check_user = !in_array($post_author, $user_block);

                    } else {

                        $check_user = !in_array($post_author, $user_block);

                    }

                } else {



                    $user_block = array();



                    $check_user = !in_array($post_author, $user_block);

                }



                if ($check_user) {

                    ?>



                    <li>

                        <div class="top_rating_war">

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

                                        <div><?php //echo $meta[attached_file][0];                                         ?></div>

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

                            <div class="top_rating_bottom_cont">

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

                    </li>

                    <!-- Post Section -->

                    <?php

                }

            }

            ?>

        </ul>

        <script>

    jQuery(document).ready(function() {

        jQuery('.bxslider').bxSlider({

            mode: 'fade',

            captions: true,

            auto: true

        });

    });



        </script>

        <!--------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------NextPart----------------------------------------------------------------------------------------->



        <?php

        if (isset($_GET['sectionby'])) {

            global $wpdb;

            $query = "SELECT * FROM wp_posts where post_type='post' ORDER BY post_date";

            $rs = $wpdb->get_results($query);

            $last_date = mysql2date('d-m-Y', $rs[0]->post_date);

            $date = date('d-m-Y');

            $end_date = $date;

            ?>

        <div class='slider_label'>Stories</div>

            <?php

            while (strtotime($last_date) <= strtotime($end_date)) {

                $query = "SELECT * FROM wp_posts WHERE post_type='post' and post_status='publish'";

                //////////////////////////////////sectionby//////////////////////////////////////////

                if (isset($_GET['sectionby'])) {

                    if ($_GET['sectionby'] == 'd') {

                        $query = $query . " and post_date like '%" . date("Y-m-d", strtotime($date)) . "%'";

                        $end_date = $date;

                    } elseif ($_GET['sectionby'] == 'w') {

                        $start_date;

                        $end_date;

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

                //echo $query."<br>";

                //$last_date=$date;



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

                //echo $query;

                $rs = $wpdb->get_results($query);

                /* echo '<pre>';

                  print_r($rs);

                  echo '</pre>'; */

                $i = 0;



                foreach ($rs as $r) {

                    $cat_array = wp_get_post_categories($r->ID);

                    $cat_id = $cat_array[0];

                    if (is_user_logged_in()) {

                        $moca = unserialize($current_user->Choosed_Mocha);

                        if (count($moca) == 0) {

                            $moca = array();

                            $check = !in_array($cat_id, $moca);

                        } else {

                            $check = in_array($cat_id, $moca);

                        }

                    } else {

                        $moca = array();

                        $check = !in_array($cat_id, $moca);

                    }

                    if ($check) {

                        $i++;

                    }

                }

                if (count($rs) != 0 && $i != 0) {

                    ?>

                    <div class="posted_on">

                        <?php

//                        if (isset($_GET['sectionby'])) {

//                            if ($_GET['sectionby'] == 'd')

//                                echo "Posted on - " . $date;

//                            elseif ($_GET['sectionby'] == 'w')

//                                echo "Posted on - " . $start_date . " to " . $end_date;

//                            elseif ($_GET['sectionby'] == 'm')

//                                echo "Posted on - " . date('M-Y', strtotime($date)); //." to ".$end_date;

//                            else

//                                echo "Posted on - " . $date;

//                        }

//                        else

//                            echo "Posted on - " . $date;

                        ?>

                    </div>

                    <?php

                }

                $date = $previousdate;

                foreach ($rs as $r) {

                    $post_datetime = $r->post_date;

                    $explode = explode(' ', $post_datetime);

                    $newDate = $explode[0];

                    $post_date = date('M d,Y', strtotime($post_datetime));

                    $cat_array = wp_get_post_categories($r->ID);

                    $cat_id = $cat_array[0];

                    $post_author = $r->post_author;

                    if (is_user_logged_in()) {

                        $moca = unserialize($current_user->Choosed_Mocha);

                        $user_block = unserialize($current_user->blocked_users);

                        if (count($moca) == 0) {

                            $moca = array();

                            $check = !in_array($cat_id, $moca);

                        } else {

                            $check = in_array($cat_id, $moca);

                        }

                        if (count($user_block) == 0) {

                            $user_block = array();

                            $check_user = !in_array($post_author, $user_block);

                        } else {

                            $check_user = !in_array($post_author, $user_block);

                        }

                    } else {

                        $moca = array();

                        $user_block = array();

                        $check = !in_array($cat_id, $moca);

                        $check_user = !in_array($post_author, $user_block);

                    }



                    if ($check & $check_user) {

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

                                        <?php //echo $meta[attached_file][0];    ?>

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



                                            <li><p>Sip it</p><a href="#" class="sip_pop sip_it_up_<?php echo $r->ID; ?>"><?php echo intval($sip_it); ?></a></li>

                                            <li><p>Spit it</p><a href="#" class="spit_pop spit_it_up_<?php echo $r->ID; ?>"><?php echo intval($spit_it); ?></a></li>

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

                        <?php

                        //echo $r->ID."<br>";

                    }

                }

            }

        } else {

            ?>

            <div class='slider_label'>Stories</div>

            <?php

            if (is_user_logged_in()) {

                global $current_user;

                get_currentuserinfo();
				
				 /*  $query = $query . " and post_date like '%" . date("Y-m-d", strtotime($date)) . "%'";   */

                $posts_array = $wpdb->get_results("SELECT * FROM `wp_posts` p LEFT JOIN wp_sipping v ON p.`ID` = v.post_id WHERE p.post_type='post' and p.post_status='publish' GROUP BY p.`ID` DESC limit 10");

            } else {

                $posts_array = $wpdb->get_results("SELECT * FROM `wp_posts` p LEFT JOIN wp_sipping v ON p.`ID` = v.post_id WHERE p.post_type='post' and p.post_status='publish' GROUP BY p.`ID`  DESC limit 10");

            }

            foreach ($posts_array as $r) {

                $post_datetime = $r->post_date;

                $explode = explode(' ', $post_datetime);

                $newDate = $explode[0];

                $post_date = date('M d,Y', strtotime($post_datetime));

                $cat_array = wp_get_post_categories($r->ID);

                $cat_id = $cat_array[0];

                $post_author = $r->post_author;

                if (is_user_logged_in()) {

                    $moca = unserialize($current_user->Choosed_Mocha);

                    $user_block = unserialize($current_user->blocked_users);

                    if (count($moca) == 0) {

                        $moca = array();

                        $check = !in_array($cat_id, $moca);

                    } else {

                        $check = in_array($cat_id, $moca);

                    }

                    if (count($user_block) == 0) {

                        $user_block = array();

                        $check_user = !in_array($post_author, $user_block);

                    } else {

                        $check_user = !in_array($post_author, $user_block);

                    }

                } else {

                    $moca = array();

                    $user_block = array();

                    $check = !in_array($cat_id, $moca);

                    $check_user = !in_array($post_author, $user_block);

                }



                if ($check & $check_user) {

                    $meta = get_post_meta($r->ID);

                    $args = array(

                        'post_id' => $r->ID, // use post_id, not post_ID

                    );

                    $comments = get_comments($args);

                    $views = $wpdb->get_results("select SUM(views) as views from wp_views where post_id='$r->ID'");

                    $view = $views[0]->views;

                    $term_name = get_term_by('id', $meta[mocas][0], 'category')->slug;

                    ?>



                    <div class="normal_post_war chima" id="10">

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

                                    <a href="<?php //echo $meta[attached_file][0];                                ?>"></a>

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



                                        <li class="sip_pop "><p>Sip it</p><a href="#" class="sip_it_up_<?php echo $r->ID; ?>"><?php echo intval($sip_it); ?></a></li>

                                        <li class="spit_pop"><p>Spit it</p><a href="#" class="spit_it_up_<?php echo $r->ID; ?>"><?php echo intval($spit_it); ?></a></li>

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

<?php } ?>

        <div id="last_msg_loader"></div>

    </div>

    <!-- #content -->

</div>

<!-- #primary -->

<?php get_sidebar(); ?>

<?php get_footer(); ?>

