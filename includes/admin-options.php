<h3><?php _e('Royal Mail Settings', 'wc-royal-mail');?></h3>
	<?php $this->check_store_requirements(); ?>
	<?php if ($this->debug_mode == 'yes'): ?>
		<div class="updated woocommerce-message">
	    	<p><?php _e('Royal Mail debug mode is activated, only administrators can use it.', 'wc-royal-mail');?></p>
	    </div>
	<?php endif;?>

<div id="poststuff">
	<div id="post-body" class="metabox-holder columns-2">
		<div id="post-body-content">
			<table class="form-table">
				<?php $this->generate_settings_html();?>
			</table><!--/.form-table-->
		</div>
    </div>
</div>
<div class="clear"></div>
