<?php

/*
 * Plugin Name: Woocommerce Bsend Payments
 * Plugin URI: https://bsend-op.com/wordpress/woocommerce-bsend-payments/
 * Description: Accept orange money ,Mobile Money , master cart ,visa and others pay Payment in Africa.
 * Author: Bsend
 * Text Domain: Bsend-api
 * Author URI: https://bsend-op.com/
 * Version: 1.1.3
 */


 if (!defined('ABSPATH')):
    exit; // Exit if accessed directly
endif;

  //activer et deactiver le plugin
  register_activation_hook( __FILE__, 'bsend_payment_activate' );
  register_deactivation_hook( __FILE__,  'bsend_payment_deactivate' );

  function bsend_payment_activate(){
    include( plugin_dir_path( __FILE__ ) . 'includes/database.php');

  }
  function bsend_payment_deactivate(){
    include( plugin_dir_path( __FILE__ ) . 'includes/drop_database.php');

  }


include( plugin_dir_path( __FILE__ ) . 'includes/menu-pages.php');
include( plugin_dir_path( __FILE__ ) . 'includes/settings-fields.php');
include( plugin_dir_path( __FILE__ ) . 'includes/enqueue.php');

 /* check if woocommerce is active then create or plugin */
 include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
 $all_plugins = apply_filters('active_plugins', get_option('active_plugins'));
