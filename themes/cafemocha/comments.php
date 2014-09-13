<?php
/**
 * The template for displaying Comments.
 *
 * The area of the page that contains both current comments
 * and the comment form. The actual display of comments is
 * handled by a callback to cafemocha_comment() which is
 * located in the functions.php file.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (post_password_required())
    return;
?>
<?php
$post_id = get_the_ID();
?>
<div id="comments" class="comments-area">

    <?php // You can start editing here -- including this comment!  ?>
    <?php comment_form(); ?>
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
    <!--                <div class="comment_img"><img src="<?php //echo $user_img;  ?>" height="50" width="50"></div>-->
                <div class="comment_contents">
                    <h2>Comment by <span><a href="<?php echo get_bloginfo('url') . '/profile-page-template/?user_id=' . $user_id; ?>"><?php echo $user_name; ?></a></span></h2>
                    <h2><?php echo $comment_date; ?></h2>
                    <p><?php echo $comment_content; ?></p>
                </div>
            </div>
        <?php } ?>
    </div>

</div><!-- #comments .comments-area -->