<?php

defined('ABSPATH') || exit;

function rayium_options_page_menu(){
    add_submenu_page(
        'edit.php?post_type=course',
        'تنظیمات',
        'تنظیمات',
        'manage_options',
        'rayium_options',
        'rayium_options_callback'
    );
}

add_action('admin_menu', 'rayium_options_page_menu');

function rayium_options_callback(){

    echo '<form action="options.php" method="POST">';

    settings_fields( 'rayium-option-gateway' );
    do_settings_sections( 'rayium-option-sections' );

    submit_button();

    echo '</form';
}

function rayium_section_cb() {
    echo '<p>کد مرچنت درگاه زیبال را در اینجا وارد کنید.</p>';
}


function rayium_register_setting(){

    add_settings_section(
        'rayium_gateway',
        'درگاه زیبال',
        'rayium_section_cb',
        'rayium-option-sections'
    );

    add_settings_field(
		'rayium_gateway_api',
		'کد مرچنت',
		'rayium_gateway_api_cb',
        'rayium-option-sections',
        'rayium_gateway',
		[
            'label_for' => 'rayium_gateway_api'
        ]
	);

    register_setting(
        'rayium-option-gateway',
        'rayium_option_gateway',
        [
            'sanitize_callback' => 'sanitize_text_field'
        ]
    );

}

add_action('admin_init', 'rayium_register_setting');

function rayium_gateway_api_cb($args){
    ?>
    <input type="text" 
    name="rayium_option_gateway" 
    id="<?php echo esc_attr( $args['label_for'] ); ?>" 
    value="<?php echo esc_attr( get_option( 'rayium_option_gateway' ) ); ?>">
    <?php
}