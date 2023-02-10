<?php

function bsend_custum_settings() {

    // If plugin settings don't exist, then create them
    if( false == get_option( 'bsend_general_gpt' ) ) {
      add_option( 'bsend_general_gpt' );
  }
   add_settings_section( 'setting_general',  'General' , 'settings_callback', 'bsend_general_gpt_content');
  // input Field
      add_settings_field( 'setting_temperature', 'Temperature' , 'temperature_callback','bsend_general_gpt_content','setting_general');
      add_settings_field('setting_max_tokens',  'Max Tokens','max_tokens_callback','bsend_general_gpt_content','setting_general',);
      add_settings_field('setting_top_p','Top P','top_p_callback','bsend_general_gpt_content','setting_general');
      add_settings_field( 'setting_best_of','Best Of','best_of_callback','bsend_general_gpt_content','setting_general');
      add_settings_field('setting_api_key','Api Key',  'api_key_callback','bsend_general_gpt_content','setting_general');
    
  
    register_setting(
      'bsend_general_gpt',
      'Tentee_settings_name'
    );
  
  }
  add_action( 'admin_init', 'bsend_custum_settings' );