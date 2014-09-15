<?php
/**
 * Template Name: Document Details Templets
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
global $wpdb, $current_user;
get_currentuserinfo();
$user_id = $current_user->id;
$user_name = $current_user->user_login;
$post_id = $_GET['id'];
$posts_array = get_posts(array('ID' => $post_id));
$meta = get_post_meta($post_id);
$args = array(
	'number' => '5',
	'comment_author' => $user_name, // use post_id, not post_ID
);
$comments = get_comments($args);
echo '<pre>';
print_r($comments);
echo '</pre>';
require_once(get_template_directory() . '/custom_functions/sipping.php');
?>
<script class="jsbin" src="<?php echo get_bloginfo('siteurl') . '/wp-content/themes/cafemocha/js/cafemocha/sip.js'; ?>"></script>
<div id="primary" class="site-content">
    <div id="content" role="main">
        <?php while (have_posts()) : the_post(); ?>
            <div class="normal_post_war">
                <div class="top_rating_cont">
                    <!--Categori Image-->
                    <div class="top_post_pic_cont"><?php $userdata = get_userdata($posts_array[0]->post_author); ?>
                        <div class="top_cat_pic"><img src="<?php
                            if (z_taxonomy_image_url($meta[mocas][0]) != false)
                                echo z_taxonomy_image_url($meta[mocas][0]);
                            else
                                echo get_site_url() . "/wp-content/themes/cafemocha/custom-images/no-image.jpg";
                            ?>" height="48px" width="48px"></div>
                    </div>
                    <!--Categori Image-->
                    <!-- Post Description -->
                    <div class="top_post_des"><?php echo $meta[description][0]; ?></div>
                    <!-- Post Description -->
                    <!-- Sip -->
                    <div class="top_post_sip">
                        <ul>
                            <?php if (is_user_logged_in()) { ?>
                                <li><a href="javascript:void(0)" class="vote_up" onclick="sip_it('<?php echo $r->ID; ?>')"></a>Sip it</li>
                                <li><a href="javascript:void(0)" class="vote_down" onclick="spit_it('<?php echo $r->ID; ?>')"></a>Spit it</li>
                            <?php } else { ?>
                                <li><a href="javascript:void(0)" class="vote_up" onclick="login_msg();"></a>Sip it</li>
                                <li><a href="javascript:void(0)" class="vote_down" onclick="login_msg();"></a>Spit it</li>
                            <?php } ?>
                        </ul>
                    </div>
                    <!-- Sip -->
                    <div><a href="#"><?php echo get_term_by('id', $meta[mocas][0], 'category')->name; ?> </a></div>
                    <div><p>Published By</p>
                        <a href="#"><?php echo $userdata->display_name; ?></a></div>
                </div>
                <!--End .top_rating_cont-->
                <!-- description -->
                <div class="top_post_des"><?php echo $meta[story][0]; ?></div>
                <!-- description -->
                <div>
                    <?php if ($meta[attached_file][0] != '') {
                        ?>
                        <a href="<?php echo $meta[attached_file][0]; ?>">DOC</a>
                    <?php }
                    ?>
                </div>
                <div class="normal_rating_bottom_cont">
                    <ul>
                        <li></li>
                        <li class="author">
                        </li>
                        <?php
                        $sip_it = get_sipit($r->ID);
                        $spit_it = get_spitit($r->ID);
                        $sip = $sip_it - $spit_it;
                        ?>
                        <li style="position:relative;"><a href="#" class="sip sip_up_<?php echo $r->ID; ?>"><?php echo $sip; ?></a>
                            <ul style="display:none;">

                                <li><a href="#" class="sip_pop sip_it_up_<?php echo $r->ID; ?>"><?php echo intval($sip_it); ?></a></li>
                                <li><a href="#" class="spit_pop spit_it_up_<?php echo $r->ID; ?>"><?php echo intval($spit_it); ?></a></li>
                            </ul>
                        </li>
                        <li><a href="#" class="view">525 views</a></li>
                        <li><a href="#" class="comment">125 comments</a></li>
                    </ul>
                </div>
                <!--End .top_rating_bottom_cont-->
            </div> 
            <?php
            if($user_id){
            comments_template('', true); 
            }
            ?>
        <?php endwhile; // end of the loop.  ?>
    </div><!-- #content -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>