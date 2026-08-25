<?php

defined('ABSPATH') || exit;

define("RAYIUM_VERSION", "5.0.0");
define("RAYIUM_ASSETS_VERSION", defined('WP_DEBUG') && WP_DEBUG ? time() : RAYIUM_VERSION);

add_action('wp_head', 'Rayium_styles', 1);
add_action('wp_footer', 'Rayium_scripts');

function Rayium_styles(){

    wp_enqueue_style(
        'tani', 
        RAYIUM_URI . '/css/tani.css',
        '2.0.0'
    );

    wp_enqueue_style(
        'bootstrap-grid', 
        RAYIUM_URI . '/css/bootstrap-grid.min.css',
        '5.3.0'
    );

    wp_enqueue_style(
        'font-awesome', 
        RAYIUM_URI . '/css/all.min.css',
        '5.3.0'
    );

    wp_enqueue_style(
        'like', 
        RAYIUM_URI . '/css/like.css',
        '1.0.0'
    );


    wp_enqueue_style(
        'style', 
        RAYIUM_STYLE,
        RAYIUM_ASSETS_VERSION
    );


};
function Rayium_scripts(){

    $deps = ['jquery'];

    wp_enqueue_script(
        'main', 
        RAYIUM_URI . '/js/main.js',
        $deps,
        RAYIUM_ASSETS_VERSION
    );

    wp_enqueue_script(
        'like',
        RAYIUM_URI . '/js/like.js',
        $deps,
        RAYIUM_ASSETS_VERSION
    );

    wp_localize_script( 'like', 'ajax_var', array(
        'url'   => admin_url( 'admin-ajax.php' ),
        'nonce' => wp_create_nonce( 'ajax-nonce' )
    ) );

};

function Course_scripts($hook): void {
    
     global $post;

     if($hook == 'post-new.php' || $hook == 'post.php'){
         if('course' === $post->post_type){
             wp_enqueue_media();

			 // JavaScript

	         wp_register_script(
		         'select2',
		         RAYIUM_URI . '/js/select2.min.js',
		         [],
		         '4.1.0',
		         true
	         );

	         wp_register_script(
		         'jalali-datepicker',
		         RAYIUM_URI . '/js/jalali-datepicker.min.js',
		         [],
		         '0.9.3',
		         true
	         );

	         wp_register_script(
		         'jalali-moment',
		         RAYIUM_URI . '/js/jalali-moment.browser.js',
		         [],
		         '3.3.11',
		         true
	         );

	         $js_deps = [
		         'jquery',
		         'select2',
		         'jalali-datepicker',
		         'jalali-moment',
		         'jquery-ui-core',
		         'jquery-ui-sortable'
	         ];

	         wp_enqueue_script(
		         'rayium_admin_js',
		         RAYIUM_URI . '/js/admin.js',
		         $js_deps,
		         RAYIUM_ASSETS_VERSION,
		         true
	         );

	         $admin_localized_data = [
		         'i18n' => [
			         'video_uploader_title' => 'آپلود ویدئو پیش نمایش',
			         'sure_delete' => 'آیا در حذف این مورد مطمئن هستید؟'
		         ]
	         ];

	         wp_localize_script( 'rayium_admin_js', 'rayium', $admin_localized_data );

			 // Styles

	         wp_register_style(
		         'select2',
		         RAYIUM_URI . '/css/select2.min.css',
		         [],
		         '4.1.0'
	         );

	         wp_register_style(
		         'jalali-datepicker',
		         RAYIUM_URI . '/css/jalali-datepicker.min.css',
		         [],
		         '0.9.3'
	         );

	         $css_deps = [
		         'select2',
		         'jalali-datepicker'
	         ];

	         wp_enqueue_style(
		         'rayium_admin_css',
		         RAYIUM_URI . '/css/admin.css',
		         $css_deps,
		         RAYIUM_ASSETS_VERSION
	         );

         }

     }
     
    wp_enqueue_style(
        'rayium_stats_css',
        RAYIUM_URI . '/css/stats.css',
        RAYIUM_ASSETS_VERSION
    );
 }
 add_action('admin_enqueue_scripts', 'Course_scripts');

