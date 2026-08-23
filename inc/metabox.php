<?php

defined('ABSPATH') || exit;

function Rayium_course_fields() {

    // برای اطلاعات دوره 

    add_meta_box( 
        'Rayium_course_field', 
        'اطلاعات دوره', 
        'Rayium_course_fields_callback', 
        'course', 
        'normal', 
        'high'
    );

    // برای لیست پخش ویدئو 

    add_meta_box( 
        'Rayium_course_playlist', 
        'لیست پخش دوره', 
        'Rayium_course_fields_playlist', 
        'course', 
        'normal', 
        'high'
    );

    // برای اطلاعات ثبت نام دوره

    add_meta_box( 
        'Rayium_course_register', 
        'اطلاعات ثبت نام دوره', 
        'Rayium_course_register_data', 
        'course_register', 
        'normal', 
        'high'
    );

    // برای اطلاعات دوره

    add_meta_box(
        'Rayium_text_metabox',
        'اطلاعات دوره',
        'Rayium_info_metabox',
        'course',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'Rayium_course_fields' );

// برای اطلاعات دوره

function Rayium_course_fields_callback($post){
    wp_nonce_field( basename( __FILE__ ), 'rayium_security_nonce' );

    $price = get_post_meta( $post->ID, 'rayium_price', true );
    $sale_price = get_post_meta( $post->ID, 'rayium_sale_price', true );
    $has_discount = get_post_meta( $post->ID, 'rayium_has_discount', true );
    $expire = get_post_meta( $post->ID, 'rayium_expire', true );
    $teacher_id = get_post_meta( $post->ID, 'rayium_teacter', true );
    $demo = get_post_meta( $post->ID, 'rayium_demo', true );

    include RAYIUM_ADMIN . 'metabox_course_data.php';
}

function rayium_save_course_data( $post_id ){

    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'rayium_security_nonce' ] ) && wp_verify_nonce( $_POST[ 'rayium_security_nonce' ],
            basename( __FILE__ ) ) ) ? 'true' : 'false';

    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }

    if(get_post_type($post_id) != 'course'){
        return;
    }

    global $wpdb;
    
    if(isset($_POST['rayium_course'])){
        $data = $_POST['rayium_course'];
        $demo = sanitize_url( $data['demo'] );
        $teacher_id = absint( $data['teacher'] );
        $teacher = get_user_by( 'ID', $teacher_id );
        if(! $teacher){
            wp_die('مدرس نامتعبر است!');
        }
        $price = absint( $data['price'] );
        $sale_price = absint( $data['sale_price'] );
        $has_discount = isset( $data['has_discount']);
        $expire = sanitize_text_field( $data['expire']);

        update_post_meta( $post_id, 'rayium_price', $price );
        update_post_meta( $post_id, 'rayium_sale_price', $sale_price );
        update_post_meta( $post_id, 'rayium_has_discount', $has_discount );
        update_post_meta( $post_id, 'rayium_expire', $expire );
        update_post_meta( $post_id, 'rayium_teacter', $teacher_id );
        update_post_meta( $post_id, 'rayium_demo', $demo );

        $playlist_items = [];
        $playlist_item_ids = [];
        $size = 0;
        $duration = 0;

        if (isset($_POST['rayium_playlist'])){
            foreach ($_POST['rayium_playlist']['ids'] as $row_index => $item_id){

                $url = sanitize_url($_POST['rayium_playlist']['urls'][$row_index]);
                $sizeVideo = rayium_remote_file_size($url);
                $video_duration = absint( $_POST['rayium_playlist']['durations'][$row_index] );
                $video_height = absint( $_POST['rayium_playlist']['heights'][$row_index] );
                $video_width = absint( $_POST['rayium_playlist']['widths'][$row_index] );

                $mime_type = '';
                $file_info = pathinfo($url);
                if(isset($file_info['extension']) && $file_info['extension'] == 'mp4'){
                    $mime_type = 'video/mp4';
                }

                $playlist_items [] = [
                    'ID' => absint($item_id),
                    'post_type' => 'playlist_items',
                    'post_title' => sanitize_text_field($_POST['rayium_playlist']['titles'][$row_index]),
                    'guid' => $url,
                    'menu_order' => $row_index,
                    'post_parent' => $post_id,
                    'post_mime_type' => $mime_type,
                    'post_status' => in_array($_POST['rayium_playlist']['statuses'][$row_index], ['free', 'premium']) ? $_POST['rayium_playlist']['statuses'][$row_index] : 'premium',
                    'meta_input' => [
                        '_width' => $video_width,
                        '_height' => $video_height,
                        '_duration' => $video_duration,
                        '_size' => $sizeVideo,
                    ]
                ];

                $size+= $sizeVideo;
                $duration+= $video_duration;

                if($item_id){
                    $playlist_item_ids[] = $item_id;
                }
                
            }
            
            update_post_meta( $post_id, '_duration', $duration);
            update_post_meta( $post_id, '_size', $size);

            if (!empty($playlist_item_ids)){
                
                $wpdb->query(
                    $wpdb->prepare(
                        "DELETE FROM $wpdb->posts WHERE post_type = 'playlist_items' AND post_parent = %d AND ID NOT IN (" . implode(',', $playlist_item_ids) . ")", $post_id
                    )
                );
            }

            foreach ($playlist_items as $rows){
                if($rows['ID']){
                    wp_update_post($rows);

                    $wpdb->update(
                        $wpdb->posts,
                        [
                            'guid' => $rows['guid'],
                        ],
                        [
                            'ID' => $rows['ID'],
                        ]
                    );

                }else{
                    wp_insert_post($rows);
                }
            }
        }

    }
}
add_action('save_post', 'rayium_save_course_data');


