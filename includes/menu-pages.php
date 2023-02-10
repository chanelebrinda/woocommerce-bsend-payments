<?php


 /**
 * Add the custom page in a specific menu 
 * @since 1.0
 */
   function bsend_add_admin_pages(){

        add_menu_page('bsend_gpt','Bsend Payement','manage_options','bsend_plugin',
          'bsend_menu_markup','dashicons-calendar-alt',30);

        add_submenu_page('bsend_plugin', 'Transaction','transaction' ,'manage_options',
        'bsend_transaction', 'bsend_transaction_markup',0);
    
        add_submenu_page('bsend_plugin','Settings','Settings' ,'manage_options',
        'bsend_settings', 'bsend_setting_markup',1);

   }
	  
  add_action( 'admin_menu','bsend_add_admin_pages' );

   /**
 * Add content for the transaction page 
 * @since 1.0
 */
function bsend_transaction_markup(){
  include( plugin_dir_path( __FILE__ ) . 'listing.php');



}


 /**
 * Add content for the settings page 
 * @since 1.0
 */
  function bsend_menu_markup(){

    // $this->options_general = get_option( 'vaajo_general' );
    // $this->options_social = get_option( 'vaajo_social' );
    // $this->options_footer = get_option( 'vaajo_footer' ); 

    $social_Screen = ( isset( $_GET['action'] ) && 'social' == $_GET['action'] ) ? true : false;
    $footer_Screen = ( isset( $_GET['action'] ) && 'footer' == $_GET['action'] ) ? true : false; 
      
      ?>
         <div class="wrap">
        <h1><?php echo get_admin_page_title() ?></h1>
         <h2 class="nav-tab-wrapper">
            <a href="<?php echo admin_url( 'admin.php?page=bsend_gpt_settings' ); ?>" class="nav-tab<?php if ( ! isset( $_GET['action'] ) || isset( $_GET['action'] ) && 'social' != $_GET['action'] && 'footer' != $_GET['action'] ) echo ' nav-tab-active'; ?>"><?php esc_html_e( 'General' ); ?></a>
            <a href="<?php echo esc_url( add_query_arg( array( 'action' => 'social' ), admin_url( 'admin.php?page=bsend_gpt_settings' ) ) ); ?>" class="nav-tab<?php if ( $social_Screen ) echo ' nav-tab-active'; ?>"><?php esc_html_e( 'Social' ); ?></a> 
            <a href="<?php echo esc_url( add_query_arg( array( 'action' => 'footer' ), admin_url( 'admin.php?page=bsend_gpt_settings' ) ) ); ?>" class="nav-tab<?php if ( $footer_Screen ) echo ' nav-tab-active'; ?>"><?php esc_html_e( 'Footer' ); ?></a> 
         </h2>
          
        <form method="post" action="options.php">
          <?php

              if($social_Screen) { 

                settings_fields( 'bsend_general_gpt' ); 
                do_settings_sections( 'bsend_general_gpt_content' ); 
                submit_button(); 

              } elseif($footer_Screen) {

                settings_fields( 'bsend_woocommerce_gpt' ); 
                do_settings_sections( 'bsend_woocommerce_gpt_content' ); 
                submit_button(); 

              }else { 

                settings_fields( 'bsend_seo_gpt' ); 
                do_settings_sections( 'bsend_seo_gpt_content' ); 
                submit_button(); 

              }

            
          ?>
        </form>
      </div>
    <?php
  }
