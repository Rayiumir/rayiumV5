<!DOCTYPE html>
<html lang="fa" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ادمین رنجر</title>
    <?php wp_head(); ?>
    <meta name="description" content="<?php 
        if (is_single() || is_page()) {
            echo get_the_excerpt();
        } else {
            bloginfo('description');
        }
    ?>">
</head>

<body <?php body_class(); ?>>