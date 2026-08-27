<!-- Section Courses -->
    <h1 class="fs-2 font-bold mb-3">دوره آموزشی</h1>
    <div class="row">
        <?php 
            $args=array(
                'post_type'=>'course',
                'posts_per_page'=>4
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
        <div class="col-3">
            <div class="card rounded-3">
                <div class="card-body">
                    <figure>
                        <?php if($percent) : ?>
                            <span class="badge text-white bg-danger bap fs-6 rounded-4 mb-3 position-absolute mt-2 me-2">
                                <i class="fa-duodone fa-percent"></i><?php echo $percent; ?> تخفیف
                            </span>
                        <?php endif; ?>
                        <?php 
                            echo the_post_thumbnail('large', [
                                'class' => 'img-100 rounded-3 ', 
                                'loading' => 'lazy', 
                                'alt' => esc_attr(get_the_title()),
                                'decoding' => 'async',
                                'sizes' => '(max-width: 768px) 100vw, 50vw'
                            ]) 
                        ?>
                        </figure>
                    <h2 class="fs-3 mt-3 mb-3"><?php the_title(); ?></h2>
                    <div class="text-center cprice <?php echo $percent ? 'rm_has_discount' : '' ?>">
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
                    </div>
                    <a href="<?php the_permalink() ?>" class="btn btn-primary btn-block mt-3 rounded-5">
                        مشاهده دوره
                    </a>
                </div>
            </div>
        </div>
        <?php endwhile; wp_reset_query(); ?>
    </div>