<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="canonical" href="<?php echo esc_url(home_url($wp->request)); ?>">
    <title><?php wp_title('|', true, 'right'); bloginfo('name'); ?></title>
    <?php wp_head(); ?>
    <?php 
        $favicon = ot_get_option('favicon');
        if(!empty($favicon)) { 
    ?>
    <link rel="icon" href="<?php echo $favicon; ?>" type="image/gif" sizes="16x16">
    <?php   }   ?>
    <meta name="description" content="<?php 
        if (is_single() || is_page()) {
            echo get_the_excerpt();
        } else {
            bloginfo('description');
        }
    ?>">
</head>

<body <?php body_class(); ?>>