<?php

defined('ABSPATH') || exit;

// function rayium_template_management($template){
    
//     if(is_singular( 'course' )){

//         $tmp = locate_template('rayium/single-course.php');

//         if(! $tmp ){
//             $template = get_template_directory() . 'single-course.php';
//         }else{
//             $template = $tmp;
//         }

//     }elseif(is_post_type_archive( 'course' ) || is_tax('course_cat') || is_tax('course_tag')){

//         $tmp = locate_template('archive-course.php');

//         if(! $tmp ){
//             $template =  get_template_directory() . 'archive-course.php';
//         }else{
//             $template = $tmp;
//         }
//     }
    
    
//     return $template;
// }
// add_filter('template_include', 'rayium_template_management');

function rayium_body_class($classes){
    
    if(is_singular( 'course' ) || is_post_type_archive( 'course' ) || is_tax(['course_cat', 'course_tag'])){
        $theme_class = str_replace('', '-', strtolower(wp_get_theme()->get('Name')));
        if(in_array($theme_class, $classes)){
            $classes[] = $theme_class;
        }
    }
    return $classes;
}
add_filter('body_class', 'rayium_body_class');