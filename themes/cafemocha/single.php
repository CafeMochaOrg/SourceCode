<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
get_header();
$post_id = get_the_ID();
global $wpdb, $current_user;
get_currentuserinfo();
$user_id = $current_user->id;
if ($user_id) {
// view section//
    $date = date('Y-m-d');
    $views = $wpdb->get_results("select * from wp_views where user_id='$user_id' and date='$date' and post_id='$post_id'");
    if (count($views) == 0) {
        $view = $wpdb->get_results("select * from wp_views where user_id='$user_id' and post_id='$post_id'");
        $total_view = $view[0]->views;
        $totalview = $total_view + 1;
        if (count($view) > 0) {
            $wpdb->get_results("update wp_views set views='$totalview',date='$date' where user_id='$user_id' and post_id='$post_id'");
        } else {
            $in_data1 = array('user_id' => $user_id, 'post_id' => $post_id, 'views' => '1', 'date' => $date);
            $wpdb->insert('wp_views', $in_data1);
        }
    }
//view section//
}
$user_name = $current_user->user_login;
$posts_array = get_post($post_id);
$post_datetime = $posts_array->post_date;
$explode = explode(' ', $post_datetime);
$newDate = $explode[0];
$post_date = date('M d,Y', strtotime($post_datetime)) . ' at ' . date('g:i A', strtotime($post_datetime));
$userdata = get_userdata($posts_array->post_author);
$user_city = get_user_meta($posts_array->post_author, 'City', TRUE);
$user_state = get_user_meta($posts_array->post_author, 'State', TRUE);
$user_expetis = get_user_meta($posts_array->post_author, 'Other_Expertise', TRUE);
$user_Experience_In = get_user_meta($posts_array->post_author, 'Experience_In', TRUE);
$user_Institiution = get_user_meta($posts_array->post_author, 'Institiution', TRUE);
$meta = get_post_meta($post_id);
$args = array(
    'post_id' => $post_id, // use post_id, not post_ID
);
$comments = get_comments($args);
$vv = $wpdb->get_results("select SUM(views) as view from wp_views where post_id='$post_id'");
$v = $vv[0]->view;
$term_name = get_term_by('id', $meta[mocas][0], 'category')->slug;
require_once(get_template_directory() . '/custom_functions/sipping.php');
?>
<script class="jsbin" src="<?php echo get_bloginfo('siteurl') . '/wp-content/themes/cafemocha/js/cafemocha/sip.js'; ?>"></script>
<div id="primary" class="site-content">
    <div id="content" role="main">
        <?php while (have_posts()): ?>
            <nav class="nav-single">
                <?php previous_post_link('%link', '<span class="meta-nav">' . _x('', 'twentytwelve') . '</span><span class="nav-previous">Previous Story</span>'); ?>
                <?php next_post_link('%link', '<span class="nav-next">Next Story</span><span class="meta-nav">' . _x('', 'twentytwelve') . '</span>'); ?>
            </nav><!-- .nav-single -->

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
                        <h2><?php echo $posts_array->post_title; ?></h2>
                        <div class="post_by">
                            <h3><?php echo get_term_by('id', $meta[mocas][0], 'category')->name; ?></h3>
                            <div class="publishby_det">
                                <p><span>Published By</span><a href="<?php echo get_bloginfo('url') . '/profile-page-template/?user_id=' . $posts_array->post_author; ?>"><?php
                                        $post_name = $userdata->post_name;
                                        if ($post_name == 1) {
                                            echo $userdata->user_login;
                                        } else {
                                            echo $userdata->Alternative_User;
                                        }
                                        ?></a></p>
                            </div>

                            <?php
                            if ($user_city != "") {
                                ?>
                                <div class="publishby_det"><p><span>City</span><b><?php echo $user_city; ?></b></p></div>
                            <?php } ?>
                            <?php
                            if ($user_state != "") {
                                ?>
                                <div class="publishby_det"><p><span>State</span><b><?php echo $user_state ?></b></p></div>
                            <?php } ?>
                            <?php
                            if ($user_expetis != "") {
                                ?>
                                <div class="publishby_det"><p><span>Other Expertise</span><b><?php echo $user_expetis; ?></b></p></div>
                            <?php } ?>
                            <?php
                            if ($user_Experience_In != "") {
                                ?>
                                <div class="publishby_det"><p><span>Experience In</span><b><?php echo $user_Experience_In; ?></b></p></div>
                            <?php } ?>
                            <?php
                            if ($user_Institiution != "") {
                                ?>
                                <div class="publishby_det"><p><span>Institution</span><b><?php echo $user_Institiution; ?></b></p></div>
                            <?php } ?>
                            <div class="publishby_det">
                                <p><span>Publish Date</span><b><?php echo $post_date; ?></b></p>
                            </div>

                        </div><!--End Post_by-->
                        <h3>Abstract:</h3>
                        <p><?php echo $meta[description][0]; ?></p>

                        <?php if ($posts_array->post_content) { ?>
                            <h3>Full Story:</h3>
                        <?php } ?>
                        <div class="top_post_des">
                            <?php
                            the_post();
                            the_content();
                            ?>
                        </div>
                        <?php if ($meta[attached_file][0] != '') {
                            ?>
                            <div class="doc_cont">
                                <?php
                                $filename = $meta[attached_file][0];
                                $explode = explode('/', $meta[attached_file][0]);
                                $cv_format = end($explode);
                                $explode_dot = explode('.', $cv_format);
                                $extention = end($explode_dot);
                                $headers = get_headers($filename, 1);
                                $fsize = $headers['Content-Length'];
                                $mbytes = formatSizeUnits($fsize);
                                ?>
                                <?php if ($extention == 'docx' or $extention == 'doc') { ?>
                                    <img src="<?php echo get_bloginfo('url') . '/wp-content/themes/cafemocha/custom-images/word_icon.png' ?>" />
                                <?php } else if ($extention == 'pdf') { ?>
                                    <img src="<?php echo get_bloginfo('url') . '/wp-content/themes/cafemocha/custom-images/file_pdf.png' ?>" />
                                <?php } else if ($extention == 'pptx') { ?>
                                    <img src="<?php echo get_bloginfo('url') . '/wp-content/themes/cafemocha/custom-images/file_ppt.png' ?>" />
                                <?php } else if ($extention == 'xlsx') { ?>
                                    <img src="<?php echo get_bloginfo('url') . '/wp-content/themes/cafemocha/custom-images/xlsx.png' ?>" />
                                <?php } ?>
                                <a href="javascript:void();" class="doc_title"><?php echo $cv_format; ?></a>
                                <p><?php echo $mbytes; ?><a href="<?php echo $meta[attached_file][0]; ?>">Download</a></p>
                            </div>
                        <?php }
                        ?>
                    </div>
                    <!-- Post Description -->
                    <!-- Sip -->
                    <div class="top_post_sip">
                        <ul>
                            <?php if (is_user_logged_in()) { ?>
                                <li><a href="javascript:void(0)" class="vote_up" onclick="sip_it('<?php echo $posts_array->ID; ?>');"></a>Sip it</li>
                                <li><a href="javascript:void(0)" class="vote_down" onclick="spit_it('<?php echo $posts_array->ID; ?>');"></a>Spit it</li>
                            <?php } else { ?>
                                <li><a href="javascript:void(0)" class="vote_up" onclick="login_msg();"></a>Sip it</li>
                                <li><a href="javascript:void(0)" class="vote_down" onclick="login_msg();"></a>Spit it</li>
                            <?php } ?>
                        </ul>
                    </div>
                    <!-- Sip -->

                </div>
                <!--End .top_rating_cont-->

                <!-- description -->
                <div class="normal_rating_bottom_cont">
                    <ul>
                        <li style="width:375px;">&nbsp;</li>
                        <?php
                        $sip_it = get_sipit($posts_array->ID);
                        $spit_it = get_spitit($posts_array->ID);
                        $sip = $sip_it - $spit_it;
                        ?>
                        <li style="position:relative;"><a href="#" class="sip sip_up_<?php echo $posts_array->ID; ?>"><?php echo $sip; ?></a>
                            <ul style="display:none;">

                                <li class="sip_pop"><p>Sip it</p><a href="#" class="sip_it_up_<?php echo $posts_array->ID; ?>"><?php echo $sip_it; ?></a></li>
                                <li class="spit_pop"><p>Spit it</p><a href="#" class="spit_it_up_<?php echo $posts_array->ID; ?>"><?php echo $spit_it; ?></a></li>
                            </ul>
                        </li>
                        <li><a href="#" class="view"><?php
                                if ($v != "") {
                                    echo $v;
                                } else {
                                    echo '0';
                                }
                                ?> views</a></li>
                        <li><a href="#" class="comment"><?php echo count($comments); ?> comments</a></li>
                    </ul>
                </div>
                <!--End .top_rating_bottom_cont-->
            </div><!--End .normal_post_war-->
        <?php endwhile; // end of the loop. ?>
        <?php
        if ($user_id) {
            comments_template('', true);
        } else {
            ?>
            <div class="comment_main">
                <?php
                $args = array(
                    'post_id' => $post_id, // use post_id, not post_ID
                );
                $comments = get_comments($args);
                foreach ($comments as $comments_val) {
                    $user_id = $comments_val->user_id;
                    $post_name = get_user_meta($user_id, 'post_name', TRUE);
                    if ($post_name == 1) {
                        $user = get_userdata($user_id);
                        $user_name = $user->user_login;
                    } else {
                        $user_name = get_user_meta($user_id, 'Alternative_User', TRUE);
                    }
                    $comment_content = $comments_val->comment_content;
                    $comment_datetime = $comments_val->comment_date;
                    $explode = explode(' ', $comment_datetime);
                    $newDate = $explode[0];
                    $comment_date = date('M d,Y', strtotime($comment_datetime)) . ' at ' . date('g:i a', strtotime($comment_datetime));
                    $comment_time = $explode[1];
                    $user_img = get_user_meta($user_id, 'photo', TRUE);
                    ?>
                    <div class="comment_single">
        <!--                        <div class="comment_img"><img src="<?php //echo $user_img;             ?>" height="50" width="50"></div>-->
                        <div class="comment_contents">
                            <h2>Comment by <span><a href="<?php echo get_bloginfo('url') . '/profile-page-template/?user_id=' . $user_id; ?>"><?php echo $user_name; ?></a></span></h2>
                            <h2><?php echo $comment_date; ?></h2>
                            <p><?php echo $comment_content; ?></p>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>
    </div><!-- #content -->
</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>