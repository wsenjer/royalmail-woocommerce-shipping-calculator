<?php
/**
 * WC_Royal_Mail_Shipping_Method
 * @author Waseem Senjer
 * @since 1.0.0
 * 
 * */
class WC_Royal_Mail_Shipping_Method extends WC_Shipping_Method {

	public $supported_services = array(
		'firstclasssmall'					=>	'Standard First Class Small Parcel',
		'firstclassmedium'					=>	'Standard First Class Medium Parcel',
		'secondclasssmallparcel'			=>	'Second Class: Small Parcel',
		'secondclassmediumparcel'			=>	'Second Class: Medium Parcel',
		'firstclasssignedforsmall'			=>	'Signed For: First Small Parcel',
		'firstclasssignedformedium'			=>	'Signed For: First Medium Parcel',
		'secondclasssmallparcelsignedfor'	=>	'Signed For: Second Class Small Parcel',
		'secondclassmediumparcelsignedfor'	=>	'Signed For: Second Class Medium Parcel',
		'specialdelivery9am'				=>	'Special Delivery: Guaranteed by 9am',
		'specialdelivery1pm'				=>	'Special Delivery: Guaranteed by 1pm',
		'specialdelivery1pmsaturday'		=>	'Special Delivery: Guaranteed by 1pm Saturday',
		'specialdelivery9amsaturday'		=>	'Special Delivery: Guaranteed by 9am Saturday',
	);

