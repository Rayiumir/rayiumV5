<?php

defined('ABSPATH') || exit;

// function rayium_get_api_key(){
//     return get_option('rayium_option_gateway');
//     //return 'adxcv-zzadq-polkjsad-opp13opoz-1sdf455aadzmck1244567';
// }

// function rayium_bitpay_url($path){
//     return 'https://bitpay.ir/payment/' . $path;
//     //return 'https://bitpay.ir/payment-test/' . $path;
// }
// function rayium_do_payment(){

//     if(
//         isset($_GET['gateway'])
//         &&
//         isset($_GET['register_id'])
//         &&
//         isset($_GET['rayium_nonce'])
//         &&
//         isset($_GET['trans_id'])
//         &&
//         isset($_GET['id_get'])
//     ){

//         $nonce = $_GET['rayium_nonce'];
//         $gateway = sanitize_key( $_GET['gateway'] );
//         $register_id = absint( $_GET['register_id'] );

//         $nonce_action = sprintf(
//             '%s-%d',
//             $gateway,
//             $register_id,
//             get_current_user_id()
//         );

//         if( get_post_type( $register_id ) != 'course_register' ){
//             return;
//         }

//         if( get_post_status( $register_id ) != 'pending' ){
//             return;
//         }

//         if( get_the_excerpt( $register_id ) != $nonce ){
//             return;
//         }

//         $id_get = sanitize_text_field( $_GET['id_get'] );
//         if($id_get != get_post_meta($register_id, '_bitpay_payment_id', true)){
//             return;
//         }

//         $trans_id = absint( $_GET['trans_id'] );
//         if($trans_id){
//             update_post_meta( $register_id, '_bitpay_trans_id', $trans_id );
//         }

//         // $card_number = isset( $_POST['cardNum'] ) ? sanitize_text_field( $_POST['cardNum'] ) : '';
//         // if($card_number){
//         //     update_post_meta( $register_id, '_bitpay_card_number', $card_number );
//         // }

//         $register_request = get_post( $register_id );

//         $data = array(
//             'api' => rayium_get_api_key(),
//             'trans_id' => $trans_id,
//             'id_get' => $id_get,
//             'json' => 1,
//         );

//         $verify_request = wp_remote_post(
//             rayium_bitpay_url('gateway-result-second'),
//             [
//                 'body' => $data
//             ]  
//         );


//         $error_message = '';


//         if(! is_wp_error( $verify_request ) && wp_remote_retrieve_response_code( $verify_request ) == 200){

//             $result = json_decode(wp_remote_retrieve_body( $verify_request ));
            
//             if(isset($result->status)){

//                 if($result->status != 1 && $result->status != 11){
//                     $error_message = rayium_get_bitpay_error_message($result->status);
//                     rayium_fail_register($register_id, $error_message);
//                     wp_safe_redirect(get_the_permalink($register_request->post_parent) . '?rayium_payment_error=true');
//                     exit;
//                     return;
//                 }

//                 if($result->amount != $register_request->menu_order){
//                     $error_message = 'قیمت نامعتبر است';
//                     rayium_fail_register($register_id, $error_message);
//                     wp_safe_redirect(get_the_permalink($register_request->post_parent) . '?rayium_payment_error=true');
//                     exit;
//                     return;
//                 }

//                 if($result->factorId != $register_id){
//                     $error_message = 'شناسه فاکتور نامعتبر است';
//                     rayium_fail_register($register_id, $error_message);
//                     wp_safe_redirect(get_the_permalink($register_request->post_parent)  . '?rayium_payment_error=true');
//                     exit;
//                     return;
//                 }

//                 rayium_complete_register($register_id);
//                 wp_safe_redirect(get_the_permalink( $register_request->post_parent ));
//                 exit;
//             }
//         }
//     }

//     if(
//         isset($_GET['gateway']) 
//         && 
//         isset($_GET['register_course_id']) 
//         && 
//         isset($_GET['action']) 
//         && 
//         $_GET['action'] == 'rayium_payment'
//         &&
//         is_user_logged_in()
//     )
//     {
//         $course_id = absint( $_GET['register_course_id'] );
//         $gateway = sanitize_key( $_GET['gateway'] );

//         if(get_post_type( $course_id ) != 'course' || get_post_status($course_id) != 'publish'){
//             wp_die('دوره نامعتبر است');
//         }

//         $final_price = rayium_get_course_final_price($course_id);

//         $register_id = rayium_create_course_register(get_current_user_id(), $course_id, $gateway);

//         $nonce_action = sprintf(
//             '%s-%d',
//             $gateway,
//             $register_id
//         );

//         $nonce = get_the_excerpt( $register_id );
        
