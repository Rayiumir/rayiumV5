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
        'style', 
        RAYIUM_STYLE,
        '5.0.0'
    );


};
function Rayium_scripts(){

    $deps = ['jquery'];

    wp_enqueue_script(
        'main', 
        RAYIUM_URI . '/js/main.js',
        $deps,
        '5.0.0'
    );

};