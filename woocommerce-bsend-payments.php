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
    // include( plugin_dir_path( __FILE__ ) . 'includes/database.php');

  }
  function bsend_payment_deactivate(){
    // include( plugin_dir_path( __FILE__ ) . 'includes/drop_database.php');

  }

  
// include( plugin_dir_path( __FILE__ ) . 'includes/bsend_insert_data.php');
// include( plugin_dir_path( __FILE__ ) . 'includes/menu-pages.php');
// include( plugin_dir_path( __FILE__ ) . 'includes/settings-fields.php');
// include( plugin_dir_path( __FILE__ ) . 'includes/enqueue.php');
include( plugin_dir_path( __FILE__ ) . 'includes/ajax.php');
  
 /* check if woocommerce is active then create or plugin :stripos(implode($all_plugins), 'woocommerce.php') && */
 include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
 $all_plugins = apply_filters('active_plugins', get_option('active_plugins'));
if (is_plugin_active( 'woocommerce/woocommerce.php' )) {


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
 
            add_action( "wp_head",array($this,  "bsend_get_total_oredert"), 20 );
            $this->id = 'bsend'; 
            $this->icon = plugins_url( 'assets/img/blue.png', __FILE__ );
	        $this->has_fields = true; 
            $this->method_title = 'Bsend Payment';
            $this->method_description = 'Accepter les paiements par Orange Money, MTN Mobile Money, Visa, Moov Money, T-Money, Free Money, WAVE et Airtel Money.'; // will be displayed on the options page

            // gateways can support subscriptions, refunds, saved payment methods,
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
            $this->private_key = $this->get_option( 'private_key' );
            
            add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'bsend_payment_js' ) );
                   
 		}

		/**
 		 * Plugin options, we deal with it in Step 3 too
 		 */
        public function bsend_get_total_oredert($order){
            
            global $woocommerce;
            $newTotal = $woocommerce->cart->total;
                
                ?>
            <script>
                jQuery(document).ready(function ($) {
                    $('#bsend_link').val(<?php echo $newTotal; ?>);
                });
               ;
            </script>
            <?php
        }

 		public function init_form_fields(){

            $this->form_fields = array(
                'enabled' => array(
                    'title'       => 'Enable/Disable',
                    'label'       => 'Enable bsend Gateway',
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
                'private_key' => array(
                    'title'       => 'Api key',
                    'type'        => 'password'
                ) 
            );
	
	 	}

		/**
		 * You will need it if you want your custom credit card form
		 */
		public function payment_fields() {
		  
		  function randomAbc123(){
            $characts    = 'abcdefghijklmnopqrstuvwxyz';
             $characts   .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';	
            $characts   .= '1234567890'; 
            $characts   .= '!?#$%^'; 
            $code_aleatoire      = ''; 
        
            for($i=0;$i < 10;$i++)
            { 
                $code_aleatoire .= substr($characts,rand()%(strlen($characts)),1); 
                
            }

          return $code_aleatoire; 
        }
		  
		     $ref = randomAbc123();
                
	 	 ?>  
           <!-- <div style="display: flex;flex-wrap: wrap; justify-content: center;">
              <img style="height: 75px ; padding-top: 2px" src="<?php //echo plugins_url( 'assets/img/download6.jpg', __FILE__ )?>">
              <img style="height: 75px ; padding-top: 2px " src="<?php// echo plugins_url( 'assets/img/download7.jpg', __FILE__ )?>">
               <img style="height: 75px ; padding-top: 2px " src="<?php// echo plugins_url( 'assets/img/download2.png', __FILE__ )?>">
              <img style="height: 75px ; padding-top: 2px " src="<?php //echo plugins_url( 'assets/img/download3.jpg', __FILE__ )?>">
              <img style="height: 75px ; padding-top: 2px " src="<?php//echo plugins_url( 'assets/img/download4.jpg', __FILE__ )?>">
              <img style="height: 75px ; padding-top: 2px " src="<?php //echo plugins_url( 'assets/img/download1.png', __FILE__ )?>">
              <img style="height: 75px ; padding-top: 2px " src="<?php //echo plugins_url( 'assets/img/download5.png', __FILE__ )?>"> 
              <img style="height: 75px ; padding-top: 2px " src="<?php //echo plugins_url( 'assets/img/maxresdefault.jpg', __FILE__ )?>">
               <img style="height: 75px ; padding-top: 2px " src="<?php //echo plugins_url( 'assets/img/download9.png', __FILE__ )?>">
                
          </div> -->
         
           <input type="hidden" id="bsend_link" name="bsend_link" value="">
           <input type="hidden" id="bsend_checkout_test" name="bsend_checkout_test" value="<?php echo $ref; ?>">
          
         <?php 
		
		}

		/*
		 * Custom CSS and JS, in most cases required only when you decided to go with a custom credit card form
		 */
		
		public function bsend_payment_js()
		{
  
			wp_enqueue_script(
                'woocommerce_bsend',
                plugins_url( 'assets/js/index.js', __FILE__ ), 
                ['jquery'],
                NULL, 
                true 
              );
                $argarray = array(
                    'ajaxurl' => admin_url( 'admin-ajax.php' ),
                    'private_key' => $this->private_key
                );
               wp_localize_script( 'woocommerce_bsend' , 'bsend_params' , $argarray  );

		}

        // public function randomAbc123(){
        //     $characts    = 'abcdefghijklmnopqrstuvwxyz';
        //      $characts   .= 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';	
        //     $characts   .= '1234567890'; 
        //     $characts   .= '!?#$%^'; 
        //     $code_aleatoire      = ''; 
        
        //     for($i=0;$i < 10;$i++)
        //     { 
        //         $code_aleatoire .= substr($characts,rand()%(strlen($characts)),1); 
        //         $code_aleatoire .= 'bsend1.0'; 
        //     }

        //   return $code_aleatoire; 
        // }

		/*
		 * We're processing the payments here, everything about it is in Step 5
		 */
		public function process_payment( $order_id ) {

            global $woocommerce;
  
			$order = wc_get_order( $order_id );
           
            $today = strtotime("now");
            $payment_timeout = 3;
            $expiry = strtotime("+".$payment_timeout." minutes", $today);
            $payement_ref = sanitize_text_field($_POST['bsend_checkout_test']); 
            $api_key = $this->private_key ;
            $payment_completed = 0;  
            $payment = [] ;

            if(!empty( $_POST['bsend_checkout_test'])){

                while($today <=  $expiry){
                    sleep(2);
                     
                    $payment = $this->bsend_check_payment($payement_ref,$api_key);
        
                        if(!empty($payment))
                        {

                            if($payment['result'] == "success"){

                                if($payment['statut'] == "success") {

                                    $order->update_status('completed', __('Payment received', 'bsend'));
                                    $order->add_order_note( 'Transaction complete with ref : '.$payement_ref, true );
                                    $order->payment_complete();
                                    $order->reduce_order_stock();
                                    WC()->cart->empty_cart();	
                                    $payment_completed = 1;
                                    $today = strtotime("now")+180;
                                    break;

                                }else if($payment['statut'] == "pending") {
	
                                    $payment_completed = 2;
                                //     // $order->update_status('processing', __('Payment processing', 'bsend'));
                                    $today = strtotime("now")+2;

                                }else{

                                    $order->update_status('failed', __('Payment was cancelled', 'bsend'));
                                    $payment_completed = 3; 
                                     $today = strtotime("now")+180;
                                    break;

                                }

                           }else{
                              $payment_completed = 4; 
                              $today = strtotime("now")+180;
                            }             
                       }else{
                        $today = strtotime("now")+180;
                        break;
                       }
                      
                }

                if( $payment_completed == 1)
                {
                        $order->add_order_note( 'Transaction was sucessful : '.$payement_ref, true ); 
                        return array(
                                'result' => 'success',
                                'redirect' => $this->get_return_url($order)
                        );
                }	
                elseif($payment_completed == 2)
                {
                    $order->update_status('failed', __('Payment was cancelled', 'bsend'));  
                    wc_add_notice(  __('Payment failed! the waiting period has ended, you should try again', 'bsend'), 'error' );
                    $order->add_order_note( 'Transaction failed with ref : '.$payement_ref, true );                
              
                }elseif( $payment_completed == 3 )
                {
                     
                    wc_add_notice(  __('Payment failed! Payment was declined by payer or insuficient funds', 'bsend'), 'error' );
                    $order->add_order_note( 'Transaction failed with ref : '.$payement_ref, true );                
              
               }else{
                    $order->update_status('failed', __('Payment was cancelled', 'bsend'));                                
                    wc_add_notice(  __('Failed to initiate transaction please try again later'), 'error' );	
               
                }

            }else{
                wc_add_notice(  __('Failed to initiate transaction please try again later'), 'error' );	
                          
            }
            

        }
 
        
        public function bsend_check_payment($ref,$public_key)
		{
            
			$server_url = "https://bsend-op.com/api/v1.0/api-checkout";
			
            $headers = array('Content-Type: application/json');
        
            $data = array( 
                "payment_ref" => $ref,
                "public_key" => $public_key,
            );
			
			$response = wp_remote_post($server_url, array(
				"method"=>"POST",
				"sslverify"=>true,
				"headers"=>$headers,
				"body"=>$data,
                'timeout'   => 45,
                'data_format' => 'body',
			));	

            if(!is_wp_error($response)) {
				$response_body = wp_remote_retrieve_body($response);
				$resp_array = json_decode($response_body); 
                

                if($resp_array->response == "Success") {
                    // wc_add_notice( '' . $resp_array->response, 'error' );
                   return array(
                        "result"=>"success",
                        "statut"=>$resp_array->statut
                    );  
                }
				else if($resp_array->response == "error"){
				    //  wc_add_notice( '' . $resp_array->message, 'error' );
                    return array(
                        "result"=>"error",
                        "statut"=>$payment_ref
                        
                    );  
                }else{
				//	 wc_add_notice(  'Unable to get access', 'error' );
                    return array(
                        "result"=>"error",
                        "statut"=>'Unable to get access'
                    );
                }
			}
			else{
                $err = $response->get_error_message();;
				// wc_add_notice(  __('Error : '.$err.''), 'error' );
                return array(
                    "result"=>"error",
                    "statut"=>$err
                    
                );
            } 
	 	}


        public function execute_payment( $params, $server_url)
		{
			
			$headers = array('Content-Type: application/json');
        
			
			$response = wp_remote_post($server_url, array(
				"method"=>"POST",
				"sslverify"=>true,
				"headers"=>$headers,
				"body"=>$params,
                'timeout'   => 45,
                'data_format' => 'body',
			));		
			
			if(!is_wp_error($response)) {
				$response_body = wp_remote_retrieve_body($response);
				$resp_array = json_decode($response_body); 
                

                if($resp_array->response == "Success") {
                    
                 

                    // wc_add_notice(  ''. $resp_array->response.'', 'error' );
                    return array(
                        "result"=>$resp_array->response,
                        "redirect"=>$resp_array->payment_url
                        
                    ); 
                }
				else if($resp_array->response == "error"){
				    wc_add_notice( 'Error:' . $resp_array->message, 'error' );
                }else{
					wc_add_notice(  'Unable to get access', 'error' );
                }
			}
			else{
                $err = $response->get_error_message();;
				wc_add_notice(  __('Error : '.$err.''), 'error' );
            }
		}
        

}}
}else{
	return false;
}