//         $data = array(
//             'api' => rayium_get_api_key(),
//             'amount' => $final_price,
//             'redirect' => get_the_permalink($course_id) . sprintf(
//                 '?gateway=%s&action=rayium_verify&register_id=%d&rayium_nonce=%s', 
//                 $gateway, 
//                 $register_id,
//                 $nonce
//             ),
//             'factorId' => intval($register_id)
//         );

//         $payment_request = wp_remote_post(
//             rayium_bitpay_url('gateway-send'),
//             [
//                 'body' => $data
//             ]  
//         );


//         if(! is_wp_error( $payment_request ) && wp_remote_retrieve_response_code( $payment_request ) == 200){

//             $payment_id = intval(wp_remote_retrieve_body( $payment_request ));
            
//             if($payment_id < 0){
//                 $error_message = rayium_get_bitpay_error_message($payment_id);
//                 rayium_fail_register($register_id, $error_message);
//                 wp_safe_redirect(get_the_permalink($course_id)  . '?rayium_payment_error=true');
//                     exit;
//             }else{

//                 update_post_meta( $register_id, '_bitpay_payment_id', $payment_id);
                
//                 $payment_url = sprintf(
//                     rayium_bitpay_url('gateway-%d-get'),
//                     $payment_id
//                 );

//                 wp_redirect($payment_url);
//                 exit;
//             }
//         }

//     }

// }
// add_action( 'init', 'rayium_do_payment' );

function rayium_get_api_key(){
    return get_option('rayium_option_gateway');
    //return 'zibal merchant api key';
}

function rayium_zibal_url($path){
    return 'https://gateway.zibal.ir/v1/' . $path;
    //return 'https://sandbox.zibal.ir/v1/' . $path; // برای تست
}

