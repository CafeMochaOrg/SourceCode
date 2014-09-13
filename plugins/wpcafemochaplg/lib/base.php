<?php
	
if ( ! class_exists( 'WPCafemochaPLG_Base' ) ) :
	class WPCafemochaPLG_Base {
	
		/**
		* Make sure jQuery and the jQuery Form plugin are available
		*
		* @param	none
		* @return	none
		*/
		public function load_jquery() {
			wp_enqueue_script('jquery');
			wp_enqueue_script('jquery-form');
		}
		
		/**
		* Unload Wordpress jQuery and load custom jQuery
		* call it in __constuctor or init() link- add_action( 'init', array( __CLASS__, 'load_custom_jquery_lib' ) );
		* @param	none
		* @return	none
		*/
		public function load_custom_jquery_lib()
		{
			wp_deregister_script( 'jquery' );  
			wp_register_script('jquery', 'http://code.jquery.com/jquery-1.7.1.js');  
			wp_enqueue_script('jquery');  
		}
		
		
		/**
		* Make sure to define ajaxurl, need outside the admin area.
		*
		* Again this is just for our ajax caller to be able to use 'ajaxurl'.
		*
		* @param	none
		* @return	none
		*/
		public function define_ajaxurl() {
			?>
			<script type="text/javascript">
				var ajaxurl = '<?php echo admin_url( 'admin-ajax.php' ); ?>'
			</script>
			<?php
		}
		
		/**
		* Delete Directory and its files.
		*
		*
		* @param	$directory Directory path 
		* @return	none
		*/
		public function remove_dir($directory)
		{
			$files = glob( $directory.'{,.}*', GLOB_BRACE);
			foreach($files as $file){ // iterate files
			  if(is_file($file))
				unlink($file); // delete file
			}
			@rmdir( $directory );
		}
		
		/**
		* Ajax Handler for ajax operations.
		*
		* Before call this function call load_jquery() and  define_ajaxurl() on wp_head hook
		* Call this on admin_head hook
		*
		* @param	$directory Directory path 
		* @return	none
		*/		
		public function ajax_handler() {
			
			$ajax_nonce = wp_create_nonce('wpcafemochaplg');
			?>

			<script type="text/javascript">
				var $my_jQuery = jQuery.noConflict();
				
				$my_jQuery(document).ready( function( $ ) {
				$my_jQuery(document).on('click', '.ajax-trigger', function ($) {

					/**
					 * Get the action from the value of 'name=' in the submit input
					 * that has the class 'action'.
					 */
					var $action = $my_jQuery(this).find('input.ajax-action').attr('name'); 

					/**
					 * Derive the form id from the action.
					 */
					var $form = '#'+$action+'-form';
					
					/**
					 * Serialize the form data to then extract from $_POST with parse_str() 
					 */
					var $data = $my_jQuery($form).serialize();
					//alert($data);
					/**
					 * We can go ahead and reset the form fields.
					 */
					//$my_jQuery($form).reset();
					$my_jQuery($form).each (function(){
					  this.reset();
					});
					
					/**
					 * 'ajaxurl' is defined by default in WordPress,and points to 
					 * admin-ajax.php. However, below you'll se we need to define it
					 * in the Widget, because it only gets defined in the admin pages.
					 */
					$my_jQuery.post(ajaxurl, {
					
						/**
						 * Send our key value pairs as a POST to admin-ajax.php.
						 * It will handle calling the functions we defined above for
						 * the given action.
						 * The security value will be derived and matched in the
						 * callback function.
						 * 'data' is our form data in serialized format.
						 */
						action: $action,
						security: '<?php echo $ajax_nonce; ?>',
						data: $data
						}, function( response ) {
						
							//alert(response);
							/**
							 * Unserialize the response into an object.
							 */
							var $json_response = $my_jQuery.parseJSON( response );

							/**
							 * In here you have lots of options for what you want to do.
							 * I'm just testing for a 'print_output' var and if it is 'yes',
							 * Then I determine if it goes into an alert or an id tag somewhere.
							 */
							alert( $json_response.data );
							
						} 
					 );
					 

				} );
			} );
			</script>
			<?php
		}
		
		/**
		* Load  custom js script
		*
		*  @param	none
		* @return	none
		*/		
		public function load_js()
		{
			wp_register_script( 'script_holder', WPCAFEMOCHAPLG_PLUGIN_URI . 'js/script.js', array(), '2013', false);
			wp_enqueue_script( 'script_holder' );
			
		}
		
		/**
		* Load any custom css file
		* ex- all, screen, print
		* 	
		* @param	none
		* @return	none
		*/		
		public function load_css()
		{		
			wp_register_style( 'css_holder', WPCAFEMOCHAPLG_PLUGIN_URI . 'css/style.css', array(), '2013', 'all');
			wp_enqueue_style( 'css_holder' );
		}
		
		/**
		* Generic function to show a message to the user using WP's 
		* standard CSS classes to make use of the already-defined
		* message colour scheme.
		*
		* call like this parent::showMessage("Hello admins!", true);
		*
		* @param $message The message you want to tell the user.
		* @param $errormsg If true, the message is an error, so use 
		* the red message style. If false, the message is a status 
		* message, so use the yellow information message style.
		*/
		public function showMessage($message, $msgtype = 'update')
		{
			if ('error' == $msgtype) {
				echo '<div id="message" class="error">';
			}
			else if('update' == $msgtype){
				echo '<div id="message" class="updated fade">';
			}else if('success' == $msgtype)
			{
				echo '<div id="message" class="success fade">';
			}
			
			echo "<p><strong>$message</strong></p></div>";
		}    		
		
		function abc()
		{
			echo 'hi';	
		}
	
	}
endif;
?>