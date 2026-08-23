<?php 
defined('ABSPATH') || exit; 

function rayium_get_course_final_price($course_id){

    $price = get_post_meta( $course_id, 'rayium_price', true );
    $sale_price = get_post_meta( $course_id, 'rayium_sale_price', true );
    $has_discount = get_post_meta( $course_id, 'rayium_has_discount', true );
    $expire = get_post_meta( $course_id, 'rayium_expire', true );

    $final_price = $price;
    if($has_discount && $sale_price && $sale_price < $price){
        if($expire){
            if(strtotime($expire) > current_time( 'timestamp' )){
                $final_price = $sale_price;
            }
        }else{
            $final_price = $sale_price;
        }
    }
    
    return $final_price;
}

function rayium_get_course_price($course_id){
    return get_post_meta( $course_id, 'rayium_price', true );
}

function rayium_get_final_discount_percent($course_id){
    $course_price = rayium_get_course_price($course_id);
    
    // Prevent division by zero
    if (!$course_price || $course_price <= 0) {
        return 0;
    }
    
    return 100 - round(rayium_get_course_final_price($course_id) / $course_price * 100);
}