// برای لیست پخش دوره

function Rayium_course_fields_playlist($post){
    $playlists = new WP_Query([
        'post_type' => 'playlist_items',
        'posts_per_page' => -1,
        'post_status' => ['free', 'premium'],
        'orderby' => 'menu_order',
        'order' => 'asc',
        'post_parent' => $post->ID,
    ]);
    include RAYIUM_ADMIN . 'metabox_course_playlist.php';

    wp_reset_postdata();
}

// برای تاریخ ثبت نام دوره

function Rayium_course_register_data($post){
    include RAYIUM_ADMIN . 'metabox_course_register.php';
}

// برای اطلاعات دوره

function Rayium_info_metabox($post){
    wp_nonce_field( basename( __FILE__ ), 'Rayium_security_nonce' );

    ?>
    <table class="metabox-tb" width="100%">
        <tbody>
            <tr>
                <td>
                    <label for="text2">پیش نیاز</label>
                    <input id="text2" type="text" name="text2" size="60" value="<?php echo get_post_meta($post->ID,'text2',true); ?>">
                </td>
                <td>
                    <label for="text3">نوع دوره</label>
                    <input id="text3" type="text" name="text3" size="60" value="<?php echo get_post_meta($post->ID,'text3',true); ?>">
                </td>
                <td>
                    <label for="text4">پشتیبانی</label>
                    <input id="text4" type="text" name="text4" size="60" value="<?php echo get_post_meta($post->ID,'text4',true); ?>">
                </td>
                <td>
                    <label for="text5">وضعیت دوره</label>
                    <input id="text5" type="text" name="text5" size="60" value="<?php echo get_post_meta($post->ID,'text5',true); ?>">
                </td>
                <td>
                    <label for="text6">پیشرفت دوره</label>
                    <input id="text6" type="text" name="text6" size="60" value="<?php echo get_post_meta($post->ID,'text6',true); ?>">
                </td>
                <td>
                    <label for="text7">سطح دوره</label>
                    <input id="text7" type="text" name="text7" size="60" value="<?php echo get_post_meta($post->ID,'text7',true); ?>">
                </td>
            </tr>
        </tbody>
    </table>
    <style>
        .metabox-tb tbody tr td {
            display: table;
            
        }
        .metabox-tb tbody tr td input{
            width: 100%;
        }
    </style>
    <?php

}

function Rayium_fields_save( $post_id ) {
    $is_autosave = wp_is_post_autosave( $post_id );
    $is_revision = wp_is_post_revision( $post_id );
    $is_valid_nonce = ( isset( $_POST[ 'Rayium_security_nonce' ] ) && wp_verify_nonce( $_POST[ 'Rayium_security_nonce' ],
            basename( __FILE__ ) ) ) ? 'true' : 'false';

    if ( $is_autosave || $is_revision || !$is_valid_nonce ) {
        return;
    }

    if( isset( $_POST[ 'text2' ] ) ) {
        update_post_meta( $post_id, 'text2', $_POST[ 'text2' ] );
    }

    if( isset( $_POST[ 'text3' ] ) ) {
        update_post_meta( $post_id, 'text3', $_POST[ 'text3' ] );
    }

    if( isset( $_POST[ 'text4' ] ) ) {
        update_post_meta( $post_id, 'text4', $_POST[ 'text4' ] );
    }

    if( isset( $_POST[ 'text5' ] ) ) {
        update_post_meta( $post_id, 'text5', $_POST[ 'text5' ] );
    }

    if( isset( $_POST[ 'text6' ] ) ) {
        update_post_meta( $post_id, 'text6', $_POST[ 'text6' ] );
    }

    if( isset( $_POST[ 'text7' ] ) ) {
        update_post_meta( $post_id, 'text7', $_POST[ 'text7' ] );
    }
}
add_action( 'save_post', 'Rayium_fields_save' );