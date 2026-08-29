<!-- Tag Section -->
<section class="mt-5 mb-5">
    <header class="mb-4 text-center">
        <h2 class="titlec">دسته بندی نوشته ها</h2>
        <p class="text-muted">آخرین نوشته ها و نکات آموزشی ما</p>
    </header>
    <div class="row">
        <?php 
            if(have_posts()){ 
                while(have_posts()){
                the_post();
        ?>
        <div class="col-md-3 mb-4">
            <article class="card p-3 rounded-4">
                <figure>
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
                <h2 class="fs-2 fw-bold mt-3 mb-4"><?php the_title() ?></h2>
                <div class="d-flex justify-content-between align-items-center">
                    <span><?php echo getPostLikeLink( get_the_ID() ); ?></span>
                    <span class="fs-3 text-muted"><i class="fa-duotone fa-comment"></i> <?php echo get_comments_number(); ?></span>
                </div>
                <a href="<?php the_permalink() ?>" class="btn btn-primary rounded-5 mt-3">مشاهده پست</a>
            </article>
        </div>
        <?php 
            }
        }
        ?>
    </div>
    <div class="mt-3">
        <?php echo bootstrap_pagination(); ?>
    </div>
</section>