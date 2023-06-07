<?php

function intiatebsendPayement(){
			
     $server_url = "https://bsend-op.com/api/v1.0/payment/control";
                     
    // global $woocommerce, $post;
    // $id_order = $post->ID ;
    // $order = new WC_Order($post->ID); 
    $price = $_POST['amount'];
    $order_phone = $_POST['phone'];    
    $order_mail = $_POST['email'];
    $order_name = $_POST['first_name'];
    $order_type = "BS_PAY";
    $currency = "XAF"; 
    $description = $_POST['description'];
    $order_language = 'fr';//
    $payement_ref = $_POST['payment_ref'];
    $api_key = $_POST['public_key'];
    $order_country = $_POST['country'];
    $order_country_cdial = $_POST['country_cdial'];

    if(!isset($_POST['public_key'])){
        wp_send_json( 'unable to get the public key');
    }

        $params = array( 
            "amount" => $price,
            "phone" => $order_phone,
            "email" => $order_mail ,
            "first_name" => $order_name,
            "cmd" => $order_type,
            "currency" => $currency,
            "description"=> $description,
            "langue" => $order_language,
            "payment_ref" => $payement_ref,
            "public_key" => $api_key,
            "country" => $order_country,
            "country_ccode" => $order_country,
            "country_cdial"=> $order_country_cdial
        ); 
        
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
                    $b_status = "pending";
                    // bsend_insert($order_id,$order_name,$order_mail,$order_phone,$price,$currency
                    // ,$description,$order_type,$payement_ref,$order_country,
                    // $b_status,$order_country,$order_country_cdial );
                    $result = array(
                        "code" =>200,
                        "ref" =>$payement_ref,
                        "link" =>$resp_array->payment_url
                        
                    ); 

                    wp_send_json($result);
                }
				else if($resp_array->response == "error"){ 

                    $result = array(
                        "code"=> 400,
                        "message"=>$resp_array->message              
                    ); 
					wp_send_json($result );

                }else{
                    $result = array(
                        "code"=> 400,
                        "message"=>'Unable to get access'                
                    ); 
					wp_send_json($result );
                }
			}
			else{
                $err = $response->get_error_message();
                $result = array(
                    "code"=>400,
                    "message"=>$err
                ); 
                wp_send_json($result);
            }
   
  }
  add_action( 'wp_ajax_nopriv_intiatebsendPayement', 'intiatebsendPayement' );
  add_action( 'wp_ajax_intiatebsendPayement', 'intiatebsendPayement' );
  