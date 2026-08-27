<!-- Single Blog Section -->
<?php 
defined('ABSPATH') || exit; 

$price = get_post_meta( $post->ID, 'rayium_price', true );
$sale_price = get_post_meta( $post->ID, 'rayium_sale_price', true );
$has_discount = get_post_meta( $post->ID, 'rayium_has_discount', true );
$expire = get_post_meta( $post->ID, 'rayium_expire', true );
$teacher_id = get_post_meta( $post->ID, 'rayium_teacter', true );
$demo = get_post_meta( $post->ID, 'rayium_demo', true );
$duration_total = get_post_meta( $post->ID, '_duration', true );
$teacter = get_user_by( 'ID', $teacher_id );
$is_student = is_user_logged_in() && rayium_is_stadent($post->ID, get_current_user_id());
$final_price = rayium_get_course_final_price($post->ID);
$percent = rayium_get_final_discount_percent($post->ID);
$student_count = rayium_get_student_count($post->ID);

$text1 = get_post_meta( $post->ID, 'text1', true );
$text2 = get_post_meta( $post->ID, 'text2', true );
$text3 = get_post_meta( $post->ID, 'text3', true );
$text4 = get_post_meta( $post->ID, 'text4', true );
$text5 = get_post_meta( $post->ID, 'text5', true );
$text6 = get_post_meta( $post->ID, 'text6', true );


if(is_user_logged_in(  )){
    $register_in_course_url = home_url(
        sprintf('?gateway=Zibal&register_course_id=%d&action=rayium_payment', $post->ID)
    );
}else{
    $register_in_course_url = wp_login_url(get_the_permalink());
}

$playlistQ = new WP_Query([
    'post_type' => 'playlist_items',
    'posts_per_page' => -1,
    'post_status' => ['free', 'premium'],
    'orderby' => 'menu_order',
    'order' => 'asc',
    'post_parent' => $post->ID,
]);

