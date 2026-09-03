<?php

define("RAYIUM_URI", get_template_directory_uri());
define("RAYIUM_PATH", get_template_directory() . DIRECTORY_SEPARATOR);
define("RAYIUM_STYLE", get_stylesheet_uri());
define("RAYIUM_INC", RAYIUM_PATH . "inc/");
define("RAYIUM_ADMIN", RAYIUM_INC . "admin/");

require_once RAYIUM_INC . "enqueue.php";
require_once RAYIUM_INC . "function.php";
require_once RAYIUM_INC . "post_type.php";
require_once RAYIUM_INC . "taxonomy.php";
require_once RAYIUM_INC . "templates.php";
require_once RAYIUM_INC . "options.php";
require_once RAYIUM_INC . "functions-register.php";
require_once RAYIUM_INC . "functions-course.php";
require_once RAYIUM_INC . "payments.php";
require_once('pagination.php');
require_once('navbar.php');
// require_once('stats.php');
require_once "like.php";
if(is_admin()){
    require_once RAYIUM_INC . "metabox.php";
    require_once RAYIUM_INC . "metaboxblog.php";
}

// Post Thumbnails
if(function_exists("add_theme_support")){
    add_theme_support("post-thumbnails");
}

// View Posts
function set_post_views_field() {
    
    if(is_single()){

        global $post;
        $post_id = $post->ID;
        $count = 1;
        $post_view_count  = get_post_meta($post_id, "post_view_count", true);
        if($post_view_count){
            $count = $post_view_count + 1;
        }
        update_post_meta($post_id, "post_view_count", $count);

    }
}
add_action("wp_head", "set_post_views_field");

function add_post_view_count_column($columns) {
    if(is_array($columns) && !isset($columns['post_view_count'])) {
        $columns['post_view_count'] = 'تعداد بازدیدها';
    }

    return $columns;
}
add_filter("manage_posts_columns", "add_post_view_count_column");
function set_post_view_count_column($column_name, $post_id){
    if($column_name == 'post_view_count'){
        $count = get_post_meta($post_id, "post_view_count", true);
        echo $count ? $count : 0;
    }
}
add_filter('manage_posts_custom_columns', 'set_post_view_count_column', 10, 2);

function get_post_view_count($post_id){
    $count = get_post_meta($post_id, "post_view_count", true);
    return $count ? $count : 0;
}

// Widgets
function Rayium_widgets(){
    register_sidebar(
        array(
            'name' => 'ابزارک سمت چپ',
            'id' => 'sidebar',
            'description' => 'محل قرار گیری ابزارک های آماده',
            'before_widget' => '<div class="card rounded-3 mb-3"><div class="card-body">',
            'after_widget' => '</div></div>',
            'before_title' => '<h5><i class="fa-duotone fa-circle-dot ms-2"></i>',
            'after_title' => '</h5>'
        )
    );
}
add_action('widgets_init', 'Rayium_widgets');

// Remove jQuery Migrate
function remove_jquery_migrate( $scripts ) {
    if ( ! is_admin() && isset( $scripts->registered['jquery'] ) ) {
        $script = $scripts->registered['jquery'];
        if ( $script->deps ) {
            $script->deps = array_diff( $script->deps, array( 'jquery-migrate' ) );
        }
    }
}
add_action( 'wp_default_scripts', 'remove_jquery_migrate' );

// Remove Site Health
function themeprefix_remove_dashboard_widget() {
    remove_meta_box( 'dashboard_site_health', 'dashboard', 'normal' );
}
add_action('wp_dashboard_setup', 'themeprefix_remove_dashboard_widget' );
add_action( 'admin_menu', 'remove_site_health_menu' );

function remove_site_health_menu(){
    remove_submenu_page( 'tools.php','site-health.php' );
}
add_filter( 'wp_fatal_error_handler_enabled', '__return_false' );

// Lazy Load
function rayium_lazy_load_images( $content ) {

    if ( is_singular() && in_the_loop() && is_main_query() ) {
        $content = preg_replace_callback( '/<img[^>]+>/', function( $matches ) {

            if( false === strpos( $matches[ 0 ], 'loading=' ) ) {
                $matches[ 0 ] = str_replace( '<img', '<img loading="lazy"', $matches[ 0 ] );
            }

            return $matches[ 0 ];

        }, $content );
    }

    return $content;
}
add_filter( 'the_content', 'rayium_lazy_load_images' );

