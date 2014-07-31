<?php
	class WC_Gateway_Dotpay extends WC_Payment_Gateway {

	    // special test customer ID for sandbox
	    const DOTPAY_PAYMENTS_TEST_CUSTOMER = '75293';

	    // Dotpay IP address
	    const DOTPAY_IP = '195.150.9.37';

	    // Dotpay URL
	    const DOTPAY_URL = 'https://ssl.dotpay.pl';

	    // Gateway name
	    const PAYMENT_METHOD = 'dotpay';

		/**
		* initialise gateway with custom settings
		*/
		public function __construct() {

			global $woocommerce;

			$this->id = self::PAYMENT_METHOD;
			$this->icon = WOOCOMMERCE_DOTPAY_PLUGIN_URL . 'resources/images/dotpay.png';
			$this->has_fields = false;
			$this->title = 'Dotpay';
			$this->description = __('Credit card payment via Dotpay', 'dotpay-payment-gateway');

			$this->init_form_fields();
			$this->init_settings();

			//Actions
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
			add_action( 'woocommerce_receipt_' . $this->id, array( $this, 'receipt_page' ) );
			add_action( 'woocommerce_api_' . strtolower( get_class( $this ) ), array( $this, 'check_dotpay_response' ) );
		} 

		public function init_form_fields() {
			$this->form_fields = array(
				'enabled' => array(
					'title' => __('Enable/Disable', 'woocommerce'),
					'type' => 'checkbox',
					'label' => __('Enable Dotpay Payment', 'dotpay-payment-gateway'),
					'default' => 'yes'
				),
				'dotpay_id' => array(
					'title' => __('Dotpay customer ID', 'dotpay-payment-gateway'),
					'type' => 'text',
					'default' => self::DOTPAY_PAYMENTS_TEST_CUSTOMER,
				),
				'title' => array(
					'title' => __('Title', 'woocommerce'),
					'type' => 'text',
					'description' => __('This controls the title which user sees during checkout.', 'dotpay-payment-gateway'),
					'default' => 'Dotpay',
					'desc_tip' => true,
				),
				'description' => array(
					'title' => __('Customer Message', 'woocommerce'),
					'type' => 'textarea',
					'default' => '',
				)
			);
		}

		function process_payment( $order_id ) {
			$order = new WC_Order( $order_id );

			return array(
				'result' 	=> 'success',
				'redirect'	=> $order->get_checkout_payment_url( true )
			);
		}

		function receipt_page( $order ) {
			echo '<p>' . __( 'Thank you - your order is now pending payment. You should be automatically redirected to Dotpay to make payment.', 'dotpay-payment-gateway' ) . '</p>';

			echo $this->generate_dotpay_form( $order );
		}

		function generate_dotpay_form( $order_id ) {
			$order = new WC_Order( $order_id );

			$dotpay_url = self::DOTPAY_URL;

			$customer_id = $this->get_option( 'dotpay_id' );

			$firstname = $order->billing_first_name;
			$lastname = $order->billing_last_name;
			$street = $order->billing_address_1;
			$street_n1 = $order->billing_address_2;
			$city = $order->billing_city;
			$postcode = $order->billing_postcode;
			$country = $order->billing_country;
			$email = $order->billing_email;

			$amount = str_replace(',', '.', $order->get_total());
			$payment_currency = get_woocommerce_currency();
			$return_url = $this->get_return_url( $order );
			$notify_url = str_replace( 'https:', 'http:', add_query_arg('order_id', $order_id, add_query_arg( 'wc-api', 'WC_Gateway_Dotpay', home_url( '/' ) )) );
			$payment_type = 0;

			wc_enqueue_js( '
				$.blockUI({
						message: "' . esc_js( __( 'Thank you for your order. We are now redirecting you to Dotpay to make payment.', 'dotpay-payment-gateway' ) ) . '",
						baseZ: 99999,
						overlayCSS:
						{
							background: "#fff",
							opacity: 0.6
						},
						css: {
							padding:        "20px",
							zindex:         "9999999",
							textAlign:      "center",
							color:          "#555",
							border:         "3px solid #aaa",
							backgroundColor:"#fff",
							cursor:         "wait",
							lineHeight:		"24px",
						}
					});
				jQuery("#submit_dotpay_payment_form").click();
			' );

	        ob_start();
	            include(WOOCOMMERCE_DOTPAY_PLUGIN_DIR . '/frontend/templates/woocommerce-dotpay-payment-button.tpl.php');
	            $html = ob_get_contents();
	        ob_end_clean();

	        return $html;

		}

		function check_dotpay_response() {
			global $woocommerce;

			$order = new WC_Order($_REQUEST['order_id']);

			if($_SERVER['REMOTE_ADDR'] == self::DOTPAY_IP & isset($_POST['t_status'])) {
				if($this->validate_transaction($order, $_POST)) {
					die('OK');
				} else {
					die('FAIL');
				}
			}

			wp_redirect( $this->get_return_url( $order ) );
		}

		public function validate_transaction(WC_Order $order, $data) {
			if(floatval($data['orginal_amount']) == str_replace(',', '.', $order->get_total())) {
				if($order->status != 'completed') {
					switch($data['t_status']) {
						case 1:
							$order->update_status('pending');
							break;
						case 2:
							$order->update_status('processing');
							break;
						case 3:
							$order->update_status('failed');
							break;
						case 4:
							$order->update_status('failed');
							break;
						case 5:
							$order->update_status('on-hold');
							break;
					}
				}
				return true;
			} else {
				return false;
			}
		}
	}