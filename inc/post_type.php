<?php

// Register Custom Post Type
function rayium_register_course_post_type() {

	$labels = array(
		'name'                  => _x( 'دوره ها', 'text_domain' ),
		'singular_name'         => _x( 'دوره', 'text_domain' ),
		'add_new'               => __( 'افزودن دوره جدید', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( 'Course', 'text_domain' ),
		'description'           => __( 'فروش دوره آموزشی', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'thumbnail', 'comments' ),
		'taxonomies'            => array( 'course_tag', 'course_cat' ),
		'hierarchical'          => true,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 5,
		'menu_icon'             => 'dashicons-format-video',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
		'show_in_rest'          => false,
		'rest_base'             => 'course',
	);
	register_post_type( 'course', $args );
	
	$labels = array(
        'name' => 'دسته بندی دوره'
    );

    $args = array(
        'labels' => $labels,
        'hierarchical' => true,
		'show_ui'  => true,
    );

    register_taxonomy('course_cat', array('course'), $args, 
        array(
            'hide_empty' => true
        )
    );

    $args = array(
        'label'                 => __( 'لیست پخش', 'text_domain' ),
        'description'           => __( 'مدیریت لیست پخش', 'text_domain' ),
        'supports'              => array( 'title', 'editor' ),
        'hierarchical'          => false,
        'public'                => false,
        'show_ui'               => false,
        'show_in_menu'          => true,
        'menu_position'         => 5,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
		
    );
    register_post_type( 'playlist_items', $args );
	register_post_status('free', $args);
	register_post_status('premium', $args);

	$labels = array(
		'name'                  => _x( 'ثبت نام دوره ها', 'text_domain' ),
		'singular_name'         => _x( 'ثبت نام دوره', 'text_domain' ),
	);
	$args = array(
		'label'                 => __( 'ثبت نام دوره', 'text_domain' ),
		'description'           => __( 'مدیریت ثبت نام دوره ها', 'text_domain' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'comments', 'author' ),
		'hierarchical'          => false,
		'public'                => false,
		'show_ui'               => true,
		'show_in_menu'          => 'edit.php?post_type=course',
		'menu_position'         => 5,
		'show_in_admin_bar'     => false,
		'show_in_nav_menus'     => false,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => true,
		'publicly_queryable'    => false,
		'capability_type'       => 'page',
		'show_in_rest'          => false,
	);
	register_post_type( 'course_register', $args );

	register_post_status( 'failed', [
		'label' => 'ناموفق',
		'show_in_admin_all_list' => true,
		'show_in_admin_status_list' => true,
		'label_count' => _n_noop('ناموفق <span class="count">%s</span>', 'ناموفق <span class="count">%s</span>')
	] );
}
add_action( 'init', 'rayium_register_course_post_type', 0 );

function rayium_course_table_cols($columns){
	$columns['price'] = 'قیمت دوره';
	$columns['students'] = 'تعداد دانشجو';
	$columns['sales'] = 'تعداد فروش';
	return $columns;
}
add_filter('manage_course_posts_columns', 'rayium_course_table_cols', 1);

function rayium_course_table_cols_data($column, $post_id) {
	
	if ($column == 'price') {
		$price = get_post_meta($post_id, 'rayium_price', true);
		$final_price = rayium_get_course_final_price($post_id);
		$percent = rayium_get_final_discount_percent($post_id);

		if($percent){
			printf('<del>%s</del> %s', number_format($price / 10), 'تومان');
		}
		echo '  ';
		if($final_price > 0) {
			printf('<ins>%s</ins> %s', number_format($final_price / 10), 'تومان');
		} else {
			printf('<ins>%s</ins>', 'رایگان');
		}
		echo '  ';
		if($percent){
			printf('%d%% تخفیف', $percent,);
		}

	}elseif($column == 'students'){
		printf('<a href="%s">%d</a>', admin_url('edit.php?post_type=course_register&post_parent=' . $post_id), rayium_get_student_count($post_id));
	}
	elseif($column == 'sales'){
		echo number_format(rayium_get_course_sales($post_id) / 10) .' '. 'تومان';
	}
}
add_action( 'manage_course_posts_custom_column', 'rayium_course_table_cols_data', 10, 2 );

function rayium_course_register_table_cols($columns){
	$columns['author'] = 'مشتریان';
	$columns['price'] = 'قیمت';
	$columns['status'] = 'وضعیت';
	$columns['title'] = 'شناسه';
	$new_columns = [];

	foreach($columns as $col => $label){
		$new_columns[$col] = $label;
		if($col == 'title'){
			$new_columns['course_title'] = 'عنوان دوره';
		}
	}

	return $new_columns;
}
add_filter('manage_course_register_posts_columns', 'rayium_course_register_table_cols', 0);

function rayium_course_register_table_cols_data($column, $post_id) {
	$course_register = get_post($post_id);
	if ($column == 'price') {
		$price = get_post_meta($post_id, '_price', true);
		$final_price = $course_register->menu_order;
		$percent = get_post_meta($post_id, '_discount_price', true);

		if($percent){
			printf('<del>%s</del> %s', number_format($price / 10), 'تومان');
		}
		echo '  ';
		if($final_price > 0) {
			printf('<ins>%s</ins> %s', number_format($final_price / 10), 'تومان');
		} else {
			printf('<ins>%s</ins>', 'رایگان');
		}
		echo '  ';
		if($percent){
			printf('%d%% تخفیف', $percent,);
		}

	}elseif($column == 'status'){
		$statues = [
			'publish' => 'تکمیل شده',
			'pending' => 'در انتظار',
			'failed' => 'ناموفق',
			'refund' => 'مسترد'
		];
		printf(
			'<a href="%s" class="badge badge-%s">%s</a>',
			admin_url('edit.php?post_status='. $course_register->post_status .'&post_type=course_register'), 
			$course_register->post_status, 
			$statues[$course_register->post_status]
		);
	}elseif($column == 'course_title'){
		echo get_the_title($course_register->post_parent);
	}
}
add_action( 'manage_course_register_posts_custom_column', 'rayium_course_register_table_cols_data', 10, 2 );

function rayium_register_course_sortable($sortable){
	$sortable['price'] = 'menu_order';

	return $sortable;
}
add_filter( 'manage_edit-course_register_sortable_columns', 'rayium_register_course_sortable' );

function rayium_register_course_filter_table(){
	include RAYIUM_ADMIN . 'filter.php';
};
add_action('restrict_manage_posts', 'rayium_register_course_filter_table');

function handle_download($atts) {
    $atts = shortcode_atts(array(
        'id' => 0,
        'token' => ''
    ), $atts, 'playlist_items');

    $post_id = $atts['id'];
    $token = $atts['token'];

    $expiration_date = get_post_meta($post_id, 'expiration_date', true);
    $download_count = get_post_meta($post_id, 'download_count', true);

    if (strtotime($expiration_date) < time() || $download_count >= 1) {
        return 'Invalid or expired download link.';
    }

    // Increment download count
    update_post_meta($post_id, 'download_count', $download_count + 1);

    // Serve the file
    $file_url = get_post_meta($post_id, 'file_url', true);
    header('Location: ' . $file_url);
    exit;
}
add_shortcode('playlist_items', 'handle_download');

// Post Type Product
function register_products() {
	// Product
    register_post_type('product',
        array(
            'labels' => array(
                'name' => 'محصولات',
                'singular_name' => 'محصول',
                'add_new' => 'افزودن جدید',
                'add_new_item' => 'افزودن محصول جدید',
                'edit_item' => 'ویرایش محصول',
                'new_item' => 'محصول جدید',
                'view_item' => 'مشاهده محصول',
                'search_items' => 'جستجوی محصولات',
                'not_found' => 'محصولی یافت نشد',
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'محصولات'),
            'supports' => array('title', 'editor', 'thumbnail'),
            'menu_icon' => 'dashicons-cart',
            'show_in_rest' => true,
        )
    );

    // Category
    register_taxonomy('product_cat', 'product',
        array(
            'labels' => array(
                'name' => 'دسته‌بندی محصولات',
                'singular_name' => 'دسته‌بندی',
                'add_new_item' => 'افزودن دسته‌بندی جدید',
            ),
            'hierarchical' => true,
            'rewrite' => array('slug' => 'دسته-محصول'),
            'show_in_rest' => true,
        )
    );

    // Tags
    register_taxonomy('product_tag', 'product',
        array(
            'labels' => array(
                'name' => 'برچسب محصولات',
                'singular_name' => 'برچسب',
            ),
            'hierarchical' => false,
            'rewrite' => array('slug' => 'برچسب-محصول'),
            'show_in_rest' => true,
        )
    );
}
add_action('init', 'register_products');

function rayium_add_failed_status()
{
    global $post;
    if($post && $post->post_type == 'course_register'){
        include RAYIUM_PATH . 'inc/admin/post_status.php';
    }
    
}
add_action('admin_footer', 'rayium_add_failed_status');
