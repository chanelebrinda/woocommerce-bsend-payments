<?php 


	add_action( 'admin_enqueue_scripts','bsend_enqueue_script' , 100 );
    add_action( 'admin_enqueue_scripts', 'bsend__enqueue_style' , 100 );
	
   function bsend__enqueue_style(){
    
      wp_enqueue_style(
        'bsend-style',
        'https://unpkg.com/bootstrap-table@1.21.2/dist/bootstrap-table.min.css', 
        [],
        time()
      );
      wp_enqueue_style(
        'bsend-style',
        "https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css"
        , 
        [],
        time()
      );
      wp_enqueue_style(
        'bsend-style',
        "https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css", 
        [],
        time()
      ); 
         
      wp_enqueue_style( 
        'bsend_bootstrap_css', 
        'https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css',
          false,
        NULL,
        'all' 
      );
      

  }
  

 function bsend_enqueue_script() {

      // wp_enqueue_script(
      //   'bsend-bundle_rounded',
      //    plugin_dir_url( __FILE__ ) . 'assets/js/bootstrap.bundle.min.js',
      //   ['jquery'],
      //   time()
      // );
      wp_enqueue_script(
        'poppccer_js',
        'https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js',
        array('jquery'),
        NULL, 
        true 
      );


      wp_enqueue_script( 
        'bsend-bootstrap-js',
        'https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js',
          array('jquery'),
        NULL, 
        true 
      );
 
    wp_enqueue_script(
      'bsend-admin-datable',
      "https://unpkg.com/bootstrap-table@1.21.2/dist/bootstrap-table.min.js",
      ['jquery'],
      time()
    );
    wp_enqueue_script(
      'bsend-admin-datable',
      "https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js",
      ['jquery'],
      time()
    );

      wp_enqueue_script(
        'bsend-admin-export',
        "https://unpkg.com/tableexport.jquery.plugin/tableExport.min.js",
        ['jquery'],
        time()
      ); 
      wp_enqueue_script(
        'bsend-admin-export',
        "https://unpkg.com/bootstrap-table@1.21.2/dist/bootstrap-table-locale-all.min.js",
        ['jquery'],
        time()
      );
 
  }
    




