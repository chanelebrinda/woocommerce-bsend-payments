<?php 

function tentee_reservation_insert_reservation($id_order,$first_name,$email,$phone,$amount,$currency,$b_description,$cmd,$payment_ref,$public_key,$country,$source,$b_status,$country_ccode,$country_cdial ) {
	global $wpdb;
	$created_on = date('y-m-d h:i:s'); 
	$sql = "INSERT INTO bsend_transaction (id_order,first_name,email,phone,amount,currency,b_description,cmd,payment_ref,public_key,country,source,b_status,country_ccode,country_cdial,created_on )
	 VALUES ($id_order,'$first_name','$email','$phone',$amount,'$currency','$b_description','$cmd','$payment_ref','$public_key','$country','$source','$b_status','$country_ccode','$country_cdial','$created_on')";
	$result = $wpdb->get_results ($sql);
	return true; // display data
}

function bsend_select_all_transaction() {
	global $wpdb;

	$result = $wpdb->get_results ("SELECT * FROM bsend_transaction ");

	return $result; // display data
	
}

function bsend_select_transaction($id_r) {
	global $wpdb;

	$result = $wpdb->get_results ("SELECT * FROM IB_tentee_reservation WHERE id = $id_r");

	return $result; // display data
	
}