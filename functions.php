<?php

/**

 * Twenty Twelve functions and definitions.

 *

 * Sets up the theme and provides some helper functions, which are used

 * in the theme as custom template tags. Others are attached to action and

 * filter hooks in WordPress to change core functionality.

 *

 * When using a child theme (see http://codex.wordpress.org/Theme_Development and

 * http://codex.wordpress.org/Child_Themes), you can override certain functions

 * (those wrapped in a function_exists() call) by defining them first in your child theme's

 * functions.php file. The child theme's functions.php file is included before the parent

 * theme's file, so the child theme functions would be used.

 *

 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached

 * to a filter or action hook.

 *

 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.

 *

 * @package WordPress

 * @subpackage Twenty_Twelve

 * @since Twenty Twelve 1.0

 */

/**

 * Sets up the content width value based on the theme's design and stylesheet.

 */

ob_start();

if (!isset($content_width))

    $content_width = 625;



/**

 * Sets up theme defaults and registers the various WordPress features that

 * Twenty Twelve supports.

 *

 * @uses load_theme_textdomain() For translation/localization support.

 * @uses add_editor_style() To add a Visual Editor stylesheet.

 * @uses add_theme_support() To add support for post thumbnails, automatic feed links,

 * 	custom background, and post formats.

 * @uses register_nav_menu() To add support for navigation menus.

 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.

 *

 * @since Twenty Twelve 1.0

 */

function cafemocha_setup() {

    /*

     * Makes Twenty Twelve available for translation.

     *

     * Translations can be added to the /languages/ directory.

     * If you're building a theme based on Twenty Twelve, use a find and replace

     * to change 'cafemocha' to the name of your theme in all the template files.

     */

    load_theme_textdomain('cafemocha', get_template_directory() . '/languages');



    // This theme styles the visual editor with editor-style.css to match the theme style.

    add_editor_style();



    // Adds RSS feed links to <head> for posts and comments.

    add_theme_support('automatic-feed-links');



    // This theme supports a variety of post formats.

    add_theme_support('post-formats', array('aside', 'image', 'link', 'quote', 'status'));



    // This theme uses wp_nav_menu() in one location.

    register_nav_menu('primary', __('Primary Menu', 'cafemocha'));



    /*

     * This theme supports custom background color and image, and here

     * we also set up the default background color.

     */

    add_theme_support('custom-background', array(

        'default-color' => 'e6e6e6',

    ));



    // This theme uses a custom image size for featured images, displayed on "standard" posts.

    add_theme_support('post-thumbnails');

    set_post_thumbnail_size(624, 9999); // Unlimited height, soft crop

}



add_action('after_setup_theme', 'cafemocha_setup');



/**

 * Adds support for a custom header image.

 */

require( get_template_directory() . '/inc/custom-header.php' );



/**

 * Returns the Google font stylesheet URL if available.

 *

 * The use of Open Sans by default is localized. For languages that use

 * characters not supported by the font, the font can be disabled.

 *

 * @since Twenty Twelve 1.2

 *

 * @return string Font stylesheet or empty string if disabled.

 */

function cafemocha_get_font_url() {

    $font_url = '';



    /* translators: If there are characters in your language that are not supported

      by Open Sans, translate this to 'off'. Do not translate into your own language. */

    if ('off' !== _x('on', 'Open Sans font: on or off', 'cafemocha')) {

        $subsets = 'latin,latin-ext';



        /* translators: To add an additional Open Sans character subset specific to your language, translate

          this to 'greek', 'cyrillic' or 'vietnamese'. Do not translate into your own language. */

        $subset = _x('no-subset', 'Open Sans font: add new subset (greek, cyrillic, vietnamese)', 'cafemocha');



        if ('cyrillic' == $subset)

            $subsets .= ',cyrillic,cyrillic-ext';

        elseif ('greek' == $subset)

            $subsets .= ',greek,greek-ext';

        elseif ('vietnamese' == $subset)

            $subsets .= ',vietnamese';



        $protocol = is_ssl() ? 'https' : 'http';

        $query_args = array(

            'family' => 'Open+Sans:400italic,700italic,400,700',

            'subset' => $subsets,

        );

        $font_url = add_query_arg($query_args, "$protocol://fonts.googleapis.com/css");

    }



    return $font_url;

}



/**

 * Enqueues scripts and styles for front-end.

 *

 * @since Twenty Twelve 1.0

 */

