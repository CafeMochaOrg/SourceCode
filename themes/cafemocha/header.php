<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
    <!--<![endif]-->
    <head>
	<meta name='yandex-verification' content='63eb0b2c18bc4ea7' />
        <meta charset="<?php bloginfo('charset'); ?>" />
	<meta name="author" content="Rajat Bhageria">
	<meta name="language" content="english"> 
        <meta name="viewport" content="width=device-width" />
        <?php //wp_title();?>
        <title><?php echo 'CafeMocha' . ' | ' . 'Where Creativity Flows' . ' | ' . 'Publish your Creativity'; ?></title>
        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <link rel="shortcut icon" href="<?php bloginfo('template_directory'); ?>/custom-images/favicon-1.ico" /> 
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
        <?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
        <!--[if lt IE 9]>
        <script src="<?php echo get_template_directory_uri(); ?>/js/html5.js" type="text/javascript"></script>
        <![endif]-->
        <?php wp_head(); ?>
    </head>

    <body <?php body_class(); ?>>
        <div id="page" class="hfeed site">
            <header id="masthead" class="site-header" role="banner">
                <div class="header_wrapper">
                    <hgroup>
                        <h1 class="site-title"><a href="<?php echo esc_url(home_url('/')); ?>" title="<?php echo esc_attr(get_bloginfo('name', 'display')); ?>" rel="home"><?php bloginfo('name'); ?></a></h1>
                        <h2 class="site-description"><?php bloginfo('description'); ?></h2>
                    </hgroup>

                    <!--Logo Section -->
                    <div class="logo">
                        <?php
                        $header_image = get_header_image();
                        if (!empty($header_image)) :
                            ?>
                            <a href="<?php echo esc_url(home_url('/')); ?>"><img src="<?php echo esc_url($header_image); ?>" class="header-image" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="" /></a>
                        <?php endif; ?>
                    </div>
                    <!--Logo Section -->

                    <!--Log out-->
                    <?php
                    if (is_user_logged_in()) {
                        global $wpdb;
                        $current_user = wp_get_current_user();
                        $current_user_id = $current_user->ID;
                        if (is_page('my-message')) {
                            $wpdb->get_results("update wp_messages set status='1' where user_id='$current_user_id'");
                        }
                        $message = $wpdb->get_results("select * from wp_messages where user_id='$current_user_id' and status='0'");
                        ?>
                        <div class="log_out">
                            <ul>

                                <li>Welcome <?php
                                    $post_name = $current_user->post_name;
                                    if ($post_name == 1) {
                                        echo $current_user->user_login;
                                    } else {
                                        echo $current_user->Alternative_User;
                                    }
                                    ?></li>
                                <li><a href="<?php echo site_url() . '/my-account-2/'; ?>">My Account</a></li>
                                <li><a href="<?php echo get_page_link(92); ?>">Profile</a></li>
                                <li><a href="<?php echo site_url() . '/my-message/'; ?>">My Messages<div>(<?php echo count($message); ?>)</div></a></li>
                                <li><a href="<?php echo wp_logout_url(site_url()); ?>">Logout?</a></li>

                            </ul>
                        </div>
                    <?php }
                    ?>
                    <!--Log out-->

                    <!--Search Section -->
                    <div class="search_wrapper">
                        <?php get_search_form(); ?>
                        <div class="advance_search"><a href="javascript:void();">Advanced Search</a></div>
                        <form role="search" method="get" id="searchform" action="<?php echo get_bloginfo('siteurl') . '/search-results' ?>" >
                            <div class="advance_search_click">
                                <h2>Search by category</h2>
                                <select name="cat_search[]" multiple="multiple">

                                    <?php
                                    $args = array('exclude' => array('1'),
                                        'orderby' => id,
                                        'hide_empty' => 0
                                    );
                                    $mocha = get_terms('category', $args);
                                    foreach ($mocha as $mocha_val) {
                                        $cat_name = $mocha_val->name;
                                        $cat_id = $mocha_val->term_id;
                                        ?>
                                        <option <?php if ($mocha_val->term_id == $_GET['cat_search']) echo 'selected="selected"'; ?> value="<?php echo $cat_id; ?>"><?php echo $cat_name; ?></option>
                                    <?php } ?>
                                </select>
                                <h2>User Name</h2>
                                <input type="text" name="username" value="" />
                                <h2>Story Title</h2>
                                <input type="text" name="title" value="" />
                                <input type="submit" id="" value="Advanced Search" />
                            </div>
                        </form>
                    </div>
                    <script type="text/javascript">
                        jQuery(document).ready(function() {
                            jQuery(".advance_search_click").hide();
                            jQuery(".advance_search").click(function() {
                                jQuery(".advance_search_click").toggle();
                            });
                        });
                    </script>
                    <!--Search Section -->

                    <!--Header Menu Section -->
                    <nav id="site-navigation" class="main-navigation" role="navigation">
                        <h3 class="menu-toggle"><?php _e('Menu', 'cafemocha'); ?></h3>
                        <a class="assistive-text" href="#content" title="<?php esc_attr_e('Skip to content', 'cafemocha'); ?>"><?php _e('Skip to content', 'cafemocha'); ?></a>
                        <?php
                        if (!is_page('my-account-2')) {
                            if (!is_page('profile-page-template')) {
                                if (!is_page('my-message')) {
                                    if (!is_category()) {
                                        wp_nav_menu(array('theme_location' => 'primary', 'menu_class' => 'nav-menu'));
                                    }
                                }
                            }
                        }
                        if (is_category()) {
                            $cur_cat_id = get_cat_id(single_cat_title("", false));
                            $cat = get_term_by('id', $cur_cat_id, 'category');
                            $cat_name = $cat->name;
                            ?>
                            <ul>
                                <li><a href="<?php echo get_bloginfo('url') . '/category/' . $cat_name . '/?most_recent=true'; ?>"><span>Most Recent</span></a></li>
                                <li><a href="<?php echo get_bloginfo('url') . '/category/' . $cat_name . '/?most_viewed=true'; ?>"><span>Most Viewed</span></a></li>
                                <li><a href="<?php echo get_bloginfo('url') . '/category/' . $cat_name . '/?top_rated=true'; ?>"><span>Top Rated</span></a></li>
                                <li><a href="<?php echo get_bloginfo('url') . '/category/' . $cat_name . '/?most_discussed=true'; ?>"><span>Most Discussed</span></a></li>
                            </ul> <?php
                        } else if (is_page('profile-page-template')) {
                            if ($_GET['user_id']) {
                                $user_id = $_GET['user_id'];
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
                                $post_name = get_user_meta($user_id, 'post_name', TRUE);
                                if ($post_name == 1) {
                                    $user = get_userdata($user_id);
                                    $user_name = $user->user_login;
                                } else {
                                    $user_name = get_user_meta($user_id, 'Alternative_User', TRUE);
                                }
                            }
                            ?><ul>
                                <li>Arrange Stories <span id='u'> Uploaded  </span> by <?php echo $user_name?> </li>
                                <li><a href="<?php echo get_page_link(92) . '?sectionby=d' ?>"><span>Daily</span></a></li>
                                <li><a href="<?php echo get_page_link(92) . '?sectionby=w' ?>"><span>Weekly</span></a></li>
                                <li><a href="<?php echo get_page_link(92) . '?sectionby=m' ?>"><span>Monthly</span></a></li>
                            </ul>
                        <?php }
                        ?>
                    </nav><!-- #site-navigation -->
                    <!--Header Menu Section -->

                    <!--Social Media Section -->
                    <div class="header_social_media">
                        <!--<a href="#"><img src="<?php echo bloginfo('template_url'); ?>/custom-images/twitter_icon.png"></a>
                        <a href="#"><img src="<?php echo bloginfo('template_url'); ?>/custom-images/facebook_icon.png"></a>
                        <a href="#"><img src="<?php echo bloginfo('template_url'); ?>/custom-images/linkin_icon.png"></a>
                        <a href="#"><img src="<?php echo bloginfo('template_url'); ?>/custom-images/myspace_icon.png"></a>-->
                        <?php dynamic_sidebar('Social Media Widget Area'); ?>
                    </div>
                    <!--Social Media Section -->
                    <?php
                    if (is_user_logged_in()) {
                        ?>
                        <!--Publish Section -->
                        <div class="publish_cont">
                            <a href="<?php echo get_bloginfo('siteurl') . '/publish' ?>" class="publish_but" onMouseOver="show_create_moca();" onMouseOut="hide_create_moca()">Publish</a>
                            <div class="create_moca" onMouseOver="show_create_moca();" onMouseOut="hide_create_moca();">
                                <ul>
                                    <li><a href="<?php echo get_page_link(71); ?>">Publish A Story</a></li>
                                    <li><a href="<?php echo get_page_link(128); ?>">Publish A Document</a></li>
                                    <li><a href="<?php echo get_page_link(87); ?>">Create New Mocha</a></li>

                                </ul>
                            </div>
                            <script>
                            jQuery(".create_moca").hide();
                            function show_create_moca()
                            {
                                jQuery(".create_moca").show();
                            }
                            function hide_create_moca()
                            {
                                jQuery(".create_moca").hide();
                            }
                            </script>
                        </div>
                        <!--Publish Section -->
                        <?php
                    } else {
                        ?>
                        <div class="publish_cont">
                            <a href="javascript:void();" class="publish_but" onClick="login_msg();" onMouseOver="show_create_moca();" onMouseOut="hide_create_moca()">Publish</a>
                            <div class="create_moca" onMouseOver="show_create_moca();" onMouseOut="hide_create_moca();">
                                <ul>
                                    <li><a href="javascript:void();" onClick="login_msg();">Publish A Story</a></li>
                                    <li><a href="javascript:void();" onClick="login_msg();">Publish A Document</a></li>
                                    <li><a href="javascript:void();" onClick="login_msg();">Create New Mocha</a></li>

                                </ul>
                            </div>
                            <script>
                            jQuery(".create_moca").hide();
                            function show_create_moca()
                            {
                                jQuery(".create_moca").show();
                            }
                            function hide_create_moca()
                            {
                                jQuery(".create_moca").hide();
                            }
                            </script>
                        </div>
                    <?php } ?>

                    <br clear="all"/>
                </div><!-- #header_wrapper -->
            </header><!-- #masthead -->

            <div id="main" class="wrapper">