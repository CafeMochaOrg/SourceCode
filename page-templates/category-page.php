<?php
/**
 * Template Name: Category Page Template
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
function x_week_range(&$start_date, &$end_date, $date)
 {
    $ts = strtotime($date);
    $start = (date('w', $ts) == 0) ? $ts : strtotime('last sunday', $ts);
    $start_date = date('Y-m-d', $start);
    $end_date = date('Y-m-d', strtotime('next saturday', $start));
}
function x_month_range(&$start_date, &$end_date, $date)
{
	$start_date = date('01-m-Y');
	$end_date =date('d-m-Y',mktime(0,0,0,date("m"),date("t"),date("Y")));
}
get_header();
require_once(get_template_directory().'/custom_functions/sipping.php');
?>
<script class="jsbin" src="<?php echo get_bloginfo('siteurl') . '/wp-content/themes/cafemocha/js/cafemocha/sip.js'; ?>"></script>
<div id="primary" class="site-content">
    <div id="content" role="main">
        <?php if (have_posts()) : ?>
            <?php /* Start the Loop */ ?>
            <?php while (have_posts()) : the_post(); ?>
                <?php //get_template_part( 'content', get_post_format() ); ?>
            <?php endwhile; ?>
            <?php cafemocha_content_nav('nav-below'); ?>
        <?php else : ?>
            <article id="post-0" class="post no-results not-found">
                <?php
                if (current_user_can('edit_posts')) :
                    // Show a different message to a logged-in user who can add posts.
                    ?>
                    <header class="entry-header">
                        <h1 class="entry-title">
                            <?php _e('No posts to display', 'cafemocha'); ?>
                        </h1>
                    </header>
                    <div class="entry-content">
                        <p><?php printf(__('Ready to publish your first post? <a href="%s">Get started here</a>.', 'cafemocha'), admin_url('post-new.php')); ?></p>
                    </div>
                    <!-- .entry-content -->
                    <?php
                else :
                    // Show the default message to everyone else.
                    ?>
                    <header class="entry-header">
                        <h1 class="entry-title">
                            <?php _e('Nothing Found', 'cafemocha'); ?>
                        </h1>
                    </header>
                    <div class="entry-content">
                        <p>
                            <?php _e('Apologies, but no results were found. Perhaps searching will help find a related post.', 'cafemocha'); ?>
                        </p>
                        <?php get_search_form(); ?>
                    </div>
                    <!-- .entry-content -->
                <?php endif; // end current_user_can() check  ?>
            </article>
            <!-- #post-0 -->
        <?php endif; // end have_posts() check  ?>
        <!-- Post Section -->
        <!--Short By-->
        <div class="short_by">
            <ul>
                <li>Arrange Most Recent By-</li>
                <li><a href="<?php echo site_url().'/category/?moca='.$_GET['moca'].'&sectionby=d'?>">Daily</a></li>
                <li><a href="<?php echo site_url().'/category/?moca='.$_GET['moca'].'&sectionby=w'?>">Weekly</a></li>
                <li><a href="<?php echo site_url().'/category/?moca='.$_GET['moca'].'&sectionby=m'?>">Monthly</a></li>
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
        <?php //echo get_site_url()."/wp-content/themes/cafemocha/js/bxslider/jquery.bxslider.min.js";?> 
                <!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>-->

        <script src="<?php echo get_site_url(); ?>/wp-content/themes/cafemocha/js/bxslider/jquery.bxslider.min.js"></script>
        <link href="<?php echo get_site_url(); ?>/wp-content/themes/cafemocha/js/bxslider/jquery.bxslider.css" rel="stylesheet" />

        <?php
        /*if (is_user_logged_in())
		{
            global $current_user;
            get_currentuserinfo();
            @$moca = unserialize($current_user->Choosed_Mocha);
            if ($moca == false) {//(count($moca)>0)
                $moca = array();
            }*/
            $posts_array = get_posts(array('post_type' => 'publishment', 'post_status' => 'publish', 'numberposts' => 5, 'meta_query' => array(array('key' => 'mocas', 'value' => $_GET['moca'], 'compare' => 'IN'))));
		/*}
		else
		{
			$posts_array = get_posts(array('post_type' => 'publishment', 'post_status' => 'publish', 'numberposts' => 5));
        }*/
        
        ?>
        <ul class="bxslider">
        <?php
        foreach ($posts_array as $r) {
            $meta = get_post_meta($r->ID);
            ?>
                <li>
                    <div class="top_rating_war">
                        <div class="top_rating_cont">
						
                            <!--Categori Image-->
                            <div class="top_post_pic_cont"><?php $userdata = get_userdata($r->post_author); ?>
                                <div class="top_cat_pic"><img src="<?php if(z_taxonomy_image_url($meta[mocas][0])!=false) echo z_taxonomy_image_url($meta[mocas][0]); else echo get_site_url()."/wp-content/themes/cafemocha/custom-images/no-image.jpg"; ?>" height="48px" width="48px"></div>
                            </div>
                            <!--Categori Image-->
                            <!-- Post Description -->
                            <div class="top_post_des">
							<a href="<?php echo get_bloginfo('siteurl').'?p='.$r->ID?>"><?php echo $meta[description][0];?></a>
								<?php
								
								if ($meta[attached_file][0] != '') {
									?>
                                    <a href="<?php echo $meta[attached_file][0]; ?>">DOC</a>
                                    <?php }
                                ?>
                            </div>
                            <!-- Post Description -->
                            <!-- Sip -->
                            <div class="top_post_sip">
                                <ul>
								<?php if (is_user_logged_in()){?>
                                    <li><a href="javascript:void(0)" class="vote_up" onclick="sip_it('<?php echo $r->ID;?>')"></a>Sip it</li>
                                    <li><a href="javascript:void(0)" class="vote_down" onclick="spit_it('<?php echo $r->ID;?>')"></a>Spit it</li>
								<?php }else{?>
									<li><a href="javascript:void(0)" class="vote_up" onclick="login_msg()"></a>Sip it</li>
                                    <li><a href="javascript:void(0)" class="vote_down" onclick="login_msg()"></a>Spit it</li>
								<?php }?>
                                </ul>
                            </div>
                            <!-- Sip -->
                        </div>
                        <!--End .top_rating_cont-->
                        <div class="top_rating_bottom_cont">
                            <ul>
                                <li><a href="#"><?php echo get_term_by('id',$meta[mocas][0],'category')->name;?> </a></li>
                                <li class="author">
                                    <p>Published By</p>
                                    <a href="#"><?php echo $userdata->display_name; ?></a></li>
									<?php
									$sip_it=get_sipit($r->ID);
									$spit_it=get_spitit($r->ID);
									$sip=$sip_it-$spit_it; ?>
                                <li style="position:relative;"><a href="#" class="sip sip_up_<?php echo $r->ID;?>"><?php echo $sip; ?></a>
                                    <ul style="display:none;">
                                        <li><a href="#" class="sip_pop sip_it_up_<?php echo $r->ID;?>"><?php echo $sip_it; ?></a></li>
                                        <li><a href="#" class="spit_pop spit_it_up_<?php echo $r->ID;?>"><?php echo $spit_it; ?></a></li>
                                    </ul>
                                </li>
                                <li><a href="#" class="view">525 views</a></li>
                                <li><a href="#" class="comment">125 comments</a></li>
                            </ul>
                        </div>
                        <!--End .top_rating_bottom_cont-->
                    </div>
                </li>
                <!-- Post Section -->
    <?php
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
if(isset($_GET['sectionby']))
{
	global $wpdb;
	$query="SELECT wp_posts.post_date FROM wp_posts,wp_postmeta where ID=post_id and post_type='publishment' and meta_value=".$_GET['moca']." ORDER BY post_date";
	//echo $query;
	$rs=$wpdb->get_results($query);
	$last_date=mysql2date('d-m-Y',$rs[0]->post_date);
	$date=date('d-m-Y');
	$i=0;
	while(strtotime($last_date)<=strtotime($date)&&$i<20)
	{
		$i++;
		$query="SELECT wp_posts.ID FROM wp_posts INNER JOIN wp_postmeta ON wp_posts.ID=wp_postmeta.post_id WHERE post_type='publishment'";
		//////////////////////////////////sectionby//////////////////////////////////////////
		if(isset($_GET['sectionby']))
			{
			if($_GET['sectionby']=='d')
				$query=$query." and post_date like '%".date("Y-m-d", strtotime($date))."%'";
			elseif($_GET['sectionby']=='w')
			{
				$start_date;
				$end_date;
				x_week_range($start_date, $end_date, $date);
				$query=$query." and post_date BETWEEN '".date("Y-m-d", strtotime($start_date))."' AND '".date("Y-m-d", strtotime($end_date))."'";
			}
			elseif($_GET['sectionby']=='m')
			{
				x_month_range($start_date, $end_date, $date);
				$query=$query." and post_date BETWEEN '".date("Y-m-d", strtotime($start_date))."' AND '".date("Y-m-d", strtotime($end_date))."'";
			}
			else
				$query=$query." and post_date like '%".date("Y-m-d", strtotime($date))."%'";
		}
		else
			$query=$query." and post_date like '%".date("Y-m-d", strtotime($date))."%'";
		$query=$query."and meta_value=".$_GET['moca']." group by wp_posts.ID";
			//echo $query."<br>";
			//$last_date=$date;
	
	
			if(isset($_GET['sectionby']))
			{
				if($_GET['sectionby']=='d')
					$previousdate=date('d-m-Y', strtotime('-1 days',strtotime($date)));
				elseif($_GET['sectionby']=='w')
					$previousdate=date('d-m-Y', strtotime('-7 days',strtotime($date)));
				elseif($_GET['sectionby']=='m')
					$previousdate=date('d-m-Y', strtotime('-1 month',strtotime($date)));
				else
					$previousdate=date('d-m-Y', strtotime('-1 days',strtotime($date)));
			}
			else
				$previousdate=date('d-m-Y', strtotime('-1 days',strtotime($date)));
			
			$rs=$wpdb->get_results($query);
			//echo "<br> - ".var_dump($rs);
			if(count($rs)!=0)
			{
			?>
			<div class="posted_on">
						<?php
							if(isset($_GET['sectionby']))
							{
								if($_GET['sectionby']=='d')
									echo "Posted on - ".$date;
								elseif($_GET['sectionby']=='w')
									echo "Posted on - ".$start_date." to ".$end_date;
								elseif($_GET['sectionby']=='m')
									echo "Posted on - ".date('M-Y', strtotime($date));//." to ".$end_date;
								else
									echo "Posted on - ".$date;
							}
							else
								echo "Posted on - ".$date;
						
						?>
			</div>
			<?php
			}
			$date=$previousdate;
			
			
			
			foreach($rs as $r)
			{
				?>
				<div class="normal_post_war">
					<div class="top_rating_cont">
						<!--Categori Image-->
						<div class="top_post_pic_cont"><?php $userdata = get_userdata($r->post_author); ?>
							<div class="top_cat_pic"><img src="<?php if(z_taxonomy_image_url($meta[mocas][0])!=false) echo z_taxonomy_image_url($meta[mocas][0]); else echo get_site_url()."/wp-content/themes/cafemocha/custom-images/no-image.jpg"; ?>" height="48px" width="48px"></div>
						</div>
						<!--Categori Image-->
						<!-- Post Description -->
						<div class="top_post_des">
				<?php
				echo $meta[description][0];
				if ($meta[attached_file][0] != '') {
					?>
								<a href="<?php echo $meta[attached_file][0]; ?>">DOC</a>
			<?php }
		?>
						</div>
						<!-- Post Description -->
						<!-- Sip -->
						<div class="top_post_sip">
							<ul>
							<?php if (is_user_logged_in()){?>
								<li><a href="javascript:void(0)" class="vote_up" onclick="sip_it('<?php echo $r->ID;?>')"></a>Sip it</li>
								<li><a href="javascript:void(0)" class="vote_down" onclick="spit_it('<?php echo $r->ID;?>')"></a>Spit it</li>
							<?php }else{?>
								<li><a href="javascript:void(0)" class="vote_up" onclick="login_msg()"></a>Sip it</li>
								<li><a href="javascript:void(0)" class="vote_down" onclick="login_msg()"></a>Spit it</li>
							<?php }?>
							</ul>
						</div>
						<!-- Sip -->
					</div>
					<!--End .top_rating_cont-->
					<div class="normal_rating_bottom_cont">
						<ul>
							<li><a href="#"><?php echo get_term_by('id',$meta[mocas][0],'category')->name;?> </a></li>
							<li class="author">
								<p>Published By</p>
								<a href="#"><?php echo $userdata->display_name; ?></a></li>
								<?php
										$sip_it=get_sipit($r->ID);
										$spit_it=get_spitit($r->ID);
										$sip=$sip_it-$spit_it; ?>
							<li style="position:relative;"><a href="#" class="sip sip_up_<?php echo $r->ID;?>"><?php echo $sip; ?></a>
								<ul style="display:none;">
								
									<li><a href="#" class="sip_pop sip_it_up_<?php echo $r->ID;?>"><?php echo intval($sip_it); ?></a></li>
									<li><a href="#" class="spit_pop spit_it_up_<?php echo $r->ID;?>"><?php echo intval($spit_it); ?></a></li>
								</ul>
							</li>
							<li><a href="#" class="view">525 views</a></li>
							<li><a href="#" class="comment">125 comments</a></li>
						</ul>
					</div>
					<!--End .top_rating_bottom_cont-->
				</div>
				<?php
				//echo $r->ID."<br>";
			}
	}
}
else
{
	if (is_user_logged_in()) {
    global $current_user;
    get_currentuserinfo();
    @$moca = unserialize($current_user->Choosed_Mocha);
    if ($moca == false) {
        $moca = array();
    }
	$args=array(
		'post_type' => 'publishment',
		'post_status' => 'publish',
		'numberposts' => -1,
		'meta_query' => array(array('key' => 'mocas', 'value' => $moca, 'compare' => 'IN'))
	);
	$posts_array = get_posts($args);
	//echo count($posts_array);
	

    ///$posts_array = get_posts($args);
} else {
    $args = array(
        'offset' => 0,
        'category' => '',
        'orderby' => 'post_date',
        'order' => 'DESC',
        'include' => '',
        'exclude' => '',
        'meta_key' => '',
        'meta_value' => '',
        'post_type' => 'publishment',
        'post_mime_type' => '',
        'post_parent' => '',
        'post_status' => 'publish',
        'suppress_filters' => true);

    $posts_array = get_posts($args);
}
foreach ($posts_array as $r) {
    $meta = get_post_meta($r->ID);
    /* echo"<pre>";
      echo"<br><br><br><br><br><br><br><br>";
      print_r($attached_file);
      echo"<pre>"; */

    //echo $attached_file[attached_file][0]."<br>";
    //echo $attached_file[mocas][0]."<br>";
    //echo $attached_file[description][0]."<br>";
    //echo $attached_file[story][0]."<br>"."<br>";
    ?>
            <div class="normal_post_war">
                <div class="top_rating_cont">
                    <!--Categori Image-->
                    <div class="top_post_pic_cont"><?php $userdata = get_userdata($r->post_author); ?>
                        <div class="top_cat_pic"><img src="<?php if(z_taxonomy_image_url($meta[mocas][0])!=false) echo z_taxonomy_image_url($meta[mocas][0]); else echo get_site_url()."/wp-content/themes/cafemocha/custom-images/no-image.jpg"; ?>" height="48px" width="48px"></div>
                    </div>
                    <!--Categori Image-->
                    <!-- Post Description -->
                    <div class="top_post_des">
            <?php
            echo $meta[description][0];
            if ($meta[attached_file][0] != '') {
                ?>
                            <a href="<?php echo $meta[attached_file][0]; ?>">DOC</a>
        <?php }
    ?>
                    </div>
                    <!-- Post Description -->
                    <!-- Sip -->
                    <div class="top_post_sip">
                        <ul>
						<?php if (is_user_logged_in()){?>
                            <li><a href="javascript:void(0)" class="vote_up" onclick="sip_it('<?php echo $r->ID;?>')"></a>Sip it</li>
                            <li><a href="javascript:void(0)" class="vote_down" onclick="spit_it('<?php echo $r->ID;?>')"></a>Spit it</li>
						<?php }else{?>
							<li><a href="javascript:void(0)" class="vote_up" onclick="login_msg()"></a>Sip it</li>
							<li><a href="javascript:void(0)" class="vote_down" onclick="login_msg()"></a>Spit it</li>
						<?php }?>
                        </ul>
                    </div>
                    <!-- Sip -->
                </div>
                <!--End .top_rating_cont-->
                <div class="normal_rating_bottom_cont">
                    <ul>
                        <li><a href="#"><?php echo get_term_by('id',$meta[mocas][0],'category')->name;?> </a></li>
                        <li class="author">
                            <p>Published By</p>
                            <a href="#"><?php echo $userdata->display_name; ?></a></li>
							<?php
									$sip_it=get_sipit($r->ID);
									$spit_it=get_spitit($r->ID);
									$sip=$sip_it-$spit_it; ?>
                        <li style="position:relative;"><a href="#" class="sip sip_up_<?php echo $r->ID;?>"><?php echo $sip; ?></a>
                            <ul style="display:none;">
							
                                <li><a href="#" class="sip_pop sip_it_up_<?php echo $r->ID;?>"><?php echo intval($sip_it); ?></a></li>
                                <li><a href="#" class="spit_pop spit_it_up_<?php echo $r->ID;?>"><?php echo intval($spit_it); ?></a></li>
                            </ul>
                        </li>
                        <li><a href="#" class="view">525 views</a></li>
                        <li><a href="#" class="comment">125 comments</a></li>
                    </ul>
                </div>
                <!--End .top_rating_bottom_cont-->
            </div>
            <!-- Post Section -->
    <?php
}
?>
	<?
}
?>
    </div>
    <!-- #content -->
</div>
<!-- #primary -->
        <?php get_sidebar(); ?>
        <?php get_footer(); ?>