function cafemocha_scripts_styles() {

    global $wp_styles;



    /*

     * Adds JavaScript to pages with the comment form to support

     * sites with threaded comments (when in use).

     */

    if (is_singular() && comments_open() && get_option('thread_comments'))

        wp_enqueue_script('comment-reply');



    /*

     * Adds JavaScript for handling the navigation menu hide-and-show behavior.

     */

    wp_enqueue_script('cafemocha-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '1.0', true);



    $font_url = cafemocha_get_font_url();

    if (!empty($font_url))

        wp_enqueue_style('cafemocha-fonts', esc_url_raw($font_url), array(), null);



    /*

     * Loads our main stylesheet.

     */

    wp_enqueue_style('cafemocha-style', get_stylesheet_uri());



    /*

     * Loads the Internet Explorer specific stylesheet.

     */

    wp_enqueue_style('cafemocha-ie', get_template_directory_uri() . '/css/ie.css', array('cafemocha-style'), '20121010');

    $wp_styles->add_data('cafemocha-ie', 'conditional', 'lt IE 9');

}



add_action('wp_enqueue_scripts', 'cafemocha_scripts_styles');



/**

 * Adds additional stylesheets to the TinyMCE editor if needed.

 *

 * @uses cafemocha_get_font_url() To get the Google Font stylesheet URL.

 *

 * @since Twenty Twelve 1.2

 *

 * @param string $mce_css CSS path to load in TinyMCE.

 * @return string

 */

function cafemocha_mce_css($mce_css) {

    $font_url = cafemocha_get_font_url();



    if (empty($font_url))

        return $mce_css;



    if (!empty($mce_css))

        $mce_css .= ',';



    $mce_css .= esc_url_raw(str_replace(',', '%2C', $font_url));



    return $mce_css;

}



add_filter('mce_css', 'cafemocha_mce_css');



/**

 * Creates a nicely formatted and more specific title element text

 * for output in head of document, based on current view.

 *

 * @since Twenty Twelve 1.0

 *

 * @param string $title Default title text for current view.

 * @param string $sep Optional separator.

 * @return string Filtered title.

 */

function cafemocha_wp_title($title, $sep) {

    global $paged, $page;



    if (is_feed())

        return $title;



    // Add the site name.

    $title .= get_bloginfo('name');



    // Add the site description for the home/front page.

    $site_description = get_bloginfo('description', 'display');

    if ($site_description && ( is_home() || is_front_page() ))

        $title = "$title $sep $site_description";



    // Add a page number if necessary.

    if ($paged >= 2 || $page >= 2)

        $title = "$title $sep " . sprintf(__('Page %s', 'cafemocha'), max($paged, $page));



    return $title;

}



add_filter('wp_title', 'cafemocha_wp_title', 10, 2);



/**

 * Makes our wp_nav_menu() fallback -- wp_page_menu() -- show a home link.

 *

 * @since Twenty Twelve 1.0

 */

function cafemocha_page_menu_args($args) {

    if (!isset($args['show_home']))

        $args['show_home'] = true;

    return $args;

}



add_filter('wp_page_menu_args', 'cafemocha_page_menu_args');



/**

 * Registers our main widget area and the front page widget areas.

 *

 * @since Twenty Twelve 1.0

 */

function cafemocha_widgets_init() {

    register_sidebar(array(

        'name' => __('Main Sidebar', 'cafemocha'),

        'id' => 'sidebar-1',

        'description' => __('Appears on posts and pages except the optional Front Page template, which has its own widgets', 'cafemocha'),

        'before_widget' => '<aside id="%1$s" class="widget %2$s">',

        'after_widget' => '</aside>',

        'before_title' => '<h3 class="widget-title">',

        'after_title' => '</h3>',

    ));



    register_sidebar(array(

        'name' => __('First Front Page Widget Area', 'cafemocha'),

        'id' => 'sidebar-2',

        'description' => __('Appears when using the optional Front Page template with a page set as Static Front Page', 'cafemocha'),

        'before_widget' => '<aside id="%1$s" class="widget %2$s">',

        'after_widget' => '</aside>',

        'before_title' => '<h3 class="widget-title">',

        'after_title' => '</h3>',

    ));



    register_sidebar(array(

        'name' => __('Second Front Page Widget Area', 'cafemocha'),

        'id' => 'sidebar-3',

        'description' => __('Appears when using the optional Front Page template with a page set as Static Front Page', 'cafemocha'),

        'before_widget' => '<aside id="%1$s" class="widget %2$s">',

        'after_widget' => '</aside>',

        'before_title' => '<h3 class="widget-title">',

        'after_title' => '</h3>',

    ));



    register_sidebar(array(

        'name' => __('Footer Menu Widget Area', 'cafemocha'),

        'id' => 'sidebar-4',

        'description' => __('Optional set as Page', 'cafemocha'),

        'before_widget' => '<aside id="%1$s" class="widget %2$s">',

        'after_widget' => '</aside>',

        'before_title' => '<h3 class="widget-title">',

        'after_title' => '</h3>',

    ));



    register_sidebar(array(

        'name' => __('Footer Copyright Widget Area', 'cafemocha'),

        'id' => 'sidebar-5',

        'description' => __('Optional set as Page', 'cafemocha'),

        'before_widget' => '<aside id="%1$s" class="widget %2$s">',

        'after_widget' => '</aside>',

        'before_title' => '<h3 class="widget-title">',

        'after_title' => '</h3>',

    ));



    register_sidebar(array(

        'name' => __('Social Media Widget Area', 'cafemocha'),

        'id' => 'sidebar-6',

        'description' => __('Optional set as Page', 'cafemocha'),

        'before_widget' => '<aside id="%1$s" class="widget %2$s">',

        'after_widget' => '</aside>',

        'before_title' => '<h3 class="widget-title">',

        'after_title' => '</h3>',

    ));

}



add_action('widgets_init', 'cafemocha_widgets_init');



if (!function_exists('cafemocha_content_nav')) :



    /**

     * Displays navigation to next/previous pages when applicable.

     *

     * @since Twenty Twelve 1.0

     */

    function cafemocha_content_nav($html_id) {

        global $wp_query;



        $html_id = esc_attr($html_id);



        if ($wp_query->max_num_pages > 1) :

            ?>

            <nav id="<?php echo $html_id; ?>" class="navigation" role="navigation">

                <h3 class="assistive-text"><?php _e('Post navigation', 'cafemocha'); ?></h3>

                <div class="nav-previous"><?php next_posts_link(__('<span class="meta-nav">&larr;</span> Older posts', 'cafemocha')); ?></div>

                <div class="nav-next"><?php previous_posts_link(__('Newer posts <span class="meta-nav">&rarr;</span>', 'cafemocha')); ?></div>

            </nav><!-- #<?php echo $html_id; ?> .navigation -->

            <?php

        endif;

    }



endif;



if (!function_exists('cafemocha_comment')) :



    /**

     * Template for comments and pingbacks.

     *

     * To override this walker in a child theme without modifying the comments template

     * simply create your own cafemocha_comment(), and that function will be used instead.

     *

     * Used as a callback by wp_list_comments() for displaying the comments.

     *

     * @since Twenty Twelve 1.0

     */

    function cafemocha_comment($comment, $args, $depth) {

        $GLOBALS['comment'] = $comment;

        switch ($comment->comment_type) :

            case 'pingback' :

            case 'trackback' :

                // Display trackbacks differently than normal comments.

                ?>

                <li <?php comment_class(); ?> id="comment-<?php comment_ID(); ?>">

                    <p><?php _e('Pingback:', 'cafemocha'); ?> <?php comment_author_link(); ?> <?php edit_comment_link(__('(Edit)', 'cafemocha'), '<span class="edit-link">', '</span>'); ?></p>

                    <?php

                    break;

                default :

                    // Proceed with normal comments.

                    global $post;

                    ?>

                <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">

                    <article id="comment-<?php comment_ID(); ?>" class="comment">

                        <header class="comment-meta comment-author vcard">

                            <?php

                            echo get_avatar($comment, 44);

                            printf('<cite><b class="fn">%1$s</b> %2$s</cite>', get_comment_author_link(),

                                    // If current post author is also comment author, make it known visually.

                                    ( $comment->user_id === $post->post_author ) ? '<span>' . __('Post author', 'cafemocha') . '</span>' : ''

                            );

                            printf('<a href="%1$s"><time datetime="%2$s">%3$s</time></a>', esc_url(get_comment_link($comment->comment_ID)), get_comment_time('c'),

                                    /* translators: 1: date, 2: time */ sprintf(__('%1$s at %2$s', 'cafemocha'), get_comment_date(), get_comment_time())

                            );

                            ?>

                        </header><!-- .comment-meta -->



                        <?php if ('0' == $comment->comment_approved) : ?>

                            <p class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'cafemocha'); ?></p>

                        <?php endif; ?>



                        <section class="comment-content comment">

                            <?php comment_text(); ?>

                            <?php edit_comment_link(__('Edit', 'cafemocha'), '<p class="edit-link">', '</p>'); ?>

                        </section><!-- .comment-content -->



                        <div class="reply">

                            <?php comment_reply_link(array_merge($args, array('reply_text' => __('Reply', 'cafemocha'), 'after' => ' <span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth']))); ?>

                        </div><!-- .reply -->

                    </article><!-- #comment-## -->

                    <?php

                    break;

            endswitch; // end comment_type check

        }



    endif;



    if (!function_exists('cafemocha_entry_meta')) :



        /**

         * Prints HTML with meta information for current post: categories, tags, permalink, author, and date.

         *

         * Create your own cafemocha_entry_meta() to override in a child theme.

         *

         * @since Twenty Twelve 1.0 

         */

        function cafemocha_entry_meta() {

            // Translators: used between list items, there is a space after the comma.

            $categories_list = get_the_category_list(__(', ', 'cafemocha'));



            // Translators: used between list items, there is a space after the comma.

            $tag_list = get_the_tag_list('', __(', ', 'cafemocha'));



            $date = sprintf('<a href="%1$s" title="%2$s" rel="bookmark"><time class="entry-date" datetime="%3$s">%4$s</time></a>', esc_url(get_permalink()), esc_attr(get_the_time()), esc_attr(get_the_date('c')), esc_html(get_the_date())

            );



            $author = sprintf('<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s" rel="author">%3$s</a></span>', esc_url(get_author_posts_url(get_the_author_meta('ID'))), esc_attr(sprintf(__('View all posts by %s', 'cafemocha'), get_the_author())), get_the_author()

            );



            // Translators: 1 is category, 2 is tag, 3 is the date and 4 is the author's name.

            if ($tag_list) {

                $utility_text = __('This entry was posted in %1$s and tagged %2$s on %3$s<span class="by-author"> by %4$s</span>.', 'cafemocha');

            } elseif ($categories_list) {

                $utility_text = __('This entry was posted in %1$s on %3$s<span class="by-author"> by %4$s</span>.', 'cafemocha');

            } else {

                $utility_text = __('This entry was posted on %3$s<span class="by-author"> by %4$s</span>.', 'cafemocha');

            }



            printf(

                    $utility_text, $categories_list, $tag_list, $date, $author

            );

        }



    endif;



    /**

     * Extends the default WordPress body class to denote:

     * 1. Using a full-width layout, when no active widgets in the sidebar

     *    or full-width template.

     * 2. Front Page template: thumbnail in use and number of sidebars for

     *    widget areas.

     * 3. White or empty background color to change the layout and spacing.

     * 4. Custom fonts enabled.

     * 5. Single or multiple authors.

     *

     * @since Twenty Twelve 1.0

     *

     * @param array Existing class values.

     * @return array Filtered class values.

     */

    function cafemocha_body_class($classes) {

        $background_color = get_background_color();

        $background_image = get_background_image();



        if (!is_active_sidebar('sidebar-1') || is_page_template('page-templates/full-width.php'))

            $classes[] = 'full-width';



        if (is_page_template('page-templates/front-page.php')) {

            $classes[] = 'template-front-page';

            if (has_post_thumbnail())

                $classes[] = 'has-post-thumbnail';

            if (is_active_sidebar('sidebar-2') && is_active_sidebar('sidebar-3'))

                $classes[] = 'two-sidebars';

        }



        if (empty($background_image)) {

            if (empty($background_color))

                $classes[] = 'custom-background-empty';

            elseif (in_array($background_color, array('fff', 'ffffff')))

                $classes[] = 'custom-background-white';

        }



        // Enable custom font class only if the font CSS is queued to load.

        if (wp_style_is('cafemocha-fonts', 'queue'))

            $classes[] = 'custom-font-enabled';



        if (!is_multi_author())

            $classes[] = 'single-author';



        return $classes;

    }



    add_filter('body_class', 'cafemocha_body_class');



    add_filter('show_admin_bar', '__return_false');



    /**

     * Adjusts content_width value for full-width and single image attachment

     * templates, and when there are no active widgets in the sidebar.

     *

     * @since Twenty Twelve 1.0

     */

    function cafemocha_content_width() {

        if (is_page_template('page-templates/full-width.php') || is_attachment() || !is_active_sidebar('sidebar-1')) {

            global $content_width;

            $content_width = 960;

        }

    }



    add_action('template_redirect', 'cafemocha_content_width');



    /**

     * Add postMessage support for site title and description for the Theme Customizer.

     *

     * @since Twenty Twelve 1.0

     *

     * @param WP_Customize_Manager $wp_customize Theme Customizer object.

     * @return void

     */

    function cafemocha_customize_register($wp_customize) {

        $wp_customize->get_setting('blogname')->transport = 'postMessage';

        $wp_customize->get_setting('blogdescription')->transport = 'postMessage';

        $wp_customize->get_setting('header_textcolor')->transport = 'postMessage';

    }



    add_action('customize_register', 'cafemocha_customize_register');



    /**

     * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.

     *

     * @since Twenty Twelve 1.0

     */

    function cafemocha_customize_preview_js() {

        wp_enqueue_script('cafemocha-customizer', get_template_directory_uri() . '/js/theme-customizer.js', array('customize-preview'), '20130301', true);

    }



    add_action('customize_preview_init', 'cafemocha_customize_preview_js');



