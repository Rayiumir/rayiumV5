<?php

defined('ABSPATH') || exit;


function rayium_second_to_time($seconds){
    $seconds = round(floatval($seconds));

    $hours   = floor($seconds / 3600);
    $minutes = floor(($seconds % 3600) / 60);
    $seconds = $seconds % 60;

    return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
}


function rayium_remote_file_size($url){
    $ch = curl_init($url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, TRUE);
    curl_setopt($ch, CURLOPT_NOBODY, TRUE);

    $data = curl_exec($ch);
    $size = curl_getinfo($ch, CURLINFO_CONTENT_LENGTH_DOWNLOAD);

    curl_close($ch);
    return $size;
}

function rayium_formatBytes($size, $precision = 2) { 

    $base = log($size, 1024);
    $suffixes = array('', 'کیلوبایت', 'مگابایت', 'گیگابایت', 'ترابایت');   

    return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];

}

function rayium_get_extension($url){

    $info = pathinfo($url);

    return isset($info['extension']) ? $info['extension'] : '';
}

/**
 * Shortcode to display and handle user profile editing.
 */
function rayium_user_profile_editor_shortcode() {
    $user = wp_get_current_user();
    $user_id = $user->ID;
    $message = '';

    // Handle form submission
    if (isset($_POST['rayium_profile_submit']) && isset($_POST['_wpnonce_rayium_profile_editor'])) {
        if (!wp_verify_nonce($_POST['_wpnonce_rayium_profile_editor'], 'rayium_profile_editor_nonce')) {
            $message = '<div class="alert alert-danger">خطای امنیتی! لطفاً دوباره تلاش کنید.</div>';
        } else {
            $errors = [];
            $user_data = [];
            $meta_data = [];

            // Handle avatar upload
            if (!empty($_FILES['avatar']['name'])) {
                require_once(ABSPATH . 'wp-admin/includes/image.php');
                require_once(ABSPATH . 'wp-admin/includes/file.php');
                require_once(ABSPATH . 'wp-admin/includes/media.php');

                $avatar = $_FILES['avatar'];
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                
                if (!in_array($avatar['type'], $allowed_types)) {
                    $errors[] = 'فرمت تصویر باید JPG، PNG یا GIF باشد.';
                } elseif ($avatar['size'] > 2 * 1024 * 1024) { // 2MB limit
                    $errors[] = 'حجم تصویر نباید بیشتر از 2 مگابایت باشد.';
                } else {
                    $upload = wp_handle_upload($avatar, ['test_form' => false]);
                    
                    if (!isset($upload['error'])) {
                        $meta_data['rayium_avatar'] = $upload['url'];
                    } else {
                        $errors[] = 'خطا در آپلود تصویر: ' . $upload['error'];
                    }
                }
            }

            // Basic fields (email, display_name)
            if (isset($_POST['email'])) {
                $email = sanitize_email($_POST['email']);
                if (!is_email($email)) {
                    $errors[] = 'لطفاً یک آدرس ایمیل معتبر وارد کنید.';
                } else {
                    $existing_user_by_email = get_user_by('email', $email);
                    if ($existing_user_by_email && $existing_user_by_email->ID !== $user_id) {
                        $errors[] = 'این آدرس ایمیل قبلاً توسط کاربر دیگری استفاده شده است.';
                    } else {
                         $user_data['user_email'] = $email;
                    }
                }
            }

            if (isset($_POST['display_name'])) {
                $user_data['display_name'] = sanitize_text_field($_POST['display_name']);
            }

            // Custom fields (mobile)
            if (isset($_POST['mobile'])) {
                $mobile = sanitize_text_field($_POST['mobile']);
                if (!preg_match('/^09[0-9]{9}$/', $mobile)) {
                     $errors[] = 'لطفاً یک شماره موبایل معتبر (مانند 09123456789) وارد کنید.';
                } else {
                     $meta_data['mobile'] = $mobile;
                }
            }

            if (empty($errors)) {
                // Update user data
                if (!empty($user_data)) {
                     $user_data['ID'] = $user_id;
                     $update_result = wp_update_user($user_data);
                     if (is_wp_error($update_result)) {
                          $errors[] = 'خطا در به‌روزرسانی اطلاعات کاربر: ' . $update_result->get_error_message();
                     }
                }

                // Update user meta data
                if (empty($errors) && !empty($meta_data)) {
                    foreach ($meta_data as $key => $value) {
                        update_user_meta($user_id, $key, $value);
                    }
                }

                if (empty($errors)) {
                    $message = '<div class="alert alert-success">اطلاعات پروفایل با موفقیت به‌روزرسانی شد.</div>';
                    $user = wp_get_current_user();
                } else {
                    $message = '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
                }
            } else {
                $message = '<div class="alert alert-danger">' . implode('<br>', $errors) . '</div>';
            }
        }
    }

    // Get current avatar
    $avatar_url = get_user_meta($user_id, 'rayium_avatar', true);
    if (empty($avatar_url)) {
        $avatar_url = get_avatar_url($user->user_email, ['size' => 150]);
    }

    // Display the form
    ob_start();
    ?>
    <div class="rayium-user-profile-editor">
        <?php echo $message; ?>
        <form method="post" enctype="multipart/form-data">
            <h1 class="fs-4 mb-3 text-center font-bold">ویرایش پروفایل کاربری</h1>

            <?php wp_nonce_field('rayium_profile_editor_nonce', '_wpnonce_rayium_profile_editor'); ?>

            <div class="text-center">
                <img src="<?php echo esc_url($avatar_url); ?>" alt="تصویر پروفایل" class="img-fluid mb-3" style="width: 100px; height: 100px; border-radius: 50%;">
                <div class="form-group">
                    <label for="avatar" class="btn btn-outline-primary btn-sm w-100 rounded-5">تغییر تصویر پروفایل</label>
                    <input type="file" class="d-none" id="avatar" name="avatar" accept="image/*">
                </div>
                <h6 class="mt-3" style="font-size: 12px;">حداکثر حجم: 2 مگابایت<br>فرمت‌های مجاز: JPG، PNG، GIF</h6>
            </div>

            <div class="form-group mb-3">
                <label for="username" class="form-label">نام کاربری:</label>
                <input type="text" class="form-control rounded-5" id="username" value="<?php echo esc_attr($user->user_login); ?>" disabled>
                <h6 class="mt-3" style="font-size: 12px;">نام کاربری قابل تغییر نیست.</h6>
            </div>

            <div class="form-group mb-3">
                <label for="email" class="form-label">ایمیل:</label>
                <input type="email" class="form-control rounded-5" id="email" name="email" value="<?php echo esc_attr($user->user_email); ?>" required>
            </div>

            <div class="form-group mb-3">
                <label for="display_name" class="form-label">نام نمایشی:</label>
                <input type="text" class="form-control rounded-5" id="display_name" name="display_name" value="<?php echo esc_attr($user->display_name); ?>" required>
            </div>

            <?php
            $mobile = get_user_meta($user_id, 'mobile', true);
            ?>
            <div class="form-group mb-3">
                <label for="mobile" class="form-label">شماره موبایل:</label>
                <input type="text" class="form-control rounded-5" id="mobile" name="mobile" value="<?php echo esc_attr($mobile); ?>">
                <h6 class="mt-3" style="font-size: 12px;">شماره موبایل باید 11 رقم و با 09 شروع شود.</h6>
            </div>

            <button type="submit" name="rayium_profile_submit" class="btn btn-primary rounded-5">ذخیره تغییرات</button>

        </form>
    </div>

    <script>
    document.getElementById('avatar').addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.querySelector('.rounded-circle').src = e.target.result;
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });
    </script>
    <?php
    return ob_get_clean();
}
add_shortcode('rayium_user_profile_editor', 'rayium_user_profile_editor_shortcode');