?>
<section class="my-4">
    <div class="row">
        <div class="col-md-9">
            <article class="card rounded-4 border-0">

                <div class="card-body">
                    <?php
                        $text7 = get_post_meta($post->ID, 'text7', true);
                        if(!empty($text7)) { 
                    ?>
                    <span class="badge bg-primary text-white fs-4 rounded-5 position-absolute mt-2 me-2">
                        <i class="fa-duotone fa-signal-bars-good"></i> <?php echo $text7; ?>
                    </span>
                    <?php } ?>
                    <div class="rayium-player">
                        <video class="img-fluid rounded-4 w-100" controls>
                            <source src="<?php echo esc_attr( $demo )?>" type="video/mp4">
                        </video>
                    </div>
                
                    <h1 class="font-bold fs-1 mt-4 mb-4"><?php the_title(); ?></h1>
                    <hr class="mb-4">
                    <?php the_content(); ?>
                    <div class="mt-3 bio">
                        <i class="fa-duotone fa-solid fa-list-tree"></i> <?php echo get_the_term_list('', 'course_cat', '', ',')?>
                        <i class="fa-duotone fa-solid fa-calendar"></i> <?php the_time('M/d/Y') ?>
                        <i class="fa-duotone fa-eye"></i> 
                        <?php
                            if(function_exists('get_post_view_count')){
                                echo get_post_view_count(get_the_ID());
                            }
                        ?>
                        <i class="fa-duotone fa-comment"></i> <?php echo comments_number(); ?>
                        <div class="float-left">
                            <span class="ms-3">
                                <a target="_blank" href="tg://msg_url?url=<?php the_permalink(); ?>&text=<?php the_title(); ?>" title="تلگرام" class="text-decoration-none ms-2"><i class="fa-brands fa-telegram "></i></a>
                                <a target="_blank" href="https://x.com/intent/tweet?text=<?php the_title(); ?>&url=<?php the_permalink(); ?>" title="ایکس (توییتر سابق)" class="text-decoration-none ms-2"><i class="fa-brands fa-square-x-twitter"></i></a>
                                <a target="_blank" href="whatsapp://send?text=<?php the_permalink(); ?>" title="واتس آپ" class="text-decoration-none ms-2"><i class="fa-brands fa-whatsapp "></i></a>
                                <a target="_blank" href="https://www.linkedin.com/sharing/share-offsite/?url=<?php the_permalink(); ?>" title="لینکدین" class="text-decoration-none ms-2"><i class="fa-brands fa-linkedin "></i></a>

                                <?php $short_link = wp_get_shortlink(); ?>
                                <button type="button"
                                        class="btn btn-light btn-sm rounded-5 copy-url-btn"
                                        data-link="<?php echo esc_attr($short_link); ?>">

                                    <i class="fa-duotone fa-link"></i>
                                    <span class="copied-text" style="display:none;">لینک کپی شد!</span>
                                </button>

                            </span>
                            <?php echo getPostLikeLink( get_the_ID() ); ?>
                        </div>
                    </div>
                </div>
            </article>
            <?php if ( ot_get_option('c2') != "off" ) {  ?>
            <section class="card rounded-3 border-0 mt-3 mb-3">
                <div class="card-body">
                    <i class="fa-duotone fa-tags"></i> <?php echo get_the_term_list('', 'course_tag', '', ',')?>
                </div>
            </section>
            <?php } ?>

            <section class="card rounded-3 border-0 mb-3">
                <div class="card-body">
                    <h1 class="fs-3 font-bold"><i class="fa-duotone fa-solid fa-list"></i> جلسات دوره</h1>
                    <?php
                        if($playlistQ->have_posts()) :
                    ?>
                    <div class="rayium-playlist-items mt-3 mb-2">
                        <?php 
                            while($playlistQ->have_posts()) : 
                            $playlistQ->the_post();
                            global $wp_query, $post;
                            $lock = ! $is_student && $post->post_status == 'premium';
                            $url = '';
                            $is_premium = $post->post_status == 'premium';
                            $is_playbale = $post->post_mime_type == 'video/mp4';
                            

                            if(! $is_premium || $is_student){
                                $url = $post->guid;
                            }

                            $width = get_post_meta(get_the_ID(), '_width', true);
                            $height = get_post_meta(get_the_ID(), '_height', true);
                            $duration = get_post_meta(get_the_ID(), '_duration', true);
                            $size = get_post_meta(get_the_ID(), '_size', true);

                            $ext = rayium_get_extension($url);
                        ?>
                        
                        
                        <div class="row">
                            <div class="col-md-10">
                                <div class="card rounded-5 shadow-sm border-0 mb-2 rayium-item" data-url="<?php echo $url; ?>">
                                    <div class="p-1">
                                        <div class="row">
                                            <div class="col-md-8 p-2">
                                                <span class="fs-6 me-3"><?php echo $post->menu_order + 1;?></span>
                                                <?php if( ! $lock ):?>
                                                    <?php if($is_playbale) : ?>
                                                        <span class="rayium-play mt-2 me-2"><i class="fa-duotone fa-play"></i></span>
                                                        <span class="rayium-pause mt-2 me-2"><i class="fa-duotone fa-pause"></i></span>
                                                    <?php else: ?>
                                                        <a href="<?php echo esc_attr( $url ) ?>" download>
                                                            <span class="rayium-download mt-2 me-2"><i class="fa-duotone fa-download"></i></span>
                                                        </a>
                                                    <?php endif; ?>
                                                <?php else: ?>
                                                    <span class="rayium-lock mt-2 me-2"><i class="fa-duotone fa-lock"></i></span>
                                                <?php endif;?> 
                                                <?php display_new_badge(get_the_ID()); ?>
                                                <span class="me-2 font-bold"><?php the_title() ?></span>
                                            </div>
                                            <div class="col-md-2 p-1">
                                                <?php if($is_premium && ! $is_student) : ?>
                                                    <div class="float-end">
                                                        <span class="badge bg-danger text-white rounded-4 p-2"><i class="fa-duotone fa-lock"></i> عدم دسترسی</span>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="col-md-2 p-1">
                                                <span class="badge text-white bg-secondary rounded-4 p-2 float-end">
                                                    <?php if($is_playbale) : ?>
                                                    <i class="fa-duotone fa-clock"></i>
                                                    <?php echo rayium_second_to_time($duration) ?>
                                                    <?php elseif($size) :?>
                                                    <i class="fa-duotone fa-hdd"></i> 
                                                    <?php echo rayium_formatBytes($size); ?> | <?php echo $ext; ?>
                                                    <?php else :?>
                                                    --:--
                                                    <?php endif; ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="d-grid gap-2">
                                    <?php if ( $url ) : ?>
                                        <a href="<?php echo $url; ?>" class="btn btn-primary btn-block mt-1 mb-2 rounded-5"><i class="fa-duotone fa-download"></i> دانلود</a>
                                    <?php else : ?>
                                        <button class="btn btn-secondary btn-block mt-1 mb-2 rounded-5" disabled><i class="fa-duotone fa-ban"></i> خرید دوره</button>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                        </div>
						<?php endwhile; ?>
                    <?php else : ?>
                        <div class="alert alert-warning text-center rounded-5 p-2 mt-3" role="alert">
                            در حال تولید دوره هستیم
                        </div>
                    <?php endif; wp_reset_postdata(); ?>
                </div>
            </section>
            <?php if ( ot_get_option('c4') != "off" ) {  ?>
            <section class="card rounded-3 border-0 mb-3">
                <div class="card-body">
                    <?php comments_template(); ?>
                </div>
            </section>
            <?php } ?>
        </div>
        <div class="col-md-3">
            <?php if ( ot_get_option('c10') != "off" ) {  ?>
            <div class="card border-0 rounded-4 border-0 mb-3">
                <div class="card-body">
                    <div class="text-center">
                        <figure class="avatar">
                            <?php echo get_avatar( get_the_author_meta('user_email'), '80', '' ); ?>
                        </figure>
                        <h1 class="fs-2 font-bold mb-3"><?php the_author(); ?></h1>
                    </div>
                    <?php the_author_meta('description'); ?>
                </div>
            </div>
            <?php } ?>
            <aside class="card text-center rounded-3 border-0 mb-3">
                <div class="card-body">
                    <?php if(! $is_student ) : ?>
                        <span class="cprice <?php echo $percent ? 'rm_has_discount' : '' ?>">
                            <?php if($percent) : ?>
                                <del class="text-danger font-bold fs-5"><?php echo number_format($price / 10 ); ?></del>
                            <?php endif; ?>
                            <ins class="text-success font-bold fs-1">
                                <?php if($final_price > 0) : ?>
                                    <?php echo number_format($final_price / 10 ); ?> تومان
                                <?php else : ?>
                                    رایگان
                                <?php endif; ?>
                            </ins>
                        </span>
                        <div class="d-grid gap-2 mt-4">
                            <a href="<?php echo esc_attr( $register_in_course_url ); ?>" class="btn btn-primary rounded-5" type="button"><i class="fa-duotone fa-plus"></i> خرید آنلاین دوره </a>
                        </div>
                    <?php else : ?>
                        <div class="alert alert-success rounded-5 border-0 text-center" role="alert">
                            شما دانشجوی دوره هستید
                        </div>
                    <?php endif; ?>
                </div>
            </aside>
            <aside class="card rounded-3 border-0 mb-3">
                <div class="card-body">
                    <i class="fa-duotone fa-circle-dot"></i>
                    <h1 class="titlesidebar fs-2 font-bold">مشخصات دوره</h1>
                    <div class="p-3">
                        <ul class="lists">
                            <li>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span class="font-bold">وضعیت دوره</span>
                                    </div>
                                    <div class="col-md-6">
                                        <?php
                                            $text5 = get_post_meta($post->ID, 'text5', true);
                                            if(!empty($text5)) { 
                                        ?>
                                            <?php echo $text5; ?>
                                        </span>
                                        <?php }else{ ?>
                                            در حال ضبط
                                        <?php } ?>
                                    </div>
                                </div>
                            </li>
                            <hr class="mt-2 mb-2">
                            <li>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span class="font-bold">مدت زمان دوره</span>
                                    </div>
                                    <div class="col-md-6">
                                        <span class="text-muted"><?php echo rayium_second_to_time($duration_total) ?></span>
                                    </div>
                                </div>
                            </li>
                            <hr class="mt-2 mb-2">
                            <li>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span class="font-bold">آخرین بروزرسانی</span>
                                    </div>
                                    <div class="col-md-6">
                                        <span class="text-muted"><?php if(get_the_modified_date() != get_the_date()){the_modified_date('d M y'); } ?></span>
                                    </div>
                                </div>
                            </li>
                            <hr class="mt-2 mb-2">
                            <li>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span class="font-bold">روش پشتیبانی</span>
                                    </div>
                                    <div class="col-md-6">
                                        <span class="text-muted"><?php echo $text4; ?></span>
                                    </div>
                                </div>
                            </li>
                            <hr class="mt-2 mb-2">
                            <li>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span class="font-bold">پیش نیاز دوره</span>
                                    </div>
                                    <div class="col-md-6">
                                        <span class="text-muted"><?php echo $text2; ?></span>
                                    </div>
                                </div>
                            </li>
                            <hr class="mt-2 mb-2">
                            <li>
                                <div class="row">
                                    <div class="col-md-6">
                                        <span class="font-bold">نوع دوره</span>
                                    </div>
                                    <div class="col-md-6">
                                        <span class="text-muted"><?php echo $text3; ?></span>
                                    </div>
                                </div>
                            </li>
                            <hr class="mt-2 mb-2">
                            <li>
                                <div class="row">
                                    <div class="col-md-9">
                                        <span class="font-bold">درصد تکمیل دوره</span>
                                    </div>
                                    <div class="col-md-3">
                                        <span class="text-muted"><?php echo $text6; ?>%</span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="progress mt-3" role="progressbar"
                                        aria-label="Default striped example" aria-valuenow="<?php echo $text6; ?>"
                                        aria-valuemin="0" aria-valuemax="100">
                                        <div class="progress-bar progress-bar-striped" style="width: <?php echo $text6; ?>%"></div>
                                    </div>
                                </div>
                            </li>
                            <hr class="mt-2 mb-2">
                        </ul>
                    </div>
                    <div class="count-student fs-1 text-center">
                        <span class="font-bold"><?php echo $student_count; ?></span> <span class="font-bold">دانشجو</span>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>