// widget //

    class RandomPostWidget extends WP_Widget {



        function RandomPostWidget() {

            $widget_ops = array('classname' => 'RandomPostWidget', 'description' => 'Displays a random post with thumbnail');

            $this->WP_Widget('RandomPostWidget', 'Custom Login', $widget_ops);

        }



        function form($instance) {

            $instance = wp_parse_args((array) $instance, array('title' => ''));

            $title = $instance['title'];

            ?>

            <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>

            <?php

        }



        function update($new_instance, $old_instance) {

            $instance = $old_instance;

            $instance['title'] = $new_instance['title'];

            return $instance;

        }



        function widget($args, $instance) {

            extract($args, EXTR_SKIP);



            echo $before_widget;

            $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);



            if (!empty($title))

                echo $before_title . $title . $after_title;;



            // WIDGET CODE GOES HERE

            //echo "<h1>This is my new widget!</h1>";



            if (isset($_COOKIE['common_login_username']) && isset($_COOKIE['common_login_userpass'])) {

                $username = $_COOKIE['common_login_username'];

                $passowrd = $_COOKIE['common_login_userpass'];

                $remember = "on";

            } else {

                $username = '';

                $passowrd = '';

                $remember = '';

            }

            $error_message = '';

            $success_message = '';

            if (isset($_POST['user_login_submit'])) {

                $username1 = trim(stripslashes($_POST['user_login_username']));

                if (email_exists($username1)) {

                    global $wpdb;

                    $query = " SELECT * FROM wp_users where user_email='$username1'";

                    $rs = $wpdb->get_results($query);

                    $username = $rs[0]->user_login;

                } else {

                    $username = $username1;

                }

                $password = trim(stripslashes($_POST['user_login_password']));

                $remenber = trim(stripslashes($_POST['user_login_remember']));



// echo $sql= "select * from wp_users where user_login='$username' and user_pass='$password'";

                if ($username == '')

                    $error_message = 'ERROR: Please enter a Email/User Name';

                elseif ($password == '')

                    $error_message = 'ERROR: Please enter a password';



                else {



                    $creds = array();

//$creds['user_login'] = $username;

                    $creds['user_login'] = $username;

                    $creds['user_password'] = $password;

                    $user = wp_signon($creds, true);

                    $user_status = get_user_meta($user->ID, 'status', true);

                    if (is_wp_error($user)) {

                        $error_message = 'ERROR: Invalid username or password';

                    }/* else if ($user_status == 0) {

                      $error_message = 'ERROR: Your Account is not activated yet';

                      } */ else {

                        if (isset($_POST['user_login_remember']) && $_POST['user_login_remember'] != "") {

                            setcookie("common_login_username", $username, time() + (60 * 60 * 24 * 7));

                            setcookie("common_login_userpass", $password, time() + (60 * 60 * 24 * 7));

                        } else {

                            setcookie("common_login_username", $username, time() - (60 * 60 * 24 * 7));

                            setcookie("common_login_userpass", $password, time() - (60 * 60 * 24 * 7));

                        }

                        global $wpdb;



                        wp_safe_redirect(get_bloginfo('siteurl'));

                        exit;

                    }

                } if ($error_message != '') {

                    set_transient('error_msg', $error_message, 30);

                    wp_redirect(get_permalink());

                    exit;

                }

            }

            $m = get_transient('error_msg');

            ?>



            <div class="sub_tempalte_page">

                <div class="all_form_con">

                    <?php if ($m) { ?>

                        <div class="error_message">

                            <?php

                            echo $m = get_transient('error_msg');

                            delete_transient('error_msg');

                            ?></div>

                    <?php } ?>

                    <form action="" method="post">

                        <div class="form_con_con">





                            <div class="formrowarea">

                                <input type="text" name="user_login_username" value="<?= $username ?>" class="text_field" placeholder="User Name Or Email Address" />

                            </div>



                            <div class="formrowarea">

                                <input type="password" name="user_login_password" value="<?= $passowrd ?>" class="text_field" placeholder="Password" />

                            </div>





                            <div class="formrowarea">

                                <input type="checkbox" name="user_login_remember" <?php if ($remember == "on") echo 'checked="checked"'; ?> style="margin-top:8px;" /><span>Remember Me</span>

                            </div>



                            <div class="form_login">

                                <input type="submit" name="user_login_submit" value="Log In" class="search_btn" />

                            </div>





                            <div class="formrowarea">

                                <a href="<?php echo get_bloginfo('siteurl'); ?>/forget-password">Forget Password?</a>

                                <a href="<?php echo get_page_link(63); ?>">Create A New Account?</a>

                                <div><?php do_action('oa_social_login'); ?></div>

                            </div>

                        </div>

                    </form>



                </div>

            </div>

            <?php

            echo $after_widget;

        }



    }



    add_action('widgets_init', create_function('', 'return register_widget("RandomPostWidget");'));



    class mocha extends WP_Widget {



        function mocha() {

            $widget_ops = array('classname' => 'mocha', 'description' => 'custom mocha');

            $this->WP_Widget('mocha', 'custom mocha', $widget_ops);

        }



        function form($instance) {

            $instance = wp_parse_args((array) $instance, array('title' => ''));

            $title = $instance['title'];

            ?>

            <p><label for="<?php echo $this->get_field_id('title'); ?>">Title: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>

            <?php

        }



        function update($new_instance, $old_instance) {

            $instance = $old_instance;

            $instance['title'] = $new_instance['title'];

            return $instance;

        }



        function widget($args, $instance) {

            extract($args, EXTR_SKIP);



            echo $before_widget;

            $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);



            if (!empty($title))

                echo $before_title . $title . $after_title;;



            // WIDGET CODE GOES HERE

            //echo "<h1>This is my new widget!</h1>";

            global $wpdb;

            $rated_cate = $wpdb->get_results("select category from wp_sipping GROUP BY category Order by SUM(sip_it) DESC LIMIT 5");

            ?>

            <ul>

                <?php

                foreach ($rated_cate as $rated_cate_val) {

                    $cat = $rated_cate_val->category;

                    $yourcat = get_category($cat);

                    $cat_name = $yourcat->slug;

                    $catname = $yourcat->name;

                    ?>

                    <li><a href='<?php echo get_bloginfo('url') . '/category/' . $cat_name; ?>'><?php echo $catname; ?></a></li>

                <?php }

                ?>

                <?php

                $categories = get_all_category_ids();

                ?>

                <div class='cat_drop'>

                    <select onchange="window.location = this.options[this.selectedIndex].value;">

                        <?php

                        foreach ($categories as $category) {

                            if (!in_array($category, array(1))) {

                                $cat = get_category($category);

                                $cat_name = $cat->slug;

                                ?>

                                <option value="<?php echo get_bloginfo('url') . '/category/' . $cat_name; ?>"><?php echo $cat->name; ?></option>

                                <?php

                            }

                        }

                        ?>

                    </select>

                </div>

            </ul>



            <?php

            echo $after_widget;

        }



    }



    add_action('widgets_init', create_function('', 'return register_widget("mocha");'));

