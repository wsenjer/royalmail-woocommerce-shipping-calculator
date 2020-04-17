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
					<?php
					if(version_compare(WC()->version, '2.6.0', 'lt')){
						$this->generate_settings_html();
					}else{
						echo $this->get_admin_options_html();
					}
					?>
				</table><!--/.form-table-->
			</div>
			<div id="postbox-container-1" class="postbox-container">
                    <div id="side-sortables" class="meta-box-sortables ui-sortable"> 
                        <div class="postbox ">
                                <div class="handlediv" title="Click to toggle"><br></div>
                                <h3 class="hndle"><span><i class="dashicons dashicons-update"></i>&nbsp;&nbsp;Upgrade to Pro</span></h3>
                                <div class="inside">
                                    <div class="support-widget">
                                        <ul>
                                            <li>» UK Parcelforce</li>
                                            <li>» UK Parcelforce Worldwide</li>
                                            <li>» International Standard</li>
                                            <li>» International Economy</li>
                                            <li>» International Tracked</li>
                                            <li>» International Signed</li>
                                            <li>» International Tracked & Signed</li>
                                            <li>» Letters Shipping</li>
                                            <li>» Customizable Domestic Shipping</li>
                                            <li>» Handling Fees and Discounts</li>
                                            <li>» Display the Cheapest option</li>
                                            <li>» Dropshipping Support</li>
                                            <li>» Auto Hassle-Free Updates</li>
                                            <li>» High Priority Customer Support</li>
                                        </ul>
										<a href="https://wpruby.com/plugin/woocommerce-royal-mail-shipping-calculator-pro/" class="button wpruby_button" target="_blank"><span class="dashicons dashicons-star-filled"></span> Upgrade Now</a> 
                                    </div>
                                </div>
	                        </div>
                        <div class="postbox ">
                            <div class="handlediv" title="Click to toggle"><br></div>
                            <h3 class="hndle"><span><i class="fa fa-question-circle"></i>&nbsp;&nbsp;Plugin Support</span></h3>
                            <div class="inside">
                                <div class="support-widget">
                                    <p>
                                    <img style="width:100%;" src="<?php echo plugin_dir_url(__FILE__).'assets/images/wpruby_logo.png' ?>">
                                    <br/>
                                    Got a Question, Idea, Problem or Praise?</p>
                                    <ul>
                                        <li>» <a href="https://wpruby.com/submit-ticket/" target="_blank">Support Request</a></li>
                                        <li>» <a href="https://wpruby.com/knowledgebase_category/woocommerce-royal-mail-shipping-calculator-pro/" target="_blank">Documentation and Common issues</a></li>
                                        <li>» <a href="https://wpruby.com/plugins/" target="_blank">Our Plugins Shop</a></li>
                                    </ul>

                                </div>
                            </div>
                        </div>

                        <div class="postbox rss-postbox">
							<div class="handlediv" title="Click to toggle"><br></div>
								<h3 class="hndle"><span><i class="fa fa-wordpress"></i>&nbsp;&nbsp;WPRuby Blog</span></h3>
								<div class="inside">
									<div class="rss-widget">
										<?php
											wp_widget_rss_output(array(
												'url' => 'https://wpruby.com/feed/',
												'title' => 'WPRuby Blog',
												'items' => 3,
												'show_summary' => 0,
												'show_author' => 0,
												'show_date' => 1,
											));
										?>
									</div>
								</div>
						</div>

                    </div>
                </div>
            </div>
		</div>
		<div class="clear"></div>
<style type="text/css">
	.wpruby_button{
		background-color:#4CAF50 !important;
		border-color:#4CAF50 !important;
		color:#ffffff !important;
		width:100%;
		padding:5px !important;
		text-align:center;
		height:35px !important;
		font-size:12pt !important;
        line-height: 24px !important;
	}
    #wc_royalmail_default_size label{
        margin-top: 11px !important;
    }
</style>

