<?php 

function bsend_insert($id_order,$first_name,$email,$phone,$amount,$currency,$b_description,$cmd,$payment_ref,$country,$b_status,$country_ccode,$country_cdial ) {
	global $wpdb;
	$created_on = date('y-m-d h:i:s'); 
	$sql = "INSERT INTO bsend_transaction (id_order,first_name,email,phone,amount,currency,b_description,cmd,payment_ref,country,b_status,country_ccode,country_cdial,created_on )
	 VALUES ($id_order,'$first_name','$email','$phone',$amount,'$currency','$b_description','$cmd','$payment_ref','$country','$b_status','$country_ccode','$country_cdial','$created_on')";
	$result = $wpdb->get_results ($sql);
	return true; // display data
}

function bsend_select_all_transaction() {
	global $wpdb;

	$result = $wpdb->get_results ("SELECT * FROM bsend_transaction ");

	return $result; // display data
	
}

function bsend_select_transaction($id_order) {
	global $wpdb;

	$result = $wpdb->get_var ("SELECT payment_ref FROM bsend_transaction WHERE id_order = $id_order ORDER BY id DESC LIMIT 1");

	return $result; // display data
	
}