//end//







    add_action('wp_enqueue_scripts', 'cus_script');



    function cus_script() {

        wp_enqueue_script('jquery');

        wp_register_script('cafemocha-js', get_bloginfo('url') . '/wp-content/themes/cafemocha/js/jquery.min.js');

        wp_enqueue_script('cafemocha-js');

    }



    add_action('wp_head', 'nwt_ajax_load_head');



    function nwt_ajax_load_head() {

        echo '<script type="text/javascript">var ajaxurl = "' . admin_url('admin-ajax.php') . '";</script>';

    }



    require 'ajax.php';

    require_once(ABSPATH . "wp-content/themes/cafemocha" . "/admin/adminend.php");

    add_action('admin_menu', 'register_custom_menu_page');



    function register_custom_menu_page() {

        add_menu_page('Manage Category', 'Manage Category', 10, 'manage_category', 'manage_category');

//    add_submenu_page('paypal_email', 'Level Implementation', 'Level Implementation', 10, 'level_implementation', 'level_implementation');

//    add_submenu_page('paypal_email', 'Pattern', 'Pattern', 10, 'pattern', 'pattern');

//    add_submenu_page('amenity', 'Applied Amenity', 'Manage amenity', 10, 'applied-amenity', 'applied_amenity');

    }



    add_action('wp_ajax_loader', 'loader');

    add_action('wp_ajax_nopriv_loader', 'loader');



    function loader() {

        require_once(get_template_directory() . '/custom_functions/sipping.php');

        global $wpdb, $current_user;

        $id = $_POST['id'];

        $limit = $id + 5;

        if (is_user_logged_in()) {

            global $current_user;

            get_currentuserinfo();

            $posts_array = $wpdb->get_results("SELECT * FROM `wp_posts` p LEFT JOIN wp_sipping v ON p.`ID` = v.post_id WHERE p.post_type='post' and p.post_status='publish' GROUP BY p.`ID` Order by SUM(v.sip_it) DESC limit $id,5");

        } else {

            $posts_array = $wpdb->get_results("SELECT * FROM `wp_posts` p LEFT JOIN wp_sipping v ON p.`ID` = v.post_id WHERE p.post_type='post' and p.post_status='publish' GROUP BY p.`ID` Order by SUM(v.sip_it) DESC limit $id,5");

        }

        //echo "SELECT * FROM `wp_posts` p LEFT JOIN wp_sipping v ON p.`ID` = v.post_id WHERE p.post_type='post' and p.post_status='publish' GROUP BY p.`ID` Order by SUM(v.sip_it) DESC limit $id,5";

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

                <div class="normal_post_war chima" id="<?php echo $limit; ?>">

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

                                <a href="<?php //echo $meta[attached_file][0];                                         ?>"></a>

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

        die;

    }



    add_action('wp_ajax_mostrecent_loader', 'mostrecent_loader');

    add_action('wp_ajax_nopriv_mostrecent_loader', 'mostrecent_loader');



    function mostrecent_loader() {

        require_once(get_template_directory() . '/custom_functions/sipping.php');

        global $wpdb, $current_user;

        $id = $_POST['id'];

        $limit = $id + 5;

        if (is_user_logged_in()) {

            global $current_user;

            get_currentuserinfo();

            @$moca = unserialize($current_user->Choosed_Mocha);

            if ($moca == false) {

                $moca = array();

                $query = "SELECT * FROM wp_posts WHERE post_type='post' and post_status='publish' ORDER BY post_date DESC limit $id,5";

            } else {

                $mocas = implode(",", $moca);

                $query = "SELECT * FROM wp_posts,wp_postmeta WHERE wp_posts.ID=wp_postmeta.post_id and wp_postmeta.meta_value IN($mocas) and wp_postmeta.meta_key='mocas' and post_type='post' and post_status='publish' ORDER BY post_date DESC limit $id,5";

            }

        } else {

            $query = "SELECT * FROM wp_posts WHERE post_type='post' and post_status='publish' ORDER BY post_date DESC limit $id,5";

        }



        $postsarray = $wpdb->get_results($query);



        foreach ($postsarray as $r) {

            $post_datetime = $r->post_date;

            $explode = explode(' ', $post_datetime);

            $newDate = $explode[0];

            $post_date = date('M d,Y', strtotime($post_datetime));

            $meta = get_post_meta($r->ID);

            $args = array(

                'post_id' => $r->ID, //use post_id, not post_ID

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

                <div class="normal_post_war chima" id="<?php echo $limit; ?>">

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

        die;

    }



    add_action('wp_ajax_mostview_loader', 'mostview_loader');

    add_action('wp_ajax_nopriv_mostview_loader', 'mostview_loader');



    function mostview_loader() {

        require_once(get_template_directory() . '/custom_functions/sipping.php');

        global $wpdb, $current_user;

        $id = $_POST['id'];

        $limit = $id + 5;

        if (is_user_logged_in()) {

            global $current_user;

            get_currentuserinfo();

            $moca = unserialize($current_user->Choosed_Mocha);

            if ($moca == false) {

                $moca = array();

                $posts_array = $wpdb->get_results("SELECT * FROM `wp_posts` p LEFT JOIN wp_views v ON p.`ID` = v.post_id WHERE p.post_type='post' and p.post_status='publish' group by v.post_id Order by SUM(v.views) DESC limit $id,5");

            } else {

                $posts_array = $wpdb->get_results("SELECT * FROM `wp_posts` p LEFT JOIN wp_views v ON p.`ID` = v.post_id WHERE p.post_type='post' and p.post_status='publish' group by v.post_id Order by SUM(v.views) DESC limit $id,5");

            }

            //$posts_array = $wpdb->get_results("SELECT * FROM `wp_posts` p LEFT JOIN wp_views v ON p.`ID` = v.post_id WHERE p.post_type='post' and p.post_status='publish' Order by v.views DESC");

        } else {

            $posts_array = $wpdb->get_results("SELECT * FROM `wp_posts` p LEFT JOIN wp_views v ON p.`ID` = v.post_id WHERE p.post_type='post' and p.post_status='publish' group by v.post_id Order by SUM(v.views) DESC limit $id,5");

        }

        //echo "SELECT * FROM wp_posts,wp_postmeta,wp_views WHERE wp_posts.ID=wp_postmeta.post_id AND wp_posts.ID=wp_views.post_id AND wp_views.post_id=wp_postmeta.post_id AND wp_postmeta.meta_value IN($mocas) and wp_postmeta.meta_key='mocas' and post_type='post' and post_status='publish' ORDER BY wp_views.views DESC limit 5";

        foreach ($posts_array as $r) {

            $post_datetime = $r->post_date;

            $explode = explode(' ', $post_datetime);

            $newDate = $explode[0];

            $post_date = date('M d,Y', strtotime($post_datetime));

            $meta = get_post_meta($r->ID);

            $args = array('post_id' => $r->ID,);

            $comments = get_comments($args);

            $views = $wpdb->get_results("select SUM(views) as views from wp_views where post_id='$r->ID'");

            $view = $views[0]->views;

            $term_name = get_term_by('id', $meta[mocas][0], 'category')->slug;

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

                ?>

                <div class="normal_post_war chima" id="<?php echo $limit; ?>">

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

        die;

    }



    add_action('wp_ajax_mostdiscussed_loader', 'mostdiscussed_loader');

    add_action('wp_ajax_nopriv_mostdiscussed_loader', 'mostdiscussed_loader');



    function mostdiscussed_loader() {

        require_once(get_template_directory() . '/custom_functions/sipping.php');

        global $wpdb, $current_user;

        $id = $_POST['id'];

        $limit = $id + 5;

        if (is_user_logged_in()) {

            global $current_user;

            get_currentuserinfo();

            @$moca = unserialize($current_user->Choosed_Mocha);

            if ($moca == false) {

                $moca = array();

            }

            $posts_array = $wpdb->get_results("select * from wp_posts where `post_type` = 'post' and `post_status` = 'publish' group by ID Order by (select count(comment_post_ID) FROM wp_comments WHERE comment_post_ID = ID ) DESC limit $id,5");

        } else {

            $posts_array = $wpdb->get_results("select * from wp_posts where `post_type` = 'post' and `post_status` = 'publish' group by ID Order by (select count(comment_post_ID) FROM wp_comments WHERE comment_post_ID = ID ) DESC limit $id,5");

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

                <div class="normal_post_war chima" id="<?php echo $limit; ?>">

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

        die;

    }



    add_action('wp_ajax_closed', 'closed');

    add_action('wp_ajax_nopriv_closed', 'closed');



    function closed() {

        global $wpdb;

        $id = $_POST['id'];

        $wpdb->get_results("delete from  wp_messages where id='$id'");



        die;

    }



    add_action('wp_ajax_reply_notification', 'reply_notification');

    add_action('wp_ajax_nopriv_reply_notification', 'reply_notification');



    function reply_notification() {

        global $wpdb;

        $id = $_POST['id'];

        $reply = $_POST['reply'];

        $date = date('Y-m-d');

        $notify_info = $wpdb->get_results("select * from wp_messages where id='$id'");

        $user_id = $notify_info[0]->user_id;

        $sender_id = $notify_info[0]->sender_id;

        $in_data = array('user_id' => $sender_id, 'sender_id' => $user_id, 'message' => $reply, 'date' => $date, 'status' => '0');

        $wpdb->insert('wp_messages', $in_data);

        ?>

        <textarea name="reply" id="reply" rows="4" cols="20"></textarea>

        <?php

        die;

    }



    function x_week_range(&$start_date, &$end_date, $date) {

        $ts = strtotime($date);

        $start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);

        $start_date = date('Y-m-d', $start);

        $end_date = date('Y-m-d', strtotime('next saturday', $start));

    }



    function x_month_range(&$start_date, &$end_date, $date) {

        $start_date = date('01-m-Y', strtotime($date));

        //$end_date = date('d-m-Y', mktime(0, 0, 0, date("m",$date), date("t",$date), date("Y",$date)));

        $end_date = date('t-m-Y', strtotime($date));

    }

function formatSizeUnits($bytes)

    {

        if ($bytes >= 1073741824)

        {

            $bytes = number_format($bytes / 1073741824, 2) . ' GB';

        }

        elseif ($bytes >= 1048576)

        {

            $bytes = number_format($bytes / 1048576, 2) . ' MB';

        }

        elseif ($bytes >= 1024)

        {

            $bytes = number_format($bytes / 1024, 2) . ' KB';

        }

        elseif ($bytes > 1)

        {

            $bytes = $bytes . ' bytes';

        }

        elseif ($bytes == 1)

        {

            $bytes = $bytes . ' byte';

        }

        else

        {

            $bytes = '0 bytes';

        }



        return $bytes;

}

function _remove_script_version( $src ){
$parts = explode( '?', $src );
return $parts[0];
}
add_filter( 'script_loader_src', '_remove_script_version', 15, 1 );
add_filter( 'style_loader_src', '_remove_script_version', 15, 1 );  