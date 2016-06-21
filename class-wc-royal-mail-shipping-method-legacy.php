<?php
/**
 * WC_Royal_Mail_Shipping_Method
 * @author Waseem Senjer
 * @since 1.0.0
 * 
 * */
class WC_Royal_Mail_Shipping_Method extends WC_Shipping_Method {

	private $supported_services = array(
		'firstclasssmall'					=>	'Standard First Class Small Parcel',
		'firstclassmedium'					=>	'Standard First Class Medium Parcel',
		'secondclasssmallparcel'			=>	'Second Class: Small Parcel',
		'secondclassmediumparcel'			=>	'Second Class: Medium Parcel',
	);

	public function __construct($instance_id = 0) {
		parent::__construct(0);
		$this->id = 'wpruby_royalmail';
		$this->method_title = __('Royal Mail', 'wc-royal-mail');
		$this->title = __('Royal Mail', 'wc-royal-mail');
		$this->instance_id = absint( $instance_id );
		$this->init_form_fields();
		$this->init_settings();
		$this->tax_status = 'taxable';

		$this->enabled = $this->get_option('enabled');
		$this->title = $this->get_option('title');

		$this->default_weight = $this->get_option('default_weight');
		$this->default_size = $this->get_option('default_size');
		
		$this->parcel_size = $this->get_option('parcel_size');

		$this->domestic_options = $this->get_option('domestic_options');

	

		$this->availability = $this->get_option('availability');
		$this->countries = $this->get_option('countries');
		$this->debug_mode = $this->get_option('debug_mode');

		add_action('woocommerce_update_options_shipping_' . $this->id, array($this, 'process_admin_options'));
	}

	public function init_form_fields() {

		$dimensions_unit = strtolower(get_option('woocommerce_dimension_unit'));
		$weight_unit = strtolower(get_option('woocommerce_weight_unit'));

		$this->form_fields = array(
			'enabled' => array(		
				'title' => __('Enable/Disable', 'wc-royal-mail'),		
				'type' => 'checkbox',		
				'label' => __('Enable Royal Mail', 'wc-royal-mail'),		
				'default' => 'no',		
			),			
			'title' => array(
				'title' => __('Method Title', 'wc-royal-mail'),
				'type' => 'text',
				'description' => __('This controls the title of the method at the checkout.', 'wc-royal-mail'),
				'default' => __('Royal Mail', 'wc-royal-mail'),
				'desc_tip' => true,
			),
			'debug_mode' => array(
				'title' => __('Enable Debug Mode', 'wc-royal-mail'),
				'type' => 'checkbox',
				'label' => __('Enable ', 'wc-royal-mail'),
				'default' => 'no',
				'description' => __('If debug mode is enabled, the shipping method will be activated just for the administrator. The debug mode will display all the debugging data at the cart and the checkout pages.'),
			),
			'default_weight' => array(
				'title' => __('Default Package Weight', 'wc-royal-mail'),
				'type' => 'text',
				'default' => '0.5',
				'css' => 'width:75px',
				'description' => __("Weight unit: ".$weight_unit."<br> This weight will only be used if the product\s weight are not set in the edit product's page.", 'wc-royal-mail'),
			),
			'default_size' => array(
				'type' => 'default_size',
				'default' => '',
			),
			'parcel_size' => array(
				'title' => __('First / Second Class Parcel Size', 'wc-royal-mail'),
				'type' => 'select',
				'default' => 'all',
				'description' => __('Select the parcel size you\'d like to use for calculating the price of First and Second class parcels. If you select "Small", then the "Small Parcel" price will be used up until 2kg. After 2kg the medium parcel price will always be used.', 'wc-royal-mail'),
				'options' => array(
					'small' => __('Small Parcel (up to 2kg)', 'wc-royal-mail'),
					'medium' => __('Medium Parcel', 'wc-royal-mail'),
				),
			),
			'domestic_options' => array(
				'title' 	=> __('Domestic Parcel Options', 'wc-royal-mail'),
				'type' 		=> 'multiselect',
				'default' 	=> 'firstclasssmall',
				'class' 	=> 'availability wc-enhanced-select',
				'css' 		=> 'width:80%;',
				'default'	=> '',
				'options' 	=> $this->supported_services,
			),	
		);

	}

	
	/**
	 * Admin Panel Options
	 * - Options for bits like 'title' and availability on a country-by-country basis
	 *
	 * @since 1.3.0
	 * @return void
	 */
	public function admin_options() {
		require_once plugin_dir_path(__FILE__).'includes/admin-options.php';
	}


	
}