<?php 
defined('ABSPATH') || exit; 

function rayium_is_stadent($course_id, $user_id){

    $register_query = new WP_Query([
        'author' => $user_id,
        'post_parent' => $course_id,
        'post_type' => 'course_register',
        'post_status' => 'publish',
    ]);
    return $register_query->have_posts();

}

function rayium_get_student_count($course_id){
    
    $register_query = new WP_Query([
        'post_parent' => $course_id,
        'post_status' => 'publish',
        'post_type' => 'course_register'
    ]);
    return $register_query->found_posts;

}

function rayium_get_students(){
    global $wpdb;

    $student_ids = $wpdb->get_col("SELECT DISTINCT post_author FROM {$wpdb->posts} WHERE post_type='course_register'");

    if(! $student_ids ){
        $student_ids = [];
    }

    return get_users(['include' => $student_ids]);
}

function rayium_get_course_sales($course_id){
    global $wpdb;

    $sql = $wpdb->prepare("SELECT SUM(menu_order) FROM {$wpdb->posts} WHERE post_type='course_register' AND post_status='publish' AND post_parent = %d"
    , $course_id
    );
    return intval(
        $wpdb->get_var($sql)
    );
}

function rayium_create_course_register($user_id, $course_id, $gateway){

    if(get_post_type( $course_id ) !== 'course' ){
        return new WP_Error('invalid_course', 'دوره نامتعبر است');
    }

    if(! get_user_by( 'ID', $user_id ) ){
        return new WP_Error('invalid_user', 'کاربر نامتعبر است');
    }

    if(get_post_status( $course_id ) !== 'publish' ){
        return new WP_Error('not_published', 'دوره منتشر نمی شود');
    }

    $price = rayium_get_course_price($course_id);
    $final_price = rayium_get_course_final_price($course_id);
    $percent = rayium_get_final_discount_percent($course_id);

    $title = '#' . $course_id;

    $register_data = [
        'post_title' => $title,
        'post_content' => $title,
        'post_author' => $user_id,
        'post_parent' => $course_id,
        'menu_order' => $final_price,
        'post_type' => 'course_register',
        'post_excerpt' => rayium_make_register_hash(),
        'post_status' => 'pending',
        'meta_input' => [
            '_price' => $price,
            '_discount_price' => $percent,
            '_gateway' => $gateway,
        ]

    ];

    return wp_insert_post( $register_data, true );

    

}

function rayium_make_hash($count = 40){
    $alphabet = 'qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM1234567890';
    $str = '';
    for($i = 0; $i < $count; $i++){
        $str.= str_shuffle($alphabet)[0];
    }
    return $str;
}

function rayium_make_register_hash(){
    global $wpdb;
    while(true){
        $hash = rayium_make_hash();
        $exists = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT ID FROM {$wpdb->posts} WHERE post_excerpt = %s", $hash
            )
        );
        if(! $exists ){
            return $hash;
        }
    }
}

function rayium_complete_register($course_id){
    return wp_update_post([
        'ID' => $course_id, 
        'post_status' => 'publish',
    ], true);
}

function rayium_fail_register($course_id, $error_message){
    return wp_update_post([
        'ID' => $course_id, 
        'post_status' => 'failed',
        'meta_input' => [
            '_error_message' => $error_message,
        ]
    ]);
}

// function rayium_get_bitpay_error_message($error_code)
// {
//     $messages = [
//         -1 => 'APIارسالی با نوعAPIتعریف شده درbitpay
//         سازگار نیست',
//         -2 => 'trans_idشده ارسال،عددي داده
//         نمیباشد',
//         -3 => 'id_getشده ارسال،عددي داده
//         نمیباشد',
//         -4 => 'تراکنشی در پایگاه وجود ندارد و یا
//         موفقیت آمیز نبودهاست',
//         1 => 'تراکنش موفقیت آمیز بوده است',
//         11 => 'تراکنش از قبل تاییده شده است',
//     ];

//     return isset($messages[$error_code]) ? $messages[$error_code] : 'خطای ناشناخته';
// }