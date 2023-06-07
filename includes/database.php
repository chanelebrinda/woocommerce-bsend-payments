<?php  

global $jal_db_version;
$jal_db_version = '1.0';

function bsend_transaction(){
   global $wpdb;
   global $jal_db_version;

   $charset_collate = $wpdb->get_charset_collate();

   $sql = "CREATE TABLE bsend_transaction(
       id int(10) unsigned NOT NULL AUTO_INCREMENT,
       id_order int(10) NOT NULL unique,
       first_name varchar(100) DEFAULT '' NOT NULL,
       email varchar(100) DEFAULT '' NOT NULL,
       phone bigint(30) NOT NULL,
       amount bigint(10) NOT NULL,
       currency varchar(100) DEFAULT '' NOT NULL,
       b_description text DEFAULT '' NOT NULL,
       cmd varchar(100) DEFAULT '' NOT NULL,
       payment_ref varchar(100) DEFAULT '' NOT NULL, 
       country varchar(100) DEFAULT '' NOT NULL,    
       b_status varchar(100) DEFAULT '' NOT NULL,
       country_ccode varchar(100) DEFAULT '' NOT NULL,
       country_cdial varchar(100) DEFAULT '' NOT NULL,
       created_on datetime DEFAULT '2022-01-01 00:00:00' NOT NULL,
       modified_on datetime DEFAULT '2022-01-01 00:00:00' NOT NULL,
       PRIMARY KEY  (id)
   ) $charset_collate;";

   require_once ABSPATH . 'wp-admin/includes/upgrade.php';
   dbDelta( $sql );
}

bsend_transaction();