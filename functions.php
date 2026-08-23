<?php

define("RAYIUM_URI", get_template_directory_uri());
define("RAYIUM_PATH", get_template_directory() . DIRECTORY_SEPARATOR);
define("RAYIUM_STYLE", get_stylesheet_uri());
define("RAYIUM_INC", RAYIUM_PATH . "inc/");
define("RAYIUM_ADMIN", RAYIUM_INC . "admin/");

require_once RAYIUM_INC . "enqueue.php";
require_once RAYIUM_INC . "function.php";
require_once RAYIUM_INC . "post_type.php";
require_once RAYIUM_INC . "taxonomy.php";
require_once RAYIUM_INC . "templates.php";
require_once RAYIUM_INC . "options.php";
require_once RAYIUM_INC . "functions-register.php";
require_once RAYIUM_INC . "functions-course.php";
require_once RAYIUM_INC . "payments.php";
require_once('pagination.php');
require_once('navbar.php');
require_once('stats.php');
require_once "like.php";
if(is_admin()){
    require_once RAYIUM_INC . "metabox.php";
    require_once RAYIUM_INC . "metaboxblog.php";
}

// Post Thumbnails
if(function_exists("add_theme_support")){
    add_theme_support("post-thumbnails");
}

// View Posts
function set_post_views_field() {
    
    if(is_single()){

        global $post;
        $post_id = $post->ID;
        $count = 1;
        $post_view_count  = get_post_meta($post_id, "post_view_count", true);
        if($post_view_count){
            $count = $post_view_count + 1;
        }
        update_post_meta($post_id, "post_view_count", $count);

    }
}
add_action("wp_head", "set_post_views_field");

function add_post_view_count_column($columns) {
    if(is_array($columns) && !isset($columns['post_view_count'])) {
        $columns['post_view_count'] = 'تعداد بازدیدها';
    }

    return $columns;
}
add_filter("manage_posts_columns", "add_post_view_count_column");
function set_post_view_count_column($column_name, $post_id){
    if($column_name == 'post_view_count'){
        $count = get_post_meta($post_id, "post_view_count", true);
        echo $count ? $count : 0;
    }
}
add_filter('manage_posts_custom_columns', 'set_post_view_count_column', 10, 2);

function get_post_view_count($post_id){
    $count = get_post_meta($post_id, "post_view_count", true);
    return $count ? $count : 0;
}

// Widgets
function Rayium_widgets(){
    register_sidebar(
        array(
            'name' => 'ابزارک سمت چپ',
            'id' => 'sidebar',
            'description' => 'محل قرار گیری ابزارک های آماده',
            'before_widget' => '<div class="card rounded-3 mb-3"><div class="card-body">',
            'after_widget' => '</div></div>',
            'before_title' => '<h5><i class="fa-duotone fa-circle-dot ms-2"></i>',
            'after_title' => '</h5>'
        )
    );
}
add_action('widgets_init', 'Rayium_widgets');

// Remove jQuery Migrate
function remove_jquery_migrate( $scripts ) {
    if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
        $script = $scripts->registered['jquery'];
        if ( $script->deps ) {
            $script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
        }
    }
}
add_action( 'wp_default_scripts', 'remove_jquery_migrate' );