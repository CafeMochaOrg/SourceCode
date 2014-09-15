<?php
/**
 * The template for displaying Search Results pages.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
get_header();
require_once(get_template_directory() . '/custom_functions/sipping.php');
?>
<script class="jsbin" src="<?php echo get_bloginfo('siteurl') . '/wp-content/themes/cafemocha/js/cafemocha/sip.js'; ?>"></script>
<section id="primary" class="site-content">
    <div id="content" role="main">
        <?php
//echo $_GET['s'];
        ?>
        <?php if (have_posts()) : ?>

            <header class="page-header">
                <h1 class="page-title"><?php printf(__('Search Results for: %s', 'cafemocha'), '<span>' . get_search_query() . '</span>'); ?></h1>
            </header>

            <?php //cafemocha_content_nav( 'nav-above' ); ?>

            <?php /* Start the Loop */ ?>
            <?php while (have_posts()) : the_post(); ?>
                <?php
                global $wpdb;
                $post_id = get_the_id();
                $post = get_post($post_id);
                $post_datetime = $post->post_date;
                $post_date = date('M d,Y', strtotime($post_datetime));
                $cat_array = wp_get_post_categories($post_id);
                $cat_id = $cat_array[0];
                $post_author = $post->post_author;
                $meta = get_post_meta($post_id);
                $userdata = get_userdata($post_author);
                $args = array(
                    'post_id' => $post_id, // use post_id, not post_ID
                );
                $comments = get_comments($args);
                $views = $wpdb->get_results("select SUM(views) as views from wp_views where post_id='$post_id'");
                $view = $views[0]->views;
                $term_name = get_term_by('id', $meta[mocas][0], 'category')->slug;
                if($meta[anonymous][0]==""){
                ?>
                <div class="normal_post_war chima" id="5">
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
                            <a href="<?php echo get_permalink($post->ID); ?>"><?php echo $post->post_title; ?></a>
                            <?php
                            if ($meta[attached_file][0] != '') {
                                ?>
                                <a href="<?php //echo $meta[attached_file][0];   ?>"></a>
                            <?php }
                            ?>
                        </div>
                        <!-- Post Description -->
                        <!-- Sip -->
                        <div class="top_post_sip">
                            <ul>
                                <?php if (is_user_logged_in()) { ?>
                                    <li><a href="javascript:void(0)" class="vote_up" onclick="sip_it('<?php echo $post_id; ?>')"></a>Sip it</li>
                                    <li><a href="javascript:void(0)" class="vote_down" onclick="spit_it('<?php echo $post_id; ?>')"></a>Spit it</li>
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
                                        echo get_bloginfo('url') . '/profile-page-template/?user_id=' . $post->post_author;
                                    } else {
                                        echo 'javascript:void();';
                                    }
                                    ?>"><?php
                                           if ($meta[anonymous][0] != "") {
                                               echo 'anonymous';
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
                            $sip_it = get_sipit($post_id);
                            $spit_it = get_spitit($post_id);
                            $sip = $sip_it - $spit_it;
                            ?>
                            <li style="position:relative;"><a href="#" class="sip sip_up_<?php echo $post_id; ?>"><?php echo $sip; ?></a>
                                <ul style="display:none;">

                                    <li><a href="#" class="sip_pop sip_it_up_<?php echo $post_id ?>"><?php echo intval($sip_it); ?></a></li>
                                    <li><a href="#" class="spit_pop spit_it_up_<?php echo $post_id ?>"><?php echo intval($spit_it); ?></a></li>
                                </ul>
                            </li>
                            <li><a href="#" class="view"><?php
                                    if ($view != "") {
                                        echo $view;
                                    } else {
                                        echo '0';
                                    }
                                    ?> views</a></li>
                            <li><a href="<?php echo get_permalink($post_id); ?>" class="comment"><?php echo count($comments); ?> comments</a></li>
                        </ul>
                    </div>
                    <!--End .top_rating_bottom_cont-->
                </div>
                <?php } //get_template_part('content', get_post_format());  ?>
            <?php endwhile; ?>

            <?php //cafemocha_content_nav( 'nav-below' ); ?>

        <?php else : ?>

            <article id="post-0" class="post no-results not-found">
                <header class="entry-header">
                    <h1 class="entry-title"><?php _e('Nothing Found', 'cafemocha'); ?></h1>
                </header>

                <div class="entry-content">
                    <p><?php _e('Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'cafemocha'); ?></p>
                    <?php get_search_form(); ?>
                </div><!-- .entry-content -->
            </article><!-- #post-0 -->

        <?php endif; ?>

    </div><!-- #content -->
</section><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>