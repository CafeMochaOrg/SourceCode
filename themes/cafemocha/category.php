<?php
/**
 * The template for displaying Category pages.
 *
 * Used to display archive-type pages for posts in a category.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
get_header();
$cur_cat_id = get_cat_id(single_cat_title("", false));
$cat = get_term_by('id', $cur_cat_id, 'category');
$cat_name = $cat->name;
global $wpdb, $current_user;
get_currentuserinfo();
$user_id = $current_user->id;
$user_name = $current_user->user_login;
if ($_GET['most_recent']) {
    $args = array(
        'posts_per_page' => -1,
        'category' => $cur_cat_id,
        'orderby' => 'post_date',
        'order' => 'DESC'
    );
    $posts_array = get_posts($args);
} else if ($_GET['most_viewed']) {
    $posts_array = $wpdb->get_results("SELECT * FROM `wp_posts` p LEFT JOIN wp_views v ON p.`ID` = v.post_id WHERE p.post_type='post' and p.post_status='publish' group by v.post_id Order by SUM(v.views) DESC");
} else if ($_GET['top_rated']) {
    $posts_array = $wpdb->get_results("SELECT * FROM `wp_posts` p LEFT JOIN wp_sipping v ON p.`ID` = v.post_id WHERE p.post_type='post' and p.post_status='publish' GROUP BY p.ID Order by SUM(v.sip_it) DESC");
} else if ($_GET['most_discussed']) {
    $posts_array = $wpdb->get_results("select * from wp_posts where `post_type` = 'post' and `post_status` = 'publish' group by ID Order by (select count(comment_post_ID) FROM wp_comments WHERE comment_post_ID = ID ) DESC");
} else {
    $args = array('posts_per_page' => -1, 'category' => $cur_cat_id);
    $posts_array = get_posts($args);
}
require_once(get_template_directory() . '/custom_functions/sipping.php');
?>
<script class="jsbin" src="<?php echo get_bloginfo('siteurl') . '/wp-content/themes/cafemocha/js/cafemocha/sip.js'; ?>"></script>
<div id="primary" class="site-content">
    <div id="content" role="main">
        <div class="short_by">

        </div>
        <h1><?php echo $cat_name; ?></h1>
        <?php
        if (count($posts_array) > 0) {
            foreach ($posts_array as $r) {
                $post_datetime = $r->post_date;
                $explode = explode(' ', $post_datetime);
                $newDate = $explode[0];
                $post_date = date('M d,Y', strtotime($post_datetime));
                $meta = get_post_meta($r->ID);
                $category_id = $meta[mocas][0];
                $userdata = get_userdata($r->post_author);
                $args = array(
                    'post_id' => $r->ID, // use post_id, not post_ID
                );
                $comments = get_comments($args);
                $views = $wpdb->get_results("select SUM(views) as views from wp_views where post_id='$r->ID'");
                $view = $views[0]->views;
                $term_name = get_term_by('id', $meta[mocas][0], 'category')->slug;
                if ($category_id == $cur_cat_id) {
                    ?>
                    <div class="normal_post_war">
                        <div class="top_rating_cont">
                            <!--Categori Image-->
                            <div class="top_post_pic_cont">
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
        } else {
            ?>
            <div class="success_message">There are no Stories in this Mocha. Sorry!</div>
        <?php }
        ?>
    </div><!-- #content -->
</div><!-- #primary -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>