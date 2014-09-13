<?php
/**
 * The template for displaying the footer.
 *
 * Contains footer content and the closing of the
 * #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
?>
	</div><!-- #main .wrapper -->
	<footer id="colophon" role="contentinfo">
		<div class="site-info">
            <div class="footer_menu_cont">
        		<?php dynamic_sidebar( 'Footer Menu Widget Area' ); ?>
        	</div>
            
            <div class="copy_right">
            	<?php dynamic_sidebar( 'Footer Copyright Widget Area' ); ?>
            </div>
		</div><!-- .site-info -->
        
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>
</body>
</html>