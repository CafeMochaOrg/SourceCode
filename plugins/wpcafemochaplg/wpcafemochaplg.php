<?php
/*
Plugin Name: Cafemocha Plugin
Plugin URI: http://wordpress.org/extend/plugins/
Description: Cafemocha Plugin.
Author: GMW
Version: 1.0
Requires at least: 3.0
Author URI: http://www.gmwinfotech.com
Contributors:  GMW
License: GPL2
Text Domain: wpCafemochaPLG
Domain Path: /languages/
*/ 

define('WPCAFEMOCHAPLG', 'wpcafemochaplg');

define('WPCAFEMOCHAPLG_PLUGIN_DIR', str_replace('\\','/',dirname(__FILE__)).'/');
if(!defined('ABSPATH')) {
    define('ABSPATH', dirname(dirname(dirname(dirname(__FILE__)))));
} 

//define('STUDIFILES_PLUGIN_URI', plugins_url('/',__FILE__));
$wpcafemochaplg_uri = str_replace(str_replace('\\','/',ABSPATH),get_option('siteurl').'/',WPCAFEMOCHAPLG_PLUGIN_DIR);
$wpcafemochaplg_uri = is_ssl() ? str_replace('http://', 'https://', $wpcafemochaplg_uri) : str_replace('https://', 'http://', $wpcafemochaplg_uri);
define('WPCAFEMOCHAPLG_PLUGIN_URI', $wpcafemochaplg_uri);
unset($wpcafemochaplg_uri);


define('WPCAFEMOCHAPLG_PLUGIN_DB', 'cafemocha');

//if plugin need any upload directory
$wpcafemochaplg_file_dir = ABSPATH.'\wp-content\uploads\wpcafemochaplg-files\\';
$wpcafemochaplg_file_dir = str_replace("/","", $wpcafemochaplg_file_dir);
define( 'WPCAFEMOCHAPLG_UPLOAD_DIR', $wpcafemochaplg_file_dir );
unset($wpcafemochaplg_file_dir); 


require('lib/base.php');


if ( ! class_exists( 'WPCafemochaPLG' ) ) :
	class WPCafemochaPLG  extends WPCafemochaPLG_Base{    

		public function __construct()
		{
			/*  Loading plugins default css and js files */
			add_action( 'init', array( __CLASS__, 'load_css' ) );
			add_action( 'init', array( __CLASS__, 'load_js' ) );
		}
		
		public function wpcafemochaplg_init()
		{
			
			register_activation_hook(__FILE__, array( __CLASS__, 'wpcafemochaplg_activate') );
			register_uninstall_hook(__FILE__, array( __CLASS__, 'wpcafemochaplg_uninstall' ) );
					
			add_action( 'admin_menu', array( __CLASS__, 'wpcafemochaplg_menu' ) );
			
			/*  Ajax Operation */
			add_action( 'wp_head', array( __CLASS__, 'load_jquery' ) );
			add_action( 'wp_head', array( __CLASS__, 'ajax_handler' ) );
			add_action( 'admin_head', array( __CLASS__, 'ajax_handler' ) );
			add_action( 'wp_ajax_ajax_test', array( __CLASS__, 'handle_ajax_call' ) );
			/*  Ajax Operation */
		}
		
		
		public function wpcafemochaplg_activate()
		{
			global $wpdb;
			// setup table names
			$wpcafemochaplg_db = $wpdb->prefix.WPCAFEMOCHAPLG_PLUGIN_DB;
			
			// table for files
			$sql = "CREATE TABLE IF NOT EXISTS {$wpcafemochaplg_db} (
						`id` int(11) NOT NULL AUTO_INCREMENT,
						`category_id` int(50) NOT NULL,
						`file_name` varchar(100) NOT NULL,
						PRIMARY KEY (`id`)
					) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;";
					
			$wpdb->query($sql);
			
			//creating upload folder		
			$isFolder = file_exists ( WPCAFEMOCHAPLG_UPLOAD_DIR ); 		
			if (!$isFolder) {
				mkdir (  WPCAFEMOCHAPLG_UPLOAD_DIR, 0777 , true );
				chmod( WPCAFEMOCHAPLG_UPLOAD_DIR, 0777 );
			}
		}
		
		public function wpcafemochaplg_uninstall()
		{
			global $wpdb;
			// setup table names
			$wpcafemochaplg_db = $wpdb->prefix.WPCAFEMOCHAPLG_PLUGIN_DB;				
			$sql_db = "DROP TABLE {$wpcafemochaplg_db}";				
			$wpdb->query($sql_db);
		}		
				

		
		public function wpcafemochaplg_menu() {

			//Add plugins link in seperate menu
			add_menu_page( 'Cafemocha', 'Cafemocha', 'manage_options', __FILE__,  array( __CLASS__, 'wpcafemochaplg_cafemocha' ) , WPCAFEMOCHAPLG_PLUGIN_URI.'images/icon.png' );
						
		}
		
		public function wpcafemochaplg_cafemocha()
		{
			echo parent::showMessage('hiiii','error');
			?>
            <div class="wrap <?php echo WPCAFEMOCHAPLG; ?>">
   			 <div class="icon32 icon32-posts-post" id="icon-list"><br></div>
   			 <h2><?php echo __('Cafemocha Settings'); ?> 
             	<a class="add-new-h2" href="<?php echo 'admin.php?page=wpcafemochaplg/wpcafemochaplg.php_listmenu'; ?>"><?php echo __('View List'); ?> </a>
             </h2>
            	
            <div class="postbox" style="width:90%; height:440px; margin-top:20px;">
            	<h3 style="text-align:center;"><?php echo __('Cafemocha Settings');  ?></h3>
            	<div id="myElement"></div>
            </div>       
            
            </div>
            <?php
			
		}
		

	}
	
endif;	

$cafemocha_instance = new WPCafemochaPLG();
$cafemocha_instance->wpcafemochaplg_init();
?>