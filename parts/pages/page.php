<!-- Section Blog -->
<section class="mt-5">
    <h1 class="fs-2 font-bold mb-3">برگه ها</h1>
    <div class="row">
        <div class="col-md-8">
            <?php
                if(have_posts()){
                    while(have_posts()){
                        the_post();
            ?>
            <article class="card rounded-3 mb-2">
                <div class="card-body">
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
                    <h2 class="fs-3 font-bold mt-3 mb-3"><?php the_title();?></h2>
                    <p class="text-muted">
                        <?php echo get_the_content(); ?>
                    </p>
                    <div class="mt-3">
                        <i class="fa-duotone fa-solid fa-list-tree"></i> <?php the_category(',') ?>
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
            <?php
                    }
                }
            ?>
        </div>
        <?php get_sidebar('index');?>
    </div>
</section>