function rayium_instructor_dashboard_shortcode() {

    $user_id = get_current_user_id();
    
    // Get all courses by this instructor
    $courses = new WP_Query([
        'post_type' => 'course',
        'posts_per_page' => -1,
        'meta_query' => [
            [
                'key' => 'rayium_teacter',
                'value' => $user_id,
                'compare' => '='
            ]
        ]
    ]);

    if (!$courses->have_posts()) {
        return '<div class="alert alert-info">شما هنوز هیچ دوره‌ای ایجاد نکرده‌اید.</div>';
    }

    // Calculate statistics
    $total_courses = $courses->post_count;
    $total_sales = 0;
    $today_sales = 0;
    $today = date('Y-m-d');

    while ($courses->have_posts()) : $courses->the_post();
        $course_id = get_the_ID();
        $total_sales += (int)rayium_get_course_sales($course_id);
        
        // Get today's sales for this course
        $today_sales_query = new WP_Query([
            'post_type' => 'course_register',
            'post_parent' => $course_id,
            'post_status' => 'publish',
            'date_query' => [
                [
                    'year' => date('Y'),
                    'month' => date('m'),
                    'day' => date('d'),
                ]
            ]
        ]);
        
        if ($today_sales_query->have_posts()) {
            while ($today_sales_query->have_posts()) : $today_sales_query->the_post();
                $price = get_post_meta(get_the_ID(), 'rayium_price', true);
                $today_sales += (int)$price;
            endwhile;
            wp_reset_postdata();
        }
    endwhile;
    wp_reset_postdata();

    ob_start();
    ?>
    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card rounded-4 border-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <i class="fa-duotone fa-graduation-cap fa-3x text-primary"></i>
                        </div>
                        <div class="col-md-9">
                            <span class="fw-bold title">تعداد دوره‌ها</span>
                            <br>
                            <span class="text-muted subtitle fw-bold"><?php echo number_format($total_courses); ?> دوره</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card rounded-4 border-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <i class="fa-duotone fa-money-bill fa-3x text-success"></i>
                        </div>
                        <div class="col-md-9">
                            <span class="fw-bold title">فروش کل</span>
                            <br>
                            <span class="text-muted subtitle fw-bold"><?php echo number_format($total_sales / 10); ?> تومان</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="center">عنوان دوره</th>
                    <th class="center" width="50"> دانشجو</th>
                    <th class="center" width="150">درآمد کل</th>
                    <th class="center" width="150">آخرین فروش</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                while ($courses->have_posts()) : $courses->the_post();
                    $course_id = get_the_ID();
                    $student_count = rayium_get_student_count($course_id);
                    $total_sales = rayium_get_course_sales($course_id);
                    
                    // Get last sale date
                    $last_sale = new WP_Query([
                        'post_type' => 'course_register',
                        'post_parent' => $course_id,
                        'post_status' => 'publish',
                        'posts_per_page' => 1,
                        'orderby' => 'date',
                        'order' => 'DESC'
                    ]);
                    
                    $last_sale_date = $last_sale->have_posts() ? get_the_date('Y/m/d', $last_sale->posts[0]->ID) : '-';
                ?>
                    <tr>
                        <td>
                            <a href="<?php echo get_permalink($course_id); ?>" class="text-decoration-none">
                                <?php echo get_the_title(); ?>
                            </a>
                        </td>
                        <td><?php echo number_format($student_count); ?></td>
                        <td><?php echo number_format($total_sales / 10); ?> تومان</td>
                        <td><?php echo $last_sale_date; ?></td>
                    </tr>
                <?php endwhile; 
                wp_reset_postdata();
                ?>
            </tbody>
        </table>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('instructor_dashboard', 'rayium_instructor_dashboard_shortcode'); 

function rayium_courses_history_shortcode() {
    if (!is_user_logged_in()) {
        return '<div class="alert alert-warning">لطفا ابتدا وارد حساب کاربری خود شوید.</div>';
    }

    $user_id = get_current_user_id();
    
    $registrations = new WP_Query([
        'post_type' => 'course_register',
        'author' => $user_id,
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC'
    ]);
    
    if (!$registrations->have_posts()) {
        return '<div class="alert alert-info">شما هنوز هیچ خریدی انجام نداده‌اید.</div>';
    }

    ob_start();
    ?>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>نام دوره</th>
                    <th width="150">نام خریدار</th>
                    <th width="100">تاریخ خرید</th>
                    <th width="150">قیمت</th>
                    <th width="50">وضعیت</th>
                    <th width="150">عملیات</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                while ($registrations->have_posts()) : $registrations->the_post();
                    $course_id = get_post_field('post_parent');
                    $course = get_post($course_id);
                    if (!$course || $course->post_type !== 'course') continue;
                    
                    $course_link = get_permalink($course_id);
                    $price = get_post_meta(get_the_ID(), '_price', true);
                    $final_price = get_post_field('menu_order');
                    $status = get_post_status();
                ?>
                    <tr>
                        <td>
                            <a href="<?php echo esc_url($course_link); ?>" class="text-decoration-none">
                                <?php echo esc_html($course->post_title); ?>
                            </a>
                        </td>
                        <td><?php echo esc_html(get_the_author_meta('display_name', get_the_author_meta('ID'))); ?></td>
                        <td><?php echo get_the_date('Y/m/d'); ?></td>
                        <td>
                            <?php 
                            if ($final_price < $price) {
                                echo '<del>' . number_format($price / 10) . '</del> ';
                            }
                            echo number_format($final_price / 10) . ' تومان';
                            ?>
                        </td>
                        <td>
                            <?php
                            $status_label = '';
                            switch ($status) {
                                case 'publish':
                                    $status_label = '<span class="badge bg-success">تکمیل شده</span>';
                                    break;
                                case 'pending':
                                    $status_label = '<span class="badge bg-warning">در انتظار پرداخت</span>';
                                    break;
                                case 'failed':
                                    $status_label = '<span class="badge bg-danger">ناموفق</span>';
                                    break;
                                default:
                                    $status_label = '<span class="badge bg-secondary">' . $status . '</span>';
                            }
                            echo $status_label;
                            ?>
                        </td>
                        <td>
                            <?php if ($status === 'publish'): ?>
                                <a href="<?php echo esc_url($course_link); ?>" class="btn btn-sm btn-primary rounded-5">
                                    <i class="fa-duotone fa-play"></i>
                                    مشاهده دوره
                                </a>
                            <?php elseif ($status === 'failed'): ?>
                                <a href="<?php echo esc_url($course_link); ?>" class="btn btn-sm btn-danger rounded-5">
                                    <i class="fa-duotone fa-rotate"></i>
                                    تلاش مجدد
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; 
                wp_reset_postdata();
                ?>
            </tbody>
        </table>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('courses_history', 'rayium_courses_history_shortcode');

function rayium_get_total_students() {
    global $wpdb;
    $student_ids = $wpdb->get_col("SELECT DISTINCT post_author FROM {$wpdb->posts} WHERE post_type='course_register' AND post_status='publish'");
    return count(array_unique($student_ids));
}

function rayium_get_total_courses() {
    $courses = new WP_Query([
        'post_type' => 'course',
        'posts_per_page' => -1,
        'post_status' => 'publish'
    ]);
    return $courses->found_posts;
}

function rayium_get_total_teaching_hours() {
    global $wpdb;
    $total_duration = $wpdb->get_var("SELECT SUM(meta_value) FROM {$wpdb->postmeta} WHERE meta_key='_duration' AND post_id IN (SELECT ID FROM {$wpdb->posts} WHERE post_type='course' AND post_status='publish')");
    return round($total_duration / 3600, 1); // Convert seconds to hours
}

function rayium_get_total_instructors() {
    global $wpdb;
    $instructor_ids = $wpdb->get_col("SELECT DISTINCT meta_value FROM {$wpdb->postmeta} WHERE meta_key='rayium_teacter' AND post_id IN (SELECT ID FROM {$wpdb->posts} WHERE post_type='course' AND post_status='publish')");
    return count(array_unique($instructor_ids));
}

