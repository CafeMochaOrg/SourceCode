<?php

if (!defined("ABSPATH")) {
	exit;
}

?>

	
	<div id="bbpp-thankmelater-install">
	
		<h2 class="bbpp-thankmelater-install-message">
			<?php
				echo __("Thank Me Later is now ready to use. Enjoy!", "bbpp-thankmelater");
			?>
		</h2>

		<h3><?php echo __("Thank <em>you</em>", "bbpp-thankmelater"); ?></h3>
		
		<p><?php echo __("When I released Thank Me Later 6 years ago, I never expected it to become as popular as it is today. In that time, I have seen the plugin downloaded over 130,000 times and used by perhaps tens of thousands of bloggers across the world. It is now available in 6 other languages thanks to translators contributing their time and effort to the plugin.", "bbpp-thankmelater"); ?></p>
		
		<p><?php echo __("Seeing the plugin used so widely gives me great satisfaction. Feedback has been overwhelmingly positive, and this has driven me to continue maintaining the software and to add new features.", "bbpp-thankmelater"); ?></p>
		
		<p><?php echo __("However, managing to maintain the plugin and provide support to users is not easy. It takes an incredible amount of my personal time, for which I am not remunerated.", "bbpp-thankmelater"); ?></p>
		
		<p><?php echo __("Thank Me Later is free and open source, but I do accept donations from those who have a few pounds or dollars to spare. A small donation will motivate me to continue working on this project. If you have been a long time user of Thank Me Later, I hope you will consider making a small donation.", "bbpp-thankmelater"); ?></p>
		
		<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
			<input type="hidden" name="cmd" value="_xclick">
			<input type="hidden" name="business" value="VJARSRXSGKHTN">
			<input type="hidden" name="lc" value="GB">
			<input type="hidden" name="item_name" value="Donation for Thank Me Later">
			<!--<input type="hidden" name="amount" value="12.00">-->
			<label><?php echo __("Amount:", "bbpp-thankmelater"); ?> <input type="text" name="amount" value="10.00" style="width: 100px;"></label>
			<!--<input type="hidden" name="currency_code" value="GBP">-->
			<select name="currency_code">
				<option value="AUD"><?php echo __("AUD", "bbpp-thankmelater"); ?></option>
				<option value="CAD"><?php echo __("CAD", "bbpp-thankmelater"); ?></option>
				<option value="EUR"><?php echo __("EUR", "bbpp-thankmelater"); ?></option>
				<option value="GBP"><?php echo __("GBP", "bbpp-thankmelater"); ?></option>
				<option value="USD" selected="selected"><?php echo __("USD", "bbpp-thankmelater"); ?></option>
			</select>
			<input type="hidden" name="button_subtype" value="services">
			<input type="hidden" name="no_note" value="1">
			<input type="hidden" name="no_shipping" value="1">
			<input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHosted">
			<?php submit_button(__("PayPal", "bbpp-thankmelater"), "primary", "submit", false); ?>
		</form>

		<p><?php echo __("I also accept Bitcoin donations:", "bbpp-thankmelater"); ?> <code>1M83EZTERfwsnSbmwD1wYugjRCg9GPbLez</code></p>
		
		<p><?php echo sprintf(__("If you cannot donate, please consider sharing your feedback by %swriting a review at Wordpress.org%s. And of course, please report any bugs or problems might you come across.", "bbpp-thankmelater"), "<a href=\"http://wordpress.org/support/view/plugin-reviews/thank-me-later\">", "</a>"); ?></p>
		
		<p><?php echo __("And finally, I would like to reiterate my thanks&mdash;to the translators, the bloggers who have shared the plugin with their audiences and, most importantly, <em>you</em>.", "bbpp-thankmelater"); ?></p>
		
		<p><?php echo sprintf(__("Thanks,<br />%sBrendon%s.", "bbpp-thankmelater"), "<a href=\"https://twitter.com/brendonboshell\">", "</a>"); ?></p>
	
	</div>