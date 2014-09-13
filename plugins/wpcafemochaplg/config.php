<?php

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


define('WPCAFEMOCHAPLG_PLUGIN_DB', 'wpcafemochaplgdb');

//if plugin need any upload directory
$wpcafemochaplg_file_dir = ABSPATH.'\wp-content\uploads\wpcafemochaplg-files\\';
$wpcafemochaplg_file_dir = str_replace("/","", $wpcafemochaplg_file_dir);
define( 'WPCAFEMOCHAPLG_UPLOAD_DIR', $wpcafemochaplg_file_dir );
unset($wpcafemochaplg_file_dir); 