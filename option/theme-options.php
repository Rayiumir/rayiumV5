<?php

add_action( 'admin_init', 'custom_theme_options', 1 );

function custom_theme_options() {

$saved_settings = get_option( 'option_tree_settings', array() );
$custom_settings = array( 
    'contextual_help' => array( 
      'sidebar'       => ''
    ),
    'sections'        => array( 
      array(
        'id'          => 'header',
        'title'       => 'سربرگ'
      ),
      array(
        'id'          => 'onoff',
        'title'       => 'فعال سازی'
      ),
      array(
        'id'          => 'footer',
        'title'       => 'پابرگ'
      ),
),

'settings'        => array( 

// General
array(
    'id'          => 'favicon',
    'label'       => 'فاویکون',
    'desc'        => 'پیشنهاد می‌شود اندازه‌ی فاویکون 32px × 32px باشد.',
    'std'         => '',
    'type'        => 'upload',
    'section'     => 'header'
),

array(
  'id'          => 'logoLight',
  'label'       => 'نشان روشن',
  'desc'        => 'پیشنهاد می شود اندازه نشان 50px باشد.',
  'std'         => '',
  'type'        => 'upload',
  'section'     => 'header'
),

array(
  'id'          => 'logoDark',
  'label'       => 'نشان تاریک',
  'desc'        => 'پیشنهاد می شود اندازه نشان 50px باشد.',
  'std'         => '',
  'type'        => 'upload',
  'section'     => 'header'
),

array(
  'id'          => 'c1',
  'label'       => '',
  'desc'        => 'جدیدترین / دیدگاه ها',
  'std'         => '',
  'type'        => 'on-off',
  'section'     => 'onoff'
),
array(
  'id'          => 'c2',
  'label'       => '',
  'desc'        => 'برچسب ها',
  'std'         => '',
  'type'        => 'on-off',
  'section'     => 'onoff'
),
array(
  'id'          => 'c3',
  'label'       => '',
  'desc'        => 'نوشته های مرتبط',
  'std'         => '',
  'type'        => 'on-off',
  'section'     => 'onoff'
),
array(
  'id'          => 'c4',
  'label'       => '',
  'desc'        => 'نظرات',
  'std'         => '',
  'type'        => 'on-off',
  'section'     => 'onoff'
),

array(
  'id'          => 'c5',
  'label'       => '',
  'desc'        => 'نشان',
  'std'         => '',
  'type'        => 'on-off',
  'section'     => 'onoff'
),
array(
  'id'          => 'c6',
  'label'       => '',
  'desc'        => 'پابرگ',
  'std'         => '',
  'type'        => 'on-off',
  'section'     => 'onoff'
),
array(
  'id'          => 'c7',
  'label'       => '',
  'desc'        => 'محصولات فروشگاه',
  'type'        => 'on-off',
  'section'     => 'onoff'
),
array(
    'id'          => 'c8',
    'label'       => '',
    'desc'        => 'دوره ها',
    'type'        => 'on-off',
    'section'     => 'onoff'
),
array(
    'id'          => 'c9',
    'label'       => '',
    'desc'        => 'محصولات مرتبط',
    'type'        => 'on-off',
    'section'     => 'onoff'
),
array(
    'id'          => 'c10',
    'label'       => '',
    'desc'        => 'اطلاعات نویسنده',
    'type'        => 'on-off',
    'section'     => 'onoff'
),
array(
  'id'          => 'c11',
  'label'       => '',
  'desc'        => 'جستجو سایت',
  'type'        => 'on-off',
  'section'     => 'onoff'
),

array(
  'id'          => 'textarea_1',
  'label'       => 'درباره ما',
  'desc'        => '',
  'std'         => '',
  'type'        => 'textarea',
  'section'     => 'footer'
),

array(
  'id'          => 'texts_1',
  'label'       => 'ایمیل',
  'desc'        => '',
  'std'         => '',
  'type'        => 'text',
  'section'     => 'footer'
),
array(
  'id'          => 'texts_2',
  'label'       => 'تلگرام',
  'desc'        => '',
  'std'         => '',
  'type'        => 'text',
  'section'     => 'footer'
),
array(
  'id'          => 'texts_3',
  'label'       => 'اینستاگرام',
  'desc'        => '',
  'std'         => '',
  'type'        => 'text',
  'section'     => 'footer'
),

array(
  'id'          => 'texts_4',
  'label'       => 'ایکس (توییتر سابق)',
  'desc'        => '',
  'std'         => '',
  'type'        => 'text',
  'section'     => 'footer'
),
array(
  'id'          => 'texts_5',
  'label'       => 'گیت هاب',
  'desc'        => '',
  'std'         => '',
  'type'        => 'text',
  'section'     => 'footer'
),





));
  /* allow settings to be filtered before saving */
  $custom_settings = apply_filters( 'option_tree_settings_args', $custom_settings );
  /* settings are not the same update the DB */
  if ( $saved_settings !== $custom_settings ) {
    update_option( 'option_tree_settings', $custom_settings ); 
  }
}