function rayium_do_payment(){

    // بخش تایید پرداخت - اصلاح شده
    if(
        isset($_GET['action']) 
        && 
        $_GET['action'] == 'rayium_verify'
        &&
        isset($_GET['gateway'])
        &&
        isset($_GET['register_id'])
        &&
        isset($_GET['rayium_nonce'])
    ){

        $nonce = $_GET['rayium_nonce'];
        $gateway = sanitize_key( $_GET['gateway'] );
        $register_id = absint( $_GET['register_id'] );

        if( get_post_type( $register_id ) != 'course_register' ){
            wp_die('نوع ثبت‌نام نامعتبر است');
        }

        if( get_post_status( $register_id ) != 'pending' ){
            wp_die('وضعیت ثبت‌نام نامعتبر است');
        }

        if( get_the_excerpt( $register_id ) != $nonce ){
            wp_die('کد امنیتی نامعتبر است');
        }

        $success = isset( $_GET['success'] ) ? absint( $_GET['success'] ) : 0;
        $status = isset( $_GET['status'] ) ? absint( $_GET['status'] ) : 0;
        $trackId = isset( $_GET['trackId'] ) ? sanitize_text_field( $_GET['trackId'] ) : '';

        // ذخیره اطلاعات دریافتی
        if($trackId){
            update_post_meta( $register_id, '_zibal_track_id', $trackId );
        }
        if($success){
            update_post_meta( $register_id, '_zibal_success', $success );
        }
        if($status){
            update_post_meta( $register_id, '_zibal_status', $status );
        }

        $register_request = get_post( $register_id );
        $course_id = $register_request->post_parent;

        // اگر پرداخت لغو شده باشد
        if($status == -1 || $success == 0){
            $error_message = 'پرداخت توسط کاربر لغو شد';
            rayium_fail_register($register_id, $error_message);
            wp_safe_redirect(get_the_permalink($course_id) . '?rayium_payment_error=true&message=' . urlencode($error_message));
            exit;
        }

        // بررسی trackId ذخیره شده
        $saved_trackId = get_post_meta($register_id, '_zibal_track_id', true);
        if(empty($saved_trackId) || $saved_trackId != $trackId){
            wp_die('شناسه تراکنش نامعتبر است');
        }

        // تایید پرداخت در زیبال
        $data = array(
            'merchant' => rayium_get_api_key(),
            'trackId' => $trackId,
        );

        $verify_request = wp_remote_post(
            rayium_zibal_url('verify'),
            [
                'body' => json_encode($data),
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ),
                'timeout' => 30
            ]  
        );

        $error_message = '';

        if(! is_wp_error( $verify_request ) && wp_remote_retrieve_response_code( $verify_request ) == 200){
            
            $result = json_decode(wp_remote_retrieve_body( $verify_request ));
            
            if(isset($result->result)){
                
                // لاگ نتیجه برای دیباگ
                update_post_meta( $register_id, '_zibal_verify_result', json_encode($result) );

                // بررسی خطاهای زیبال
                if($result->result != 100){
                    $error_message = rayium_get_zibal_error_message($result->result);
                    rayium_fail_register($register_id, $error_message);
                    wp_safe_redirect(get_the_permalink($course_id) . '?rayium_payment_error=true&message=' . urlencode($error_message));
                    exit;
                }

                // بررسی مبلغ
                if($result->amount != $register_request->menu_order){
                    $error_message = 'قیمت نامعتبر است. مبلغ پرداختی: ' . $result->amount . ' - مبلغ دوره: ' . $register_request->menu_order;
                    rayium_fail_register($register_id, $error_message);
                    wp_safe_redirect(get_the_permalink($course_id) . '?rayium_payment_error=true&message=' . urlencode($error_message));
                    exit;
                }

                // بررسی شناسه فاکتور
                if(isset($result->factorNumber) && $result->factorNumber != (string)$register_id){
                    $error_message = 'شناسه فاکتور نامعتبر است';
                    rayium_fail_register($register_id, $error_message);
                    wp_safe_redirect(get_the_permalink($course_id) . '?rayium_payment_error=true&message=' . urlencode($error_message));
                    exit;
                }

                // اگر همه چیز درست بود، ثبت‌نام را تکمیل کن
                rayium_complete_register($register_id);
                
                // ریدایرکت به صفحه دوره با پیام موفقیت
                wp_safe_redirect(get_the_permalink($course_id) . '?payment=success');
                exit;
                
            } else {
                $error_message = 'پاسخ نامعتبر از درگاه پرداخت';
                rayium_fail_register($register_id, $error_message);
                wp_safe_redirect(get_the_permalink($course_id) . '?rayium_payment_error=true&message=' . urlencode($error_message));
                exit;
            }
            
        } else {
            // خطا در ارتباط با سرور زیبال
            $error_message = 'خطا در ارتباط با درگاه پرداخت';
            if(is_wp_error($verify_request)){
                $error_message .= ' - ' . $verify_request->get_error_message();
            }
            rayium_fail_register($register_id, $error_message);
            wp_safe_redirect(get_the_permalink($course_id) . '?rayium_payment_error=true&message=' . urlencode($error_message));
            exit;
        }

    }

    // بخش ایجاد تراکنش جدید - اصلاح شده
    if(
        isset($_GET['gateway']) 
        && 
        isset($_GET['register_course_id']) 
        && 
        isset($_GET['action']) 
        && 
        $_GET['action'] == 'rayium_payment'
        &&
        is_user_logged_in()
    )
    {
        $course_id = absint( $_GET['register_course_id'] );
        $gateway = sanitize_key( $_GET['gateway'] );

        if(get_post_type( $course_id ) != 'course' || get_post_status($course_id) != 'publish'){
            wp_die('دوره نامعتبر است');
        }

        $final_price = rayium_get_course_final_price($course_id);
        
        // بررسی قیمت
        if($final_price <= 0){
            wp_die('قیمت دوره نامعتبر است');
        }

        // ایجاد ثبت‌نام
        $register_id = rayium_create_course_register(get_current_user_id(), $course_id, $gateway);
        
        if(is_wp_error($register_id)){
            wp_die('خطا در ایجاد ثبت‌نام: ' . $register_id->get_error_message());
        }

        $nonce = get_the_excerpt( $register_id );
        
        // ساخت callback URL
        $callback_url = add_query_arg(array(
            'gateway' => $gateway,
            'action' => 'rayium_verify',
            'register_id' => $register_id,
            'rayium_nonce' => $nonce
        ), get_the_permalink($course_id));
        
        $data = array(
            'merchant' => rayium_get_api_key(),
            'amount' => $final_price,
            'callbackUrl' => $callback_url,
            'description' => 'پرداخت دوره آموزشی: ' . get_the_title($course_id),
            'orderId' => (string)$register_id,
            'mobile' => '', // می‌توانید شماره کاربر را از پروفایل بگیرید
        );

        $payment_request = wp_remote_post(
            rayium_zibal_url('request'),
            [
                'body' => json_encode($data),
                'headers' => array(
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json'
                ),
                'timeout' => 30
            ]  
        );

        if(! is_wp_error( $payment_request ) && wp_remote_retrieve_response_code( $payment_request ) == 200){
            
            $response = json_decode(wp_remote_retrieve_body( $payment_request ));
            
            // لاگ پاسخ برای دیباگ
            update_post_meta( $register_id, '_zibal_request_response', json_encode($response) );
            
            if($response->result != 100){
                $error_message = rayium_get_zibal_error_message($response->result);
                rayium_fail_register($register_id, $error_message);
                wp_safe_redirect(get_the_permalink($course_id) . '?rayium_payment_error=true&message=' . urlencode($error_message));
                exit;
            }else{
                $trackId = $response->trackId;
                update_post_meta( $register_id, '_zibal_track_id', $trackId);
                
                // ریدایرکت به درگاه زیبال
                $payment_url = sprintf(
                    'https://gateway.zibal.ir/start/%s',
                    $trackId
                );
                // برای تست: 'https://sandbox.zibal.ir/start/%s'

                wp_redirect($payment_url);
                exit;
            }
        } else {
            // خطا در ارتباط با زیبال
            $error_message = 'خطا در ارتباط با درگاه پرداخت';
            if(is_wp_error($payment_request)){
                $error_message .= ' - ' . $payment_request->get_error_message();
            }
            rayium_fail_register($register_id, $error_message);
            wp_safe_redirect(get_the_permalink($course_id) . '?rayium_payment_error=true&message=' . urlencode($error_message));
            exit;
        }

    }

}
add_action( 'init', 'rayium_do_payment' );

