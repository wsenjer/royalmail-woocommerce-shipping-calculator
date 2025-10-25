<?php
// Check if the notice should be shown (not dismissed within the last 2 weeks)
$current_user_id = get_current_user_id();
$dismissed_time = get_user_meta($current_user_id, '3rulehook_royalmail_promo_dismissed', true);
$show_rulehook_notice = empty($dismissed_time) || (time() - intval($dismissed_time) > 14 * DAY_IN_SECONDS);
?>

<h3><?php _e('Royal Mail Settings', 'wc-royal-mail');?></h3>
<?php $this->check_store_requirements(); ?>
<?php if ($this->debug_mode == 'yes'): ?>
    <div class="updated woocommerce-message">
        <p><?php _e('Royal Mail debug mode is activated, only administrators can use it.', 'wc-royal-mail');?></p>
    </div>
<?php endif;?>

<?php
// Check if the dismissible notice should be displayed
if ($show_rulehook_notice) :
    ?>
    <div id="rulehook-promo-notice" class="notice notice-info is-dismissible">
        <p><strong>Meet RuleHook</strong> — create flexible shipping rules in Shopify and WooCommerce by weight, postcode, cart total, and product tags. No code required.</p>
        <p><a href="https://rulehook.com/woocommerce?utm_source=royal-mail-plugin&utm_medium=plugin-notice&utm_campaign=cross-promotion" class="button button-primary" target="_blank">Learn More →</a></p>
    </div>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            // Handle the dismiss button click for RuleHook promo
            $(document).on('click', '#rulehook-promo-notice .notice-dismiss', function() {
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'dismiss_royalmail_rulehook_notice',
                        security: '<?php echo wp_create_nonce('rulehook_royalmail_dismiss_nonce'); ?>'
                    },
                    success: function(response) {
                        $('#rulehook-promo-notice').fadeOut(300, function() { $(this).remove(); });
                    }
                });
            });
        });
    </script>