// Add Table of Contents to Posts
function add_table_of_contents($content) {
    if (is_singular('post') && is_main_query()) {
        $pattern = '/<h([2-6]).*?>(.*?)<\/h[2-6]>/';
        if (preg_match_all($pattern, $content, $matches, PREG_SET_ORDER)) {
            $output = '<div class="card mt-4 mb-4 rounded-5 p-2"><details class="js-list">';
            $output .= '<summary class="title js-title"><i class="fa-duotone fa-list-dots mt-1"></i> <h3 class="fs-4 mt-1 me-2">فهرست محتوا</h3> <span class="icons ms-4 mt-3"></span></summary>';
            $output .= '<div class="content js-content"><ul class="mt-3">';
            foreach ($matches as $match) {
                $level = $match[1];
                $title = $match[2];
                $slug = sanitize_title($title);
                $output .= '<li class="mb-2 toc-level-' . $level . '"><a href="#' . $slug . '">' . $title . '</a></li>';
                $content = str_replace($match[0], '<h' . $level . ' id="' . $slug . '">' . $title . '</h' . $level . '>', $content);
            }
            $output .= '</ul></div>';
            $output .= '</details></div>';
            $content = $output . $content;
        }
    }
    return $content;
}

add_filter('the_content', 'add_table_of_contents');

// Estimated Post Reading Time
function estimate_study_duration(){
    $content_text           = strip_tags( get_the_content() );
    $content_words          = explode( ' ', $content_text );
    $word_count             = count( $content_words );
    $estimate_duration      = round( $word_count / 200 );
    $estimate_duration_html = '<p>';
    $estimate_duration_html.= '';
    $estimate_duration_html.= $estimate_duration . ' دقیقه';
    $estimate_duration_html.= '</p>';
    return $estimate_duration_html;
}
add_shortcode('studyduration', 'estimate_study_duration');

// Display New Badge Posts
function display_new_badge($post_id) {
    $post_date = get_the_date('Y-m-d', $post_id);
    $current_date = current_time('Y-m-d');
    $days_diff = (strtotime($current_date) - strtotime($post_date)) / (60 * 60 * 24);
    if ($days_diff < 3) {
        echo '<span class="badge text-bg-secondary">جدید</span>';
    }
}

// fields Profie User
function contact_methods($profile_fields){
    $profile_fields['linkedin'] = 'لینکدین';
    $profile_fields['cv'] = 'رزومه';
    $profile_fields['whatsapp'] = 'واتس آپ';
    $profile_fields['twitter'] = 'توئیتر';
    $profile_fields['telegram'] = 'تلگرام';
    $profile_fields['instagram'] = 'اینستاگرام';
    return $profile_fields; 
}
add_filter('user_contactmethods','contact_methods');

// Activation Page Option Tree
add_filter( 'ot_show_new_layout', '__return_false' );
add_filter( 'ot_show_pages', '__return_false' );
add_filter( 'ot_theme_mode', '__return_true' );
add_filter( 'ot_meta_boxes', '__return_true' );
include_once( 'option/option-tree/ot-loader.php' );
include_once( 'option/theme-options.php' );
// include_once( 'option/theme-metabox.php' );

// Disables the block editor for WordPress widgets
function disable_widgets_block_editor() {
	remove_theme_support( 'widgets-block-editor' );
}
add_action( 'after_setup_theme', 'disable_widgets_block_editor' );

// Register Menus
function register_my_menus() {
    register_nav_menus(array(
        'primary-menu' => 'موقعیت سایدبار',
    ));
}
add_action('init', 'register_my_menus');

// Add Google Preferred Source Button
function rayium_preferred_source_script() {
    wp_enqueue_script(
        'google-preferred-source',
        'https://news.google.com/swg/js/v1/publisher.js',
        array(),
        null,
        true
    );
}
add_action('wp_enqueue_scripts', 'rayium_preferred_source_script');

function rayium_preferred_source_shortcode() {
    return '<div google-add-preferred-source-btn></div>';
}
add_shortcode('preferred_source', 'rayium_preferred_source_shortcode');

function rayium_preferred_source_content($content) {
    if (!is_singular(array('post', 'product'))) {
        return $content;
    }

    if (!in_the_loop() || !is_main_query()) {
        return $content;
    }

    $button = do_shortcode('[preferred_source]');

    return $button . $content;
}

add_filter('the_content', 'rayium_preferred_source_content');