// تابع جدید برای دریافت پیام خطاهای زیبال
function rayium_get_zibal_error_message($error_code){
    $messages = array(
        -1 => 'در انتظار پرداخت',
        -2 => 'خطای داخلی',
        1 => 'پرداخت شده - تاییدشده',
        2 => 'پرداخت شده - تاییدنشده',
        3 => 'لغوشده توسط کاربر',
        4 => 'شماره کارت نامعتبر است',
        5 => 'موجودی حساب کافی نیست',
        6 => 'رمز واردشده اشتباه است',
        7 => 'تعداد درخواست‌ها بیش از حد مجاز است',
        8 => 'تعداد پرداخت اینترنتی روزانه بیش از حد مجاز است',
        9 => 'مبلغ پرداخت اینترنتی روزانه بیش از حد مجاز است',
        10 => 'صادرکننده‌ی کارت نامعتبر است',
        11 => 'خطای سوییچ',
        12 => 'کارت قابل دسترسی نیست',
        13 => 'مبلغ تراکنش اصلاح شود',
        14 => 'خطا در شماره تراکنش',
        15 => 'خطا در شماره پیگیری',
        16 => 'خطا در پارامترهای ورودی',
        17 => 'نام کاربری پذیرنده اشتباه است',
        18 => 'رمز پذیرنده اشتباه است',
        19 => 'پذیرنده فعال نیست',
        20 => 'پذیرنده اجازه دسترسی به این تراکنش را ندارد',
        21 => 'آدرس آیپی پذیرنده نامعتبر است',
        22 => 'مبلغ باید بیش از ۱۰۰ ریال باشد',
        23 => 'درخواست تکراری است',
        24 => 'پذیرنده نامعتبر است',
        25 => 'پذیرنده نامعتبر است',
        27 => 'کد پرداخت نامعتبر است',
        28 => 'مبلغ نامعتبر است',
        29 => 'پذیرنده نامعتبر است',
        31 => 'پذیرنده نامعتبر است',
        32 => 'پذیرنده نامعتبر است',
        33 => 'مبلغ واریزی با مبلغ تراکنش مطابقت ندارد',
        34 => 'تراکنش قبلا واریز شده است',
        35 => 'پذیرنده نامعتبر است',
        36 => 'پذیرنده نامعتبر است',
        37 => 'شماره تراکنش نامعتبر است',
        38 => 'رمز عبور نامعتبر است',
        49 => 'پذیرنده نامعتبر است',
        50 => 'مبلغ تراکنش کمتر از حداقل مجاز است',
        51 => 'مبلغ تراکنش بیشتر از حداکثر مجاز است',
        99 => 'پذیرنده نامعتبر است',
        100 => 'با موفقیت تایید شد',
        101 => 'تراکنش قبلا تایید شده است',
        102 => 'تراکنش قبلا وریفای شده است',
        103 => 'تراکنش در انتظار تایید است',
        104 => 'تراکنش در انتظار واریز است',
        201 => 'قبض تایید شد',
        202 => 'شناسه قبض نادرست است',
        203 => 'مبلغ نادرست است',
        204 => 'قبض پرداخت شده است',
        205 => 'خطای پایانه',
        206 => 'پذیرنده قبض معتبر نیست',
        207 => 'شناسه پرداخت نادرست است',
        208 => 'زمان مجاز برای پرداخت به پایان رسیده است',
        209 => 'مبلغ قابل پرداخت نیست',
        210 => 'قبض برگشت خورده است',
        211 => 'خطای نامشخص'
    );
    
    return isset($messages[$error_code]) ? $messages[$error_code] : 'خطای نامشخص';
}