if (stripos(implode($all_plugins), 'woocommerce.php') && is_plugin_active( 'woocommerce/woocommerce.php' )) {


/*
 * This action hook registers our PHP class as a WooCommerce payment gateway
 */
add_filter( 'woocommerce_payment_gateways', 'bsend_add_gateway_class' );
function bsend_add_gateway_class( $gateways ) {
	$gateways[] = 'WC_Bsend_Gateway'; // your class name is here
	return $gateways;
}

/*
 * The class itself, please note that it is inside plugins_loaded action hook
 */
add_action( 'plugins_loaded', 'bsend_init_gateway_class' );
function bsend_init_gateway_class() {

	class WC_Bsend_Gateway extends WC_Payment_Gateway {

 		/**
 		 * Class constructor, more about it in Step 3
 		 */
 		public function __construct() {

            $this->id = 'bsend'; // payment gateway plugin ID
            $this->icon = plugins_url( 'assets/img/blue.png', __FILE__ ); // URL of the icon that will be displayed on checkout page near your gateway name
	        $this->has_fields = true; // in case you need a custom credit card form
            $this->method_title = 'Bsend Payment';
            $this->method_description = 'Accepter les paiements par Orange Money, MTN Mobile Money, Visa, Moov Money, T-Money, Free Money, WAVE et Airtel Money.'; // will be displayed on the options page
        
            // gateways can support subscriptions, refunds, saved payment methods,
            // but in this tutorial we begin with simple payments
            $this->supports = array(
                'products'
            );
        
            // Method with all the options fields
            $this->init_form_fields();
        
            // Load the settings.
            $this->init_settings();
            $this->title = $this->get_option( 'title' );
            $this->description = $this->get_option( 'description' );
            $this->enabled = $this->get_option( 'enabled' );
            // $this->testmode = 'yes' === $this->get_option( 'testmode' );
            // $this->private_key = $this->testmode ? $this->get_option( 'test_private_key' ) : $this->get_option( 'private_key' );
            // $this->publishable_key = $this->testmode ? $this->get_option( 'test_publishable_key' ) : $this->get_option( 'publishable_key' );
            $this->private_key = $this->get_option( 'private_key' );
           
            // This action hook saves the settings
            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
        
            // We need custom JavaScript to obtain a token
            add_action( 'wp_enqueue_scripts', array( $this, 'payment_scripts' ) );
            
            // You can also register a webhook here
            add_action( 'woocommerce_api_{webhook name}', array( $this, 'webhook' ) );

            // add_filter( 'woocommerce_checkout_fields', array( $this, 'bsend_checkout_remove_fields') );
            // add_filter( 'woocommerce_billing_fields', array( $this,  'adjust_requirement_of_checkout_address_fields') );
            

            // add_action( 'woocommerce_after_checkout_billing_form', array( $this,'bsend_checkout_select_field') );
            
            // add_action( 'woocommerce_checkout_process', array( $this,'bsend_checkout_check_if_selected') );
            // add_filter( 'woocommerce_billing_fields', array( $this, 'bsend_checkout_remove_fields') );

 		}

		/**
 		 * Plugin options, we deal with it in Step 3 too
 		 */
 		public function init_form_fields(){

            $this->form_fields = array(
                'enabled' => array(
                    'title'       => 'Enable/Disable',
                    'label'       => 'Enable Misha Gateway',
                    'type'        => 'checkbox',
                    'description' => '',
                    'default'     => 'no'
                ),
                'title' => array(
                    'title'       => 'Title',
                    'type'        => 'text',
                    'description' => 'This controls the title which the user sees during checkout.',
                    'default'     => 'bsend payment',
                    'desc_tip'    => true,
                ),
                'description' => array(
                    'title'       => 'Description',
                    'type'        => 'textarea',
                    'description' => 'This controls the description which the user sees during checkout.',
                    'default'     => 'Pay with your credit card via our super-cool payment gateway.',
                ),
                // 'testmode' => array(
                //     'title'       => 'Test mode',
                //     'label'       => 'Enable Test Mode',
                //     'type'        => 'checkbox',
                //     'description' => 'Place the payment gateway in test mode using test API keys.',
                //     'default'     => 'yes',
                //     'desc_tip'    => true,
                // ),
                // 'Bsen_api_key' => array(
                //     'title'       => 'Api key',
                //     'type'        => 'password'
                // )
                'private_key' => array(
                    'title'       => 'Api key',
                    'type'        => 'password'
                )
                // 'test_publishable_key' => array(
                //     'title'       => 'Test Publishable Key',
                //     'type'        => 'text'
                // ),
                // 'test_private_key' => array(
                //     'title'       => 'Test Private Key',
                //     'type'        => 'password',
                // ),
                // 'publishable_key' => array(
                //     'title'       => 'Live Publishable Key',
                //     'type'        => 'text'
                // ),
                // 'private_key' => array(
                //     'title'       => 'Live Private Key',
                //     'type'        => 'password'
                // )
            );
	
	 	}

		/**
		 * You will need it if you want your custom credit card form
		 */
		public function payment_fields() {

	 	 
		}

		/*
		 * Custom CSS and JS, in most cases required only when you decided to go with a custom credit card form
		 */
	 	public function payment_scripts() {

            // we need JavaScript to process a token only on cart/checkout pages, right?
            if ( ! is_cart() && ! is_checkout() && ! isset( $_GET['pay_for_order'] ) ) {
                return;
            }

            // if our payment gateway is disabled, we do not have to enqueue JS too
            if ( 'no' === $this->enabled ) {
                return;
            }

            // no reason to enqueue JavaScript if API keys are not set
            if ( empty( $this->private_key  ) ) {
                return;
            }

            // do not work with card detailes without SSL unless your website is in a test mode
            if ( ! is_ssl() ) {
                return;
            }

            // let's suppose it is our payment processor JavaScript that allows to obtain a token
            // wp_enqueue_script( 'bsend_js', 'https://www.mishapayments.com/api/token.js' );

            // and this is our custom JS in your plugin directory that works with token.js
            // wp_register_script( 'woocommerce_bsend', plugins_url( 'bsend.js', __FILE__ ), array( 'jquery', 'bsend_js' ) );

            // in most payment processors you have to use PUBLIC KEY to obtain a token
            // wp_localize_script( 'woocommerce_bsend', 'misha_params', array(
            // 	'publishableKey' => $this->publishable_key
            // ) );

            // wp_enqueue_script( 'woocommerce_bsend' );              
	
	 	}

		/*
 		 * Fields validation, more in Step 5
		 */
		public function validate_fields() {

            if( empty( $_POST[ 'billing_first_name' ]) ) {
                wc_add_notice(  'First name is required!', 'error' );
                return false;
            }

            return true;

		}

		/*
		 * We're processing the payments here, everything about it is in Step 5
		 */
		public function process_payment( $order_id ) {

            if(!isset($_POST['private_key']))
				return ;
			
		//	$payment_ref = trim(rtrim(sanitize_text_field($_POST['campay_payment_option'])));
			
				$server_url = "https://bsend-op.com/api/v1.0/payment/control";
                        
			global $woocommerce;

            $api_key = sanitize_text_field($_POST['private_key']); 
            
			$order = wc_get_order( $order_id );

			$price = $order->get_total();
            $order_phone = $order->get_billing_phone();      
			$order_mail = $order->get_billing_email();
			$order_name = $order->get_billing_first_name() ." " . $order->get_billing_last_name() ;
            $order_type = "BS_PAY";
			$currency = "XAF";
			$order_currency = $order->get_currency();
			$order_currency = strtoupper($order_currency);//
			$description = "Payment from : ".site_url()." for other : ".$order->get_id();
            $order_language = 'fr';//
			$payement_ref = $this->guidv4();
            $order_country = $order->get_billing_country();
            
            $order_country_code = "CM";
            $order_country_cdial = "237";
            
			$payment_timeout = 15;
			$order_created_date = $order->get_date_created();
			$order_expiry_time = $order_created_date;
			$order_expiry_time->add(new DateInterval("PT5M"));//
            var_dump($api_key);

            $params = array( 
                "amount" => $price,
                "phone" => $order_phone,
                "email" => $order_mail ,
                "first_name" => $order_name,
                "cmd" => $order_type,
                "currency" => $currency,
                "description"=> $description,
                "langue" => $order_language,
                "payment_ref" => $$payement_ref,
                "public_key" => $api_key,
                "country" => $order_country,
                "country_ccode" => $order_country_code,
                "country_cdial"=>$order_country_cdial
            );
            {
                
            }
			$params = json_encode($params);
			
			$headers = array('Content-Type: application/json');
			
			$response = wp_remote_post($server_url, array(
				"method"=>"POST",
				"sslverify"=>true,
				"headers"=>$headers,
				"body"=>$params
			));
            // print_r($response) ;
			if(!is_wp_error($response))
			{
				// $response_body = wp_remote_retrieve_body($response);
				$resp_array = json_decode($response);

                if($resp_array->response == "Success")
					// return $resp_array->payment_url;
                    return array(
                        "result"=>"success",
                        "redirect"=>$resp_array->payment_url
                    );
					// wc_add_notice(  'dWGTG QTQTQ5R', 'error' );
				elseif($resp_array->response == "error")
				    wc_add_notice( 'NNNN'. $resp_array->message, 'error' );
				else
					wc_add_notice(  'Unable to get access token', 'error' );

				// if(isset($resp_array->token) && !isset($resp_array->non_field_errors))
				// 	return $resp_array->token;
				// elseif(!isset($resp_array->token) && isset($resp_array->non_field_errors))
				//     wc_add_notice(  $resp_array->non_field_errors[0], 'error' );
				// else
				// 	wc_add_notice(  'Unable to get access token', 'error' );
			}
			else
				wc_add_notice(  'Failed to initiate transaction please try again later', 'error' );

	 
					
	 	}

		/*
		 * In case you need a webhook, like PayPal IPN etc
		 */
		public function webhook() {
 
					
	 	}


        public function bsend_checkout_remove_fields( $checkout_fields ) {

            // and to remove the billing fields below
            unset( $checkout_fields[ 'billing' ][ 'billing_company' ] );
            unset( $checkout_fields[ 'billing' ][ 'billing_address_1' ] );
            unset( $checkout_fields[ 'billing' ][ 'billing_address_2' ] );
            unset( $checkout_fields[ 'billing' ][ 'billing_city' ] );
            unset( $checkout_fields[ 'billing' ][ 'billing_state' ] ); // remove state field
            unset( $checkout_fields[ 'billing' ][ 'billing_postcode' ] ); // remove zip code field

	
            //  $checkout_fields[ 'billing' ][ 'billing_first_name' ]['required'] = true;
            //  $checkout_fields[ 'billing' ][ 'billing_last_name' ]['required'] = true;
            //  $checkout_fields[ 'billing' ][ 'billing_phone' ]['required'] = true;
            //  $checkout_fields[ 'billing' ][ 'billing_email' ]['required'] = true;
            //  $checkout_fields[ 'order' ][ 'order_comments' ]['required'] = true; // remove order notes // remove company field
            //  $checkout_fields[ 'billing' ][ 'billing_country' ]['required'] = true;
   
            
            // $checkout_fields[ 'billing' ][ 'billing_city' ]['required'] = false;
            // $checkout_fields[ 'billing' ][ 'billing_state' ]['required'] = false; // remove state field
            // $checkout_fields[ 'billing' ][ 'billing_postcode' ]['required'] = false; // remove zip code field
            // $checkout_fields[ 'billing' ][ 'billing_address_1' ]['required'] = false;
            //  $checkout_fields[ 'billing' ][ 'billing_address_2' ]['required'] = false;
        
             
        
            return $checkout_fields;
        }

        public function adjust_requirement_of_checkout_address_fields( $fields ) { 

                $fields['billing_address_1']['required'] = false;
                $fields['billing_address_2']['required'] = false;
                $fields['billing_postcode']['required'] = false;
                $fields['billing_city']['required'] = false;
                $fields['billing_phone']['required'] = false;
                return $fields;
            
        }
            

        public function bsend_checkout_select_field($checkout){

            woocommerce_form_field( 
                'bsend_country_cdial', 
                array(
                    'type'          => 'select',
                    'required'	    => true,
                    'input_class'   => array('select2-selection' , 'select2-selection--single'),
                    'class'         => array( 'misha-field', 'form-row-wide' ), // array only, read more about classes and styling in the previous step
                    'label'         => 'Numeros dial',
                    'label_class'   => 'misha-label', 
                    'options'    	=> array(
                                      ''		=> 'Please select',
                                    'By phone'	=> 'By phone',
                                    'By email'	=> 'By email'
                                    )
                    ), 
                    $checkout->get_value( 'bsend_country_cdial' ) 
            );

        }

        // <span class="select2-selection select2-selection--single" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-label="Pays/région" role="combobox"><span class="select2-selection__rendered" id="select2-billing_country-container" role="textbox" aria-readonly="true" title="Cameroun">Cameroun</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span>

        public function bsend_checkout_check_if_selected() {

            // you can add any custom validations here
            if ( empty( $_POST[ 'bsend_country_cdial' ] ) ) {
                wc_add_notice( 'Please select your preferred contact method.', 'error' );
            }
            
        }
         

        /*
		 * Get token from campay
		 */
		
		public function get_token($server_uri)
		{
			
			// $user = $this->campay_username;
			// $pass = $this->campay_password;
			
			// $params = array("username"=>$user, "password"=>$pass);
			// //$params = json_encode($params);
			
			// $headers = array('Content-Type: application/json');
			
			// $response = wp_remote_post($server_uri."/api/token/", array(
			// 	"method"=>"POST",
			// 	"sslverify"=>true,
			// 	"headers"=>$headers,
			// 	"body"=>$params
			// ));
			// if(!is_wp_error($response))
			// {
			// 	$response_body = wp_remote_retrieve_body($response);
			// 	$resp_array = json_decode($response_body);

			// 	if(isset($resp_array->token) && !isset($resp_array->non_field_errors))
			// 		return $resp_array->token;
			// 	elseif(!isset($resp_array->token) && isset($resp_array->non_field_errors))
			// 	    wc_add_notice(  $resp_array->non_field_errors[0], 'error' );
			// 	else
			// 		wc_add_notice(  'Unable to get access token', 'error' );
			// }
			// else
			// 	wc_add_notice(  'Failed to initiate transaction please try again later', 'error' );
			
			
			
		}

        public function execute_payment($token, $params, $server_uri)
		{
			
			// $headers = array(
			// 	'Authorization' => 'Token '.$token,
			// 	'Content-Type' => 'application/json'
			// 	);
				
			// $response = wp_remote_post($server_uri."/api/collect/", array(
			// 	"method"=>"POST",
			// 	"sslverify"=>true,
			// 	"body"=>$params,				
			// 	"headers"=>$headers,
			// 	"data_format"=>"body"
			// ));			
			
			// if(!is_wp_error($response))
			// {
			// 	$response_body = wp_remote_retrieve_body($response);
			// 	$resp_array = json_decode($response_body);
			// 	if(isset($resp_array->reference))
			// 		return $resp_array->reference;
			// 	if(!isset($resp_array->reference) && isset($resp_array->message))
			// 		wc_add_notice(  $resp_array->message, 'error' );
			// }
			// else
			// 	wc_add_notice(  __('Failed to initiate transaction please try again later', 'campay-api'), 'error' );
			
		}

        public function check_payment($token, $trans, $server_uri)
		{
			
			// $headers = array(
			// 	'Authorization' => 'Token '.$token,
			// 	'Content-Type' => 'application/json'
			// );
			
			// $response = wp_remote_get($server_uri."/api/transaction/".$trans."/", array(
			// 	"sslverify"=>true,				
			// 	"headers"=>$headers,
			// ));
			
			// if(!is_wp_error($response))
			// {
			// 	$response_body = wp_remote_retrieve_body($response);
			// 	$resp_array = json_decode($response_body);
				
			// 	if(isset($resp_array->status))
			// 		return $resp_array;
			// 	else
			// 		wc_add_notice(  __('Invalid Transaction Reference', 'campay-api'), 'error' );
			// }
			// else
			// 	wc_add_notice(  __('Failed to initiate transaction please try again later', 'campay-api'), 'error' );			
			
		
		}

        public function get_payment_link($token, $parameters, $server_uri)
        {
                
                
                // $headers = array(
                //                 'Authorization' => 'Token '.$token,
                //                 'Content-Type' => 'application/json'
                // );
                            
                // $response = wp_remote_post($server_uri."/api/get_payment_link/", array(
                //     "method"=>"POST",
                //     "sslverify"=>true,
                //     "body"=>$parameters,				
                //     "headers"=>$headers,
                //     "data_format"=>"body"
                // ));
                            
                // if(!is_wp_error($response))
                // {
                //     $response_body = wp_remote_retrieve_body($response);
                //     $resp_array = json_decode($response_body);
                    
                //     if(isset($resp_array->link))
                //         return $resp_array->link;
                //     else
                //         wc_add_notice(  __('Invalid Transaction Reference', 'campay-api'), 'error' );
                                     
                                     
                // }
                // else
                //     wc_add_notice(  __('Failed to initiate transaction please try again later', 'campay-api'), 'error' );			
                
        }

        public function campay_payment_processing_modal()
		{
				// if(is_checkout())
				// {
				// 	?>
					
				<!-- // 	<div id="campay_modal_processing" class="modal">

				// 	  <!-- Modal content -->
				 	  <!-- <div class="modal-content">
				 		<h3 style="text-align:center; text-decoration: underline"><?php echo __('PAYMENT PROCESSING', 'campay-api'); ?></h3>
				 			<p class="cp_payment_info">
				 				<?php //echo __('We are waiting for your payment. Please dial *126# for MTN and #150*50# for Orange.', 'campay-api'); ?>
				 			</p>
				 			<div class="cp_payment_waiting">
				 				<img src="<?php echo plugins_url( 'assets/img/wait.gif', __FILE__ ); ?>" />
				 			</div>
				 	  </div>

				 	</div> -->
					
				 	<?php 
				// }
		}

        public function campay_checkout_form_submit()
		{
				// if(is_checkout())
				// {
				// 	?>
				// 	<script>
				// 	    var form = document.getElementsByName("checkout");
						
				// 		if(form)
				// 		{
							
				// 			function checkCampay()
				// 			{
				// 				var payment_method = document.getElementsByName("payment_method");
				// 				function isChecked(item)
				// 				{
				// 					if(item.checked)
				// 					{
				// 						if(item.value=="campay")
				// 							document.getElementById("campay_modal_processing").style.display="block";
										
				// 					}
				// 				}
								
				// 				payment_method.forEach(isChecked);
							
				// 			}

				// 			form[0].addEventListener("submit", checkCampay);
				// 		}
				// 		function pay_card()
				// 		{
							
				// 				event.preventDefault();
				// 				document.getElementById("campay_payment_option").value="card";
				// 				document.getElementById("place_order").click();
								
							
				// 		}
				// 	</script>
				// 	<?php
				// }
		}
 	}
}
}
else{
	return false;
}