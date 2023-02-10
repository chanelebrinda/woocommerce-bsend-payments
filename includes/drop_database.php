<?php

global $jal_db_version;
$jal_db_version = '1.0';

// Delete table when deactivate
function bsend_remove_database() {
    global $wpdb;
    $table_name = "bsend_transaction";
    $sql = "DROP TABLE IF EXISTS $table_name;";
    $wpdb->query($sql);
    delete_option("my_plugin_db_version");
}  

bsend_remove_database();

