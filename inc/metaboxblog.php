<?php
function Rayium_fields(){
    add_meta_box('rayium_fields', 'اطلاعات دانلود', 'rayium_fields_callback', 'post', 'normal', 'high');
}
add_action('add_meta_boxes', 'Rayium_fields');
function rayium_fields_callback($post){
    wp_nonce_field(basename(__FILE__), 'rayium_security_nonce');
    ?>
    <table class="metabox-tb" width="100%">
        <tbody>
        <tr>
			<td>
                <label for="wordpress">مخزن وردپرس</label>
                <input id="wordpress" type="text" name="wordpress" size="60" value="<?php echo get_post_meta($post->ID,'wordpress',true); ?>">
            </td>
            <td>
                <label for="github">گیت هاب</label>
                <input id="github" type="text" name="github" size="60" value="<?php echo get_post_meta($post->ID,'github',true); ?>">
            </td>
            <td>
                <label for="download">دانلود</label>
                <input id="download" type="text" name="download" size="60" value="<?php echo get_post_meta($post->ID,'download',true); ?>">
            </td>
            <td>
                <label for="eyes">پیش نمایش</label>
                <input id="eyes" type="text" name="eyes" size="60" value="<?php echo get_post_meta($post->ID,'eyes',true); ?>">
            </td>
            <td>
                <label for="links">منابع</label>
                <input id="links" type="text" name="links" size="60" value="<?php echo get_post_meta($post->ID,'links',true); ?>">
            </td>
        </tr>
        </tbody>
    </table>
    <style>
        .metabox-tb tbody tr td {
            display: table;
        }
    </style>
    <?php
}

function rayium_fields_save($post_id){ 
    $is_autosave = wp_is_post_autosave($post_id);
    $is_revision = wp_is_post_revision($post_id);
    $is_valid_nonce = (isset($_POST['rayium_security_nonce']) && wp_verify_nonce($_POST['rayium_security_nonce'], basename(__FILE__))) ? 'true' : 'false';

    if($is_autosave || $is_revision || !$is_valid_nonce){
        return;
    }

    if(isset($_POST['wordpress'])){
        update_post_meta($post_id, 'wordpress', $_POST['wordpress']);
    }

    if(isset($_POST['github'])){
        update_post_meta($post_id, 'github', $_POST['github']);
    }

    if(isset($_POST['download'])){
        update_post_meta($post_id, 'download', $_POST['download']);
    }

    if(isset($_POST['eyes'])){
        update_post_meta($post_id, 'eyes', $_POST['eyes']);
    }

    if(isset($_POST['links'])){
        update_post_meta($post_id, 'links', $_POST['links']);
    }
}
add_action('save_post', 'rayium_fields_save');