	public function __construct($instance_id = 0) {
		$this->id = 'wpruby_royalmail';
		$this->method_title = __('Royal Mail', 'wc-royal-mail');
		$this->title = __('Royal Mail', 'wc-royal-mail');
		$this->instance_id = absint( $instance_id );
		$this->init_form_fields();
		$this->init_settings();
		$this->supports  = array(
 			'shipping-zones',
 			'instance-settings',
 		);
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

		$this->instance_form_fields = array(
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
				'default' => 'small',
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

	public function calculate_shipping( $package = array() ) {
		if($package['destination']['country'] != 'GB'){
			return;
		}
		require_once(plugin_dir_path(__FILE__). 'includes/royalmail/Src/CalculateMethod.php');
		require_once(plugin_dir_path(__FILE__). 'includes/royalmail/Src/Data.php');
		require_once(plugin_dir_path(__FILE__). 'includes/royalmail/Src/Method.php');

		$package_details =  $this->get_package_details($package);
		
		$this->debug('Settings: ', json_encode($this->settings));
		$this->debug('Packing Details', $package_details);

		$calculateMethodClass = new Meanbee_RoyalmailPHPLibrary_CalculateMethod();
   		$dataClass = new Meanbee_RoyalmailPHPLibrary_Data(
        	$calculateMethodClass->_csvCountryCode,
        	$calculateMethodClass->_csvZoneToDeliverMethod,
        	$calculateMethodClass->_csvDeliveryMethodMeta,
        	$calculateMethodClass->_csvDeliveryToPrice,
        	$calculateMethodClass->_csvCleanNameToMethod,
        	$calculateMethodClass->_csvCleanNameMethodGroup
   		);
        
   		$rates = array();
        foreach($package_details as $pack){
        	$allowedMethods = $this->getAllowedMethods($pack, $package['destination']['country']);
	        if (empty($allowedMethods) == false) {

	            // NEEDS INVESTIGATION
	            $dataClass->setWeightUnit(strtolower(get_option('woocommerce_weight_unit')));
	            $dataClass->_setWeight($pack['weight']);

	            $calculatedMethods = $calculateMethodClass->getMethods(
	            	$package['destination']['country'],
	                '1',
	                $pack['weight']
	            );

	            // Config check to remove small or medium parcel size based on the
	            // config value set in the admin panel
	                if ($this->parcel_size == 'small' ||
	                    $this->parcel_size == ""
	                ) {
	                    foreach ($calculatedMethods as $key => $value) {
	                        if ($value->size == 'MEDIUM') {
	                            unset($calculatedMethods[$key]);
	                        }
	                    }
	                }
	                if ($this->parcel_size == 'medium') {
	                    foreach ($calculatedMethods as $key => $value) {
	                        if ($value->size == 'SMALL') {
	                            unset($calculatedMethods[$key]);
	                        }
	                    }
	                }


	            $allMethods = $this->getAllMethods();
	            foreach ($allowedMethods as $allowedMethod) {
	                
	                foreach ($calculatedMethods as $methodItem) {
	                    if(isset($allMethods[$allowedMethod])){
		                    if ($allMethods[$allowedMethod] == $methodItem->shippingMethodNameClean) {
								$this->debug('Shipping Methods: ', $methodItem);
		                    	
	                        	$price = $methodItem->methodPrice;

								if(!isset($rates[$methodItem->shippingMethodName])){
									$rates[$methodItem->shippingMethodName] = array();
			                        $rates[$methodItem->shippingMethodName]['id'] =  $methodItem->shippingMethodName;
			                        $rates[$methodItem->shippingMethodName]['label'] = $this->title;

			                        $rates[$methodItem->shippingMethodName]['label'] .= ': '. $methodItem->shippingMethodNameClean;
		             				$rates[$methodItem->shippingMethodName]['cost'] = $price;

								}else{
		             				$rates[$methodItem->shippingMethodName]['cost'] = $rates[$methodItem->shippingMethodName]['cost'] + $price;
								}
		                    
		                    }
	                	}
	                }

	            }
	        }
    	}
    	uasort( $rates, array( $this, 'sort_rates' ) );
		foreach($rates as $key => $rate){
			$rate['package'] = $package;
			$this->add_rate($rate);
		}
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

	/**
	 * get_min_dimension function.
	 * get the minimum dimension of the package, so we multiply it with the quantity
	 * @access private
	 * @param number $width
	 * @param number $length
	 * @param number $height
	 * @return string $result
	 */
	public static function get_min_dimension($width, $length, $height) {

		$dimensions = array('width' => $width, 'length' => $length, 'height' => $height);
		$result = array_keys($dimensions, min($dimensions));
		return $result[0];
	}

	/**
	 * get_package_details function.
	 *
	 * @access private
	 * @param mixed $package
	 * @return void
	 */
	private function get_package_details($package) {
		global $woocommerce;

		$default_length = (isset($this->default_size['length']))?$this->default_size['length']:1;
		$default_width = (isset($this->default_size['width']))?$this->default_size['width']:1;
		$default_height = (isset($this->default_size['height']))?$this->default_size['height']:1;

		$parcel = array();
		$requests = array();
		$weight = 0;
		$volume = 0;
		$value = 0;
		$products = array();
		// Get weight of order
		foreach ($package['contents'] as $item_id => $values) {
			$final_weight = wc_get_weight((floatval($values['data']->get_weight()) <= 0) ? $this->default_weight : $values['data']->get_weight(), 'kg');
			$weight += $final_weight * $values['quantity'];
			$value = $values['data']->get_price();

			$length = woocommerce_get_dimension((floatval($values['data']->length) <= 0) ? $default_length : $values['data']->length, 'cm');
			$height = woocommerce_get_dimension((floatval($values['data']->height) <= 0) ? $default_height : $values['data']->height, 'cm');
			$width = woocommerce_get_dimension((floatval($values['data']->width) <= 0) ? $default_width : $values['data']->width, 'cm');
			$min_dimension = self::get_min_dimension($width, $length, $height);
			$products[] = array('weight' => wc_get_weight((floatval($values['data']->get_weight()) <= 0) ? $this->default_weight : $values['data']->get_weight(), 'kg'),
				'quantity' => $values['quantity'],
				'length' => $length,
				'height' => $height,
				'width' => $width,
				'item_id' => $item_id,
				'value' => $value,
				'min_dimension' => $min_dimension,
			);

			$volume += ($length * $height * $width);
		}
		$max_weights = $this->royalmail_get_max_weight($package, $products);
		// @since 1.5 order the products by their postcodes
		array_multisort($products, SORT_ASC, $products);

		$pack = array();
		$packs_count = 1;
		$pack[$packs_count]['weight'] = 0;
		$pack[$packs_count]['length'] = 0;
		$pack[$packs_count]['height'] = 0;
		$pack[$packs_count]['width'] = 0;
		$pack[$packs_count]['quantity'] = 0;
		$pack[$packs_count]['value'] = 0;
		$i = 0;
		foreach ($products as $product) {
			$max_weight = $max_weights['own_package'];
			while ($product['quantity'] > 0) {
				if (!isset($pack[$packs_count]['weight'])) {
					$pack[$packs_count]['weight'] = 0;
				}
				if (!isset($pack[$packs_count]['quantity'])) {
					$pack[$packs_count]['quantity'] = 0;
				}
				$pack[$packs_count]['weight'] += $product['weight'];
				$pack[$packs_count]['length'] = ('length' == $product['min_dimension']) ? $pack[$packs_count]['length'] + $product['length'] : $product['length'];
				$pack[$packs_count]['height'] = ('height' == $product['min_dimension']) ? $pack[$packs_count]['height'] + $product['height'] : $product['height'];
				$pack[$packs_count]['width'] = ('width' == $product['min_dimension']) ? $pack[$packs_count]['width'] + $product['width'] : $product['width'];
				$pack[$packs_count]['item_id'] = $product['item_id'];
				$pack[$packs_count]['quantity'] += 1;
				$pack[$packs_count]['value'] += round($product['value'], 2);
				$package_height = self::get_min_dimension($pack[$packs_count]['width'], $pack[$packs_count]['length'], $pack[$packs_count]['height']);
	

				if ($pack[$packs_count]['weight'] > $max_weight) {

					$pack[$packs_count]['value'] -= round($product['value'], 2);

					$pack[$packs_count]['length'] = ('length' == $product['min_dimension']) ? $pack[$packs_count]['length'] - $product['length'] : $product['length'];
					$pack[$packs_count]['height'] = ('height' == $product['min_dimension']) ? $pack[$packs_count]['height'] - $product['height'] : $product['height'];
					$pack[$packs_count]['width'] = ('width' == $product['min_dimension']) ? $pack[$packs_count]['width'] - $product['width'] : $product['width'];

					$pack[$packs_count]['quantity'] -= 1;
					$pack[$packs_count]['weight'] -= $product['weight'];


					$packs_count++;
					$pack[$packs_count]['weight'] = $product['weight'];
					$pack[$packs_count]['length'] = $product['length'];
					$pack[$packs_count]['height'] = $product['height'];
					$pack[$packs_count]['width'] = $product['width'];
					$pack[$packs_count]['item_id'] = $product['item_id'];
					$pack[$packs_count]['quantity'] = 1;
					$pack[$packs_count]['value'] = round($product['value'], 2);

				}
				$product['quantity']--;
			}
			$i++;
		}
		return $pack;
	}

	

	private function royalmail_get_max_weight($package, $products = array()) {
		// @TODO
		$country = $package['destination']['country'];

		$max_weights = array();

		$max_weights['own_package'] = ($country == 'GB') ? 30 : 2;
		if($country == 'GB'){
			if($this->parcel_size == 'small'){
				return array(
					'own_package' => 2,
				);

			}elseif($this->parcel_size == 'medium'){
				return array(
					'own_package' => 20,
				);
			}else{
				return array(
					'own_package' => $max_weights['own_package'],
				);
			}
		}else{
			return array(
				'own_package' => $max_weights['own_package'],
			);
		}
		
	}


	




	public function generate_default_size_html() {
		$dimensions_unit = strtolower(get_option('woocommerce_dimension_unit'));
		if(version_compare(WC()->version, '2.6.0', 'lt')){
			$length = (isset($this->default_size['length']))?$this->default_size['length']:'';
			$width  = (isset($this->default_size['width']))?$this->default_size['width']:'';
			$height  = (isset($this->default_size['height']))?$this->default_size['height']:'';
		}else{
			$length = (isset($this->instance_settings['default_size']['length']))?$this->instance_settings['default_size']['length']:'';
			$width = (isset($this->instance_settings['default_size']['width']))?$this->instance_settings['default_size']['width']:'';
			$height = (isset($this->instance_settings['default_size']['height']))?$this->instance_settings['default_size']['height']:'';
		}
		ob_start();
		?>
		<tr valign="top">
			<th scope="row" class="titledesc">
				<label for="woocommerce_wpruby_royalmail_debug_mode"><?php _e('Default Package Size', 'wc-royal-mail') ?></label>
							</th>
			<td class="forminp">
				<fieldset>
					<label for="woocommerce_wpruby_royalmail_default_length"><?php _e('Length', 'wc-royal-mail'); ?></label> <input id="woocommerce_wpruby_royalmail_default_length" name="woocommerce_wpruby_royalmail_default_length" type="text" value="<?php echo esc_attr($length); ?>" style="width:70px" /> 
					<label for="woocommerce_wpruby_royalmail_default_width"><?php _e('Width', 'wc-royal-mail'); ?></label>  <input id="woocommerce_wpruby_royalmail_default_width" name="woocommerce_wpruby_royalmail_default_width" type="text" value="<?php echo esc_attr($width); ?>" style="width:70px" /> 
					<label for="woocommerce_wpruby_royalmail_default_height"><?php _e('Height', 'wc-royal-mail'); ?></label> <input id="woocommerce_wpruby_royalmail_default_height" name="woocommerce_wpruby_royalmail_default_height" type="text" value="<?php echo esc_attr($height); ?>" style="width:70px" /> 
					<p class="description">Size unit: <?php echo $dimensions_unit; ?><br> This dimension will only be used if the product\s dimensions are not set in the edit product's page.</p>
				</fieldset>
			</td>
		</tr>

		<?php
	return ob_get_clean();

	}
	/**
	 * validate_default_size_field function.
	 *
	 * @access public
	 * @param mixed $key
	 * @return void
	 */
	public function validate_default_size_field($key) {
		$dimensions = array();
		if(is_numeric($_POST['woocommerce_wpruby_royalmail_default_length']) && $_POST['woocommerce_wpruby_royalmail_default_length'] > 0)
			$dimensions['length'] = $_POST['woocommerce_wpruby_royalmail_default_length'];
		if(is_numeric($_POST['woocommerce_wpruby_royalmail_default_width']) && $_POST['woocommerce_wpruby_royalmail_default_width'] > 0)
			$dimensions['width']  = $_POST['woocommerce_wpruby_royalmail_default_width'];
		if(is_numeric($_POST['woocommerce_wpruby_royalmail_default_height']) && $_POST['woocommerce_wpruby_royalmail_default_height'] > 0)
			$dimensions['height'] = $_POST['woocommerce_wpruby_royalmail_default_height'];

		return $dimensions;
	}

	private function getAllowedMethods($pack, $country){
		$domestic_options = (($this->domestic_options == ''))?array():(((is_array($this->domestic_options)))?$this->domestic_options:array($this->domestic_options));
		return $domestic_options;
	}
	private function getAllMethods(){
		return array_merge($this->supported_services);
	}
	/**
	 * Output a message
	 */
	private function debug($message, $array, $type = 'notice') {
		if ($this->debug_mode == 'yes' && current_user_can('manage_options')) {
			$message = $message . ': <pre>' . print_r($array, true). '</pre>';
			if (version_compare(WOOCOMMERCE_VERSION, '2.1', '>=')) {
				wc_add_notice($message, $type);
			} else {
				global $woocommerce;
				$woocommerce->add_message($message);
			}
		}
	}

	/**
	 * check_store_requirements function.
	 *
	 * @access public
	 * @return void
	 */
	public function check_store_requirements() {
		if ( get_woocommerce_currency() != "GBP" ) {
			$this->display_error_message( __( 'In order to the Royal Mail extension to work, the store currency must be British Pounds.', 'wc-royal-mail' ) );
		}

		if ( WC()->countries->get_base_country() != "GB" ) {
			$this->display_error_message( __( 'In order to the Royal Mail extension to work, the base country/region must be set to United Kingdom.', 'wc-royal-mail' ) );
		}
	}
	/**
	 * @param $message string
	 */
	private function display_error_message( $message ){
		$url = esc_url( self_admin_url( 'admin.php?page=wc-settings&tab=general' ) );
		$link = "<a href='$url'>" . esc_html( __( 'Click here to change the setting.', 'wc-royal-mail' ) ) . '</a>';
		$message = esc_html( $message );

		echo "<div class='error'><p>$message $link</p></div>";
	}

	/** sort rates based on cost **/	
    public function sort_rates( $a, $b ) {
		if ( $a['cost'] == $b['cost'] ) return 0;
		return ( $a['cost'] < $b['cost'] ) ? -1 : 1;
    }
}