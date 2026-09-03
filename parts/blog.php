<!-- Section Blog -->
<section class="mt-5">
    <h1 class="fs-1 font-bold mb-3">وبلاگ</h1>
    <div class="row">
        <div class="col-md-8">
            <?php
                if(have_posts()){
                    while(have_posts()){
                        the_post();
            ?>
            <article class="card rounded-3 mb-2">
                <div class="card-body">
                    <a href="<?php the_permalink(); ?>" class="">
                        <?php 
                            echo the_post_thumbnail('large', [
                                'class' => 'img-100 rounded-3 ', 
                                'loading' => 'lazy', 
                                'alt' => esc_attr(get_the_title()),
                                'decoding' => 'async',
                                'sizes' => '(max-width: 768px) 100vw, 50vw'
                            ]) 
                        ?>
                        <h2 class="fs-3 font-bold mt-3 mb-3"><?php the_title();?></h2>
                        <p class="text-muted">
                            <?php echo get_the_excerpt(); ?>
                        </p>
                    </a>
                    <div class="mt-3">
                        <?php echo getPostLikeLink( get_the_ID() ); ?>
                        <div class="float-left mt-1">
                            <i class="fa-duotone fa-comment"></i> <?php echo comments_number(); ?>
                        </div>
                    </div>
                </div>
            </article>
            <?php
                    }
                }
            ?>
            <div class="mt-3">
                <?php echo bootstrap_pagination(); ?>
            </div>
        </div>
        <?php get_sidebar('index');?>
    </div>
</section>