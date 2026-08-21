<?php

define("RAYIUM_URI", get_template_directory_uri());
define("RAYIUM_PATH", get_template_directory() . DIRECTORY_SEPARATOR);
define("RAYIUM_STYLE", get_stylesheet_uri());
define("RAYIUM_INC", RAYIUM_PATH . "inc/");
define("RAYIUM_ADMIN", RAYIUM_INC . "admin/");

require_once RAYIUM_INC . "enqueue.php";
require_once "like.php";

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