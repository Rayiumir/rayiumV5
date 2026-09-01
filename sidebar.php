<div class="card rounded-3 mb-3">
    <div class="card-body">
        <h2 class="fs-3 font-bold"><i class="fa-duotone fa-circle-dot"></i> آخرین نوشته ها
        </h2>
        <div class="mt-3">
            <ul class="lists">
                <?php 
                    $recent_post= new WP_Query(
                        array(
                            'post_type' => 'post',
                            'post_status' => 'publish',
                            'order' => 'DESC',
                            'orderby' => 'ID',
                            'posts_per_page' =>'10',
                        )
                    );
                    if($recent_post->have_posts()) :
                        while($recent_post->have_posts()) : 
                        $recent_post->the_post();
                ?> 
                <li> 
                    <a href="<?php the_permalink(); ?>" target="_blank"><?php the_title(); ?> 
                        <div class="float-left">
                            <i class="fa-duotone fa-arrow-left"></i>
                        </div>
                    </a>
                </li>
                <hr class="mt-2 mb-2">
                <?php 
                    endwhile;
                    endif;
                    wp_reset_query(); 
                ?>
            </ul>
        </div>
    </div>
</div>

<div class="card rounded-3 mb-3">
    <div class="card-body">
        <h2 class="fs-3 font-bold"><i class="fa-duotone fa-eye"></i> پربازدید ترین مقالات </h2>
        <div class="mt-3">
            <ul class="lists">
                <?php 
                    $hgw_pupolar_args = array( 
                        'posts_per_page' => 10, 
                        'meta_key' => 'post_view_count', 
                        'orderby' => 'meta_value_num', 
                        'order' => 'DESC'  
                    );
                    $hgw_popular_posts = new WP_Query( $hgw_pupolar_args );

                    while ( $hgw_popular_posts->have_posts() ) : $hgw_popular_posts->the_post();
                ?> 
                <li>
                    <a href="<?php the_permalink(); ?>" target="_blank" class="text-decoration-none">
                        <?php the_title(); ?> 
                        <div class="float-left">
                            <i class="fa-duotone fa-eye"></i>
                            <span>
                                <?php
                                    if ( function_exists( 'get_post_view_count' ) ) {
                                        echo get_post_view_count( get_the_ID() );
                                    }
                                ?>
                            </span>
                        </div>
                    </a>
                </li>
                <hr class="mt-2 mb-2">
                <?php 
                endwhile;
                ?>
            </ul>
        </div>
    </div>
</div>

<div class="card rounded-3 mb-3">
    <div class="card-body">
        <h2 class="fs-3 font-bold"><i class="fa-duotone fa-comments"></i> آخرین نظرات </h2>
        <div class="mt-3">
            <ul class="lists mt-3">
                <?php 
                    $comments = get_comments('status=approve&number=10');
                    foreach ($comments as $comment) { 
                ?>
                    <li class="">
                        <h6 class="fs-6 mt-3"><?php echo strip_tags($comment->comment_author); ?> می گوید : </h6>
                        <p class="fs-6 mt-3"><?php echo strip_tags($comment->comment_content); ?></p>
                    </li>
                    <hr class="mt-2 mb-2">
                <?php }  ?>
            </ul>
        </div>
    </div>
</div>

<?php dynamic_sidebar("sidebar"); ?>