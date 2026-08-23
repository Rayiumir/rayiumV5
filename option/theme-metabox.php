<?php
/**
 * Initialize the custom Meta Boxes. 
 */
add_action( 'admin_init', 'custom_meta_boxes' );

/**
 * Meta Boxes By Reza Kianoosh From IranThemes.com And Rkianoosh.ir.
 *
 * You can find all the available option types in demo-theme-options.php.
 *
 * @return    void
 * @since     2.0
 */
function custom_meta_boxes() {
  
$kianoosh_box = array();
	
$kianoosh_box = array();
    
if ( function_exists( 'ot_register_meta_box' ) )
    ot_register_meta_box( $kianoosh_box );
}