<?php endif; ?>

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
                <!-- New RuleHook Widget -->
                <div class="postbox royal-mail-rulehook">
                    <h3 class="hndle"><span><i class="dashicons dashicons-filter"></i>&nbsp;&nbsp;Discover RuleHook</span></h3>
                    <div class="inside">
                        <div class="rulehook-widget">
                            <div class="rulehook-logo">
                                <img style="width: 25%" alt="RuleHook" src="https://cdn.wpruby.com/wp-content/uploads/2025/10/25143333/rulehook.png">
                            </div>
                            <h4>Advanced Shipping Rules Made Simple</h4>
                            <p>Create flexible shipping rules based on:</p>
                            <ul class="rulehook-features">
                                <li><i class="dashicons dashicons-yes"></i> Product weight</li>
                                <li><i class="dashicons dashicons-yes"></i> Customer location</li>
                                <li><i class="dashicons dashicons-yes"></i> Cart total value</li>
                                <li><i class="dashicons dashicons-yes"></i> Product tags & categories</li>
                            </ul>
                            <p><strong>No code required!</strong> Perfect for store owners who want precise control over shipping options.</p>
                            <a href="https://rulehook.com/woocommerce?utm_source=royal-mail-plugin&utm_medium=plugin-widget&utm_campaign=cross-promotion" class="button rulehook-button" target="_blank">
                                <span class="dashicons dashicons-external"></span> Learn More
                            </a>
                        </div>
                    </div>
                </div>

                <div class="postbox royal-mail-pro">
                    <h3 class="hndle"><span><i class="dashicons dashicons-star-filled"></i>&nbsp;&nbsp;Upgrade to Pro</span></h3>
                    <div class="inside">
                        <div class="support-widget">
                            <ul class="royal-mail-features">
                                <li><span class="feature-tag new">New</span> Evri (Hermes UK)</li>
                                <li><span class="feature-tag new">New</span> DPD UK</li>
                                <li>UK Parcelforce</li>
                                <li>UK Parcelforce Worldwide</li>
                                <li>International Standard</li>
                                <li>International Economy</li>
                                <li>International Tracked</li>
                                <li>International Signed</li>
                                <li>International Tracked & Signed</li>
                                <li>Letters Shipping</li>
                                <li>Customizable Domestic Shipping</li>
                                <li>Handling Fees and Discounts</li>
                                <li>Display the Cheapest option</li>
                                <li>Dropshipping Support</li>
                                <li>Auto Hassle-Free Updates</li>
                                <li>High Priority Customer Support</li>
                            </ul>
                            <a href="https://wpruby.com/plugin/woocommerce-royal-mail-shipping-calculator-pro/?utm_source=royalmail-lite&utm_medium=widget&utm_campaign=freetopro" class="button wpruby_button" target="_blank"><span class="dashicons dashicons-star-filled"></span> Upgrade Now</a>
                        </div>
                    </div>
                </div>
                <div class="postbox royal-mail-support">
                    <h3 class="hndle"><span><i class="dashicons dashicons-editor-help"></i>&nbsp;&nbsp;Plugin Support</span></h3>
                    <div class="inside">
                        <div class="support-widget">
                            <div class="logo-container">
                                <img alt="WPRuby" src="https://cdn.wpruby.com/wp-content/uploads/2024/12/01122141/wpruby-logo-wide-1.png">
                            </div>
                            <p>Got a Question, Idea, Problem or Praise?</p>
                            <ul class="support-links">
                                <li><a href="https://wpruby.com/submit-ticket/" target="_blank"><i class="dashicons dashicons-sos"></i> Support Request</a></li>
                                <li><a href="https://wpruby.com/knowledgebase_category/woocommerce-royal-mail-shipping-calculator-pro/" target="_blank"><i class="dashicons dashicons-book"></i> Documentation</a></li>
                                <li><a href="https://wpruby.com/plugins/" target="_blank"><i class="dashicons dashicons-cart"></i> Our Plugins Shop</a></li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="postbox rss-postbox royal-mail-blog">
                    <h3 class="hndle"><span><i class="dashicons dashicons-wordpress"></i>&nbsp;&nbsp;WPRuby Blog</span></h3>
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
    /* Main styles */
    #poststuff .postbox {
        border-radius: 4px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.1);
        margin-bottom: 20px;
    }

    /* Headers */
    #poststuff .postbox .hndle {
        padding: 12px 15px;
        border-bottom: 1px solid #e5e5e5;
        background: #f9f9f9;
        border-radius: 4px 4px 0 0;
    }

    /* Inside content */
    #poststuff .postbox .inside {
        padding: 15px;
    }

    /* Remove horizontal rules */
    #poststuff .postbox hr {
        display: none;
    }

    /* Pro features box */
    .royal-mail-pro {
        border-left: 3px solid #4CAF50;
    }

    /* Pro button */
    .wpruby_button {
        background-color: #4CAF50 !important;
        border-color: #43A047 !important;
        color: #ffffff !important;
        width: 100%;
        padding: 10px !important;
        text-align: center;
        height: auto !important;
        font-size: 14px !important;
        line-height: 1.5 !important;
        border-radius: 4px !important;
        transition: background-color 0.2s ease;
        font-weight: 600;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .wpruby_button:hover {
        background-color: #388E3C !important;
        box-shadow: 0 3px 6px rgba(0,0,0,0.15);
    }

    /* Features list */
    .royal-mail-features {
        margin: 0 0 15px 0;
        padding: 0;
        list-style: none;
    }

    .royal-mail-features li {
        padding: 8px 0;
        margin: 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .royal-mail-features li:last-child {
        border-bottom: none;
    }

    .feature-tag {
        display: inline-block;
        padding: 2px 6px;
        border-radius: 3px;
        font-size: 11px;
        font-weight: bold;
        margin-right: 5px;
    }

    .feature-tag.new {
        background-color: #ff5252;
        color: white;
    }

    /* Support box */
    .royal-mail-support {
        border-left: 3px solid #2196F3;
    }

    .logo-container {
        text-align: center;
        margin-bottom: 15px;
    }

    .logo-container img {
        max-width: 80%;
        height: auto;
    }

    .support-links {
        margin: 10px 0 0 0;
        padding: 0;
        list-style: none;
    }

    .support-links li {
        margin-bottom: 10px;
    }

    .support-links a {
        display: block;
        padding: 8px 10px;
        background: #f5f5f5;
        border-radius: 3px;
        text-decoration: none;
        transition: background 0.2s ease;
    }

    .support-links a:hover {
        background: #e9e9e9;
    }

    .support-links .dashicons {
        margin-right: 5px;
        color: #2196F3;
    }

    /* Blog box */
    .royal-mail-blog {
        border-left: 3px solid #FF9800;
    }

    .rss-widget ul li {
        padding: 8px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .rss-widget ul li:last-child {
        border-bottom: none;
    }

    .rss-widget a {
        font-weight: 600;
        text-decoration: none;
    }

    /* RuleHook Styles */
    .royal-mail-rulehook {
        border-left: 3px solid #00d492;
    }

    .rulehook-logo {
        text-align: center;
        margin-bottom: 15px;
    }

    .rulehook-logo img {
        max-width: 60%;
        height: auto;
    }

    .rulehook-widget h4 {
        text-align: center;
        margin: 0 0 15px 0;
        color: #038e62;
        font-weight: 600;
    }

    .rulehook-features {
        margin: 0 0 15px 0;
        padding: 0;
        list-style: none;
    }

    .rulehook-features li {
        padding: 6px 0;
        margin: 0;
    }

    .rulehook-features .dashicons {
        color: #00d492;
        margin-right: 5px;
    }

    .rulehook-button {
        background-color: #00d492 !important;
        border-color: #0dd696 !important;
        color: #ffffff !important;
        width: 100%;
        padding: 10px !important;
        text-align: center;
        height: auto !important;
        font-size: 14px !important;
        line-height: 1.5 !important;
        border-radius: 4px !important;
        transition: background-color 0.2s ease;
        font-weight: 600;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .rulehook-button:hover {
        background-color: #038e62 !important;
        box-shadow: 0 3px 6px rgba(0,0,0,0.15);
    }

    /* Inline Promo Styles */
    #rulehook-promo-notice {
        border-left-color: #00d492;
        padding: 15px;
        position: relative;
    }

    #rulehook-promo-notice p {
        margin: 0 0 10px 0;
    }

    #rulehook-promo-notice p:last-child {
        margin-bottom: 0;
    }

    #rulehook-promo-notice .button-primary {
        background: #00d492;
        border-color: #0dd696;
        box-shadow: 0 1px 0 #4b8b3d;
    }

    #rulehook-promo-notice .button-primary:hover {
        background: #038e62;
        border-color: #059164;
        box-shadow: 0 1px 0 #038e62;
    }

    /* Fix for WP admin */
    #wc_royalmail_default_size label {
        margin-top: 11px !important;
    }
</style>
