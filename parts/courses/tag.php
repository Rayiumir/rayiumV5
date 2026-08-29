<!-- Course Section -->
<section id="courses" class="mt-5 mb-5">
    <div class="container">
        <header class="mb-4 text-center">
            <h2 class="titlec">دوره‌ها</h2>
            <p class="text-muted">دوره‌های منتخب ما برای یادگیری مهارت‌های کاربردی</p>
        </header>
        <div class="row">
            <?php 
                $args=array(
                    'post_type'=>'course',
                    'posts_per_page'=>10,
                    'tax_query' => array(
                        array(
                            'taxonomy' => 'course_cat',
                            'field'    => 'term_id',
                            'terms'    => get_queried_object_id(),
                        ),
                    ),
                );
                $loop = new WP_Query($args); 
                while($loop->have_posts()) : 
                    $loop->the_post();
                    $final_price = rayium_get_course_final_price($post->ID);
                    $percent = rayium_get_final_discount_percent($post->ID);
                    $price = get_post_meta( $post->ID, 'rayium_price', true );
                    $duration_total = get_post_meta( $post->ID, '_duration', true );
                    $teacher_id = get_post_meta( $post->ID, 'rayium_teacter', true );
                    $teacter = get_user_by( 'ID', $teacher_id );
                    $student_count = rayium_get_student_count($post->ID);
            ?>
            <div class="col-md-3 mb-4">
                <div class="card p-3 rounded-4 border-0">
                    <figure>
                        <?php if($percent) : ?>
                            <span class="badge text-white bg-danger bap fs-6 rounded-4 mb-3 position-absolute mt-2 me-2">
                                <i class="fa-duodone fa-percent"></i><?php echo $percent; ?> تخفیف
                            </span>
                        <?php endif; ?>
                        <?php echo the_post_thumbnail('medium', ['class' => 'img-fluid rounded-4', 'loading' => 'lazy', 'alt' => esc_attr( get_the_title() ), 'title' => esc_attr( get_the_title() )]) ?>
                    </figure>
                    <div class="mt-1">
                        <?php
                            $text7 = get_post_meta($post->ID, 'text7', true);
                            if(!empty($text7)) { 
                        ?>
                        <span class="badge text-white bg-primary bap2 rounded-4 mb-3">
                            <i class="fa-duotone fa-signal-bars-good"></i> <?php echo $text7; ?>
                        </span>
                        <?php } ?>
                        <?php
                            $text5 = get_post_meta($post->ID, 'text5', true);
                            if(!empty($text5)) { 
                        ?>
                        <span class="badge text-white bg-secondary bap3 rounded-4 mb-3">
                            <i class="fa-duotone fa-circle-dot"></i> <?php echo $text5; ?>
                        </span>
                        <?php }else{ ?>
                            <span class="badge text-white bg-success bap3 rounded-4 mb-3">
                                <i class="fa-duotone fa-circle-dot"></i> در حال ضبط
                            </span>
                        <?php } ?>
                        <h2 class="fs-5 fw-bold mb-4"><?php the_title() ?></h2>
                        <div class="row">
                            <div class="col times font-bold"><i class="fa-duotone fa-timer"></i> <?php echo rayium_second_to_time($duration_total) ?> </div>
                            <div class="col text-left counts font-bold"><i class="fa-duotone fa-users"></i> <?php echo $student_count; ?> دانشجو </div>
                        </div>
                    </div>
                    <span class="text-center cprice <?php echo $percent ? 'rm_has_discount' : '' ?>">
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
                    <a href="<?php the_permalink() ?>" class="btn btn-primary rounded-5">مشاهده دوره</a>
                </div>
            </div>
            <?php endwhile; wp_reset_query(); ?>
        </div>
        <div class="mt-3">
            <?php echo bootstrap_pagination(); ?>
        </div>
    </div>
</section>