<?php
/**
 * Template Name: My Message Page Template
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
?>
<script class="jsbin" src="<?php echo get_bloginfo('siteurl') . '/wp-content/themes/cafemocha/js/cafemocha/sip.js'; ?>"></script>
<script type="text/javascript" src="<?php echo get_bloginfo('url') . '/wp-content/themes/cafemocha/autosuggest/src/jquery.tokeninput.js' ?>"></script>

<link rel="stylesheet" href="<?php echo get_bloginfo('url') . '/wp-content/themes/cafemocha/autosuggest/styles/token-input.css' ?>" type="text/css" />
<link rel="stylesheet" href="<?php echo get_bloginfo('url') . '/wp-content/themes/cafemocha/autosuggest/styles/token-input-facebook.css' ?>" type="text/css" />

<div id="primary" class="site-content">
    <div id="content" role="main">
        <?php
        global $wpdb, $current_user;
        get_currentuserinfo();
        $user_id = $current_user->id;

        if ($_GET['senditem']) {
            $notification = $wpdb->get_results("select * from wp_messages where sender_id='$user_id' ORDER BY id DESC");
        } else {
            $notification = $wpdb->get_results("select * from wp_messages where user_id='$user_id' ORDER BY id DESC");
        }
        ?>

        <div class="short_by">
            <ul><li>Messages</li></ul>
        </div>
        <div class="message_links">
            <ul>
                <li><a href="<?php echo $dashboard_url; ?>?notification=true&senditem=true" class="btn btn-primary">Sent Items</a></li>
                <li><a href="<?php echo $dashboard_url; ?>?notification=true" class="btn btn-primary">Inbox</a></li>
                <li><a href="<?php echo $dashboard_url; ?>?notification=true&compose=true" class="btn btn-primary">Compose</a></li>
            </ul>



        </div>


        <?php if ($_GET['compose']) { ?>
            <!--message section-->

            <?php
            if (is_user_logged_in()) {
                global $wpdb, $current_user;
                get_currentuserinfo();
                $user_id = $current_user->id;
                if (isset($_POST['save'])) {
                    $register = $_POST['register'];
                    $message = trim(stripslashes($register['message']));
                    $user = trim(stripslashes($register['user']));

                    $date = date('Y-m-d');
                    $explode = explode(',', $user);
                    if ($user == '')
                        $error_message = 'ERROR : Please Select User';
                    else if ($message == '')
                        $error_message = 'ERROR : Please Enter Message';

                    if ($error_message != '') {
                        set_transient('error_msg', $error_message, 30);
                        set_transient('register', $register);
                        wp_redirect(get_bloginfo('siteurl') . '/my-message/?notification=true&compose=true');
                        exit;
                    } else {
                        foreach ($explode as $e_val) {

                            $in_data = array('user_id' => $e_val, 'sender_id' => $user_id, 'message' => $message, 'date' => $date, 'status' => '0');
                            $wpdb->insert('wp_messages', $in_data);
                        }
//                        $success_message="";
//                        set_transient('success_msg', $success_message, 30);
                        wp_redirect(get_bloginfo('siteurl') . '/my-message/?notification=true');
                        exit();
                    }
                }
                $mm = get_transient('error_msg');
                $register = get_transient('register');
                delete_transient('error_msg');
                delete_transient('register');
                ?>
                <?php
                if ($m != "") {
                    ?>
                    <div class="success_message"><?php echo $m; ?></div>
                <?php } ?>
                <?php
                if ($mm != "") {
                    ?>
                    <div class="error_message"><?php echo $mm; ?></div>
                <?php } ?>
                <div class="message_box">
                    <form method="post">
                        <input type="hidden" name="user_id" value="<?php echo $user_id; ?>" />
                        <div class="form_cont">
                            <div class="form_left"><lable>select user*</lable></div>
                            <div class="form_right">
                                <input type="text" id="demo-input-facebook-theme" name="register[user]" value="" class="form_input" />

                                <script type="text/javascript">
                                    jQuery(document).ready(function() {
                                        jQuery("#demo-input-facebook-theme").tokenInput("<?php echo get_bloginfo('url') . '/wp-content/themes/cafemocha' ?>/autosuggestuser.php", {
                                            prePopulate: [
        <?php
        if ($register['user'] != "") {

            $explode = explode(',', $register['user']);
            foreach ($explode as $e_val) {
                $user_info = get_userdata($e_val);
                $username = $user_info->user_login;
                ?>
                                                        {id: <?php echo $e_val; ?>, name: "<?php echo $username; ?>"},
            <?php }
        }
        ?>
                                            ],
                                            theme: "facebook",
                                            preventDuplicates: true

                                        });
                                    });</script>
                            </div>
                        </div>

                        <div class="form_cont">
                            <div class="form_left"><lable>text message*</lable></div>
                            <div class="form_right"><textarea name="register[message]" rows="4" cols="20" class="text_message"><?php echo $register['message']; ?></textarea></div>
                        </div>

                        <div class="form_cont">
                            <div class="form_left">&nbsp;</div>
                            <div class="form_right"><input type="submit" value="send" name="save" class="btn-info" /></div>
                        </div>




                    </form>
                </div>
    <?php } ?>

            <!--message section-->
        <?php } else {
            ?>
            <?php
            if ($_GET['senditem']) {
                foreach ($notification as $notification_val) {
                    $id = $notification_val->id;

                    $message = $notification_val->message;
                    $sender_id = $notification_val->user_id;
                    $sender_name = get_user_meta($sender_id, 'nickname', TRUE);
                    $newDate = $notification_val->date;
                    $date = date("d-m-Y", strtotime($newDate));
                    ?>

                    <div class="message_box">
                        <div class="span9" id="notify">	
                            <div class="alert alert-success fade in">
                                <button type="button" class="close" id="<?php echo $id; ?>" onclick="return closed(this.id);" data-dismiss="alert">x</button>
                                <button type="button" class="close" id="event_<?php echo $event_name; ?>" onclick="return edit_event(this);">reply</button>
                                <div class="message_date"><b>message date : <?php echo $date; ?></b> || <b><?php
                                        if ($_GET['senditem']) {
                                            echo 'message to';
                                        } else {
                                            echo 'message from';
                                        }
                                        ?> : </b><?php echo $sender_name; ?></div>
                                <p>Message:<span><?php echo $message; ?></span></p> 
                                <div style="display:none;" class='span9_as'>
                                    <div id="reply_msg"><textarea name="reply" id="reply" rows="4" cols="20"></textarea></div>
                                    <div><input type="button" value="Reply" name="reply_button" id="<?php echo $id; ?>" onclick="return reply(this.id);"/></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--<hr>-->
                    <?php
                }
            } else {
                if (count($notification) > 0) {
                    foreach ($notification as $notification_val) {
                        $id = $notification_val->id;

                        $message = $notification_val->message;
                        $sender_id = $notification_val->sender_id;
                        $sender_name = get_user_meta($sender_id, 'nickname', TRUE);
                        $newDate = $notification_val->date;
                        $date = date("d-m-Y", strtotime($newDate));
                        ?>

                        <div class="message_box">
                            <div class="span9" id="notify">	
                                <div class="alert alert-success fade in">
                                    <button type="button" class="close" id="<?php echo $id; ?>" onclick="return closed(this.id);" data-dismiss="alert">x</button>
                                    <button type="button" class="close" id="event_<?php echo $event_name; ?>" onclick="return edit_event(this);">reply</button>
                                    <div class="message_date"><b>message date : <?php echo $date; ?></b> || <b><?php
                                            if ($_GET['senditem']) {
                                                echo 'message to';
                                            } else {
                                                echo 'message from';
                                            }
                                            ?> : </b><?php echo $sender_name; ?></div>
                                    <p>Message:<span><?php echo $message; ?></span></p> 
                                    <div style="display:none;" class='span9_as'>
                                        <div id="reply_msg"><textarea name="reply" id="reply" rows="4" cols="20"></textarea></div>
                                        <div><input type="button" value="Reply" name="reply_button" id="<?php echo $id; ?>" onclick="return reply(this.id);"/></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--<hr>-->
                        <?php
                    }
                } else { ?>
                    <div class="error_message">Sorry! Presently You Don't Have Any Message.</div>
               <?php }
            }
        }
        ?>

    </div>
    <!-- #content -->
</div>
<!-- #primary -->
<?php get_sidebar(); ?>
<?php get_footer(); ?>
