<?php

if (!defined("ABSPATH")) {
	exit;
}

$url = "?page=" . urlencode(stripslashes($_REQUEST["page"]))
	. "&action=install";
$continue_url = "?page=" . urlencode(stripslashes($_REQUEST["page"]))
	. "&action=continue";

?>

	
	<div id="bbpp-thankmelater-install">
	
		<h2 class="bbpp-thankmelater-install-message">
			<?php
				echo __("Install Thank Me Later 3.3", "bbpp-thankmelater");
			?>
		</h2>

		<h3><?php echo __("Email open tracking", "bbpp-thankmelater"); ?></h3>
		
		<p><?php echo __("This version of Thank Me Later introduces email open tracking.", "bbpp-thankmelater"); ?></p>
		
		<ul>
			<li><?php echo __("View number of opened Thank Me Later emails from the 'Stats' page.", "bbpp-thankmelater"); ?></li>
			<li><?php echo __("You can enable and disable tracking from each message's options page.", "bbpp-thankmelater"); ?></li>
			<li><?php echo __("You should notify commenters that your emails may contain tracking code.", "bbpp-thankmelater"); ?></li>
		</ul>
		
		<p><?php echo __("Please decide whether you would like to enable email tracking.", "bbpp-thankmelater"); ?></p>
		
		
		<form id="bbpp_thankmelater_install" action="<?php echo esc_attr($url); ?>" method="post">
		
		<p><label><input 
				type="checkbox" 
				name="bbpp_thankmelater_share_data" 
				value="1"<?php if ($share_data) { echo " checked=\"checked\""; } ?>>
		
			<?php echo __("Share usage data with the plugin developer*", "bbpp-thankmelater"); ?>
			</label></p>
		
			<?php wp_nonce_field("bbpp_thankmelater_install"); ?>
			<?php submit_button(__("Enable Email Open Tracking", "bbpp-thankmelater"), "primary", "submit", false); ?>
			<a class="button" href="<?php echo esc_attr($continue_url); ?>">Do not enable</a> 
		</form>
	
	</div>

<p><?php echo __("* The following information will be shared: "
		. "your domain name, the number of emails sent and the number of emails opened. "
		. "User details, such as email addresses, are never included. "
		. "This information will help me plan for future features.", "bbpp-thankmelater"); ?></p>