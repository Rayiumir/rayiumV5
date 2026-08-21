<!-- Section Blog -->
<section class="mt-5">
    <h1 class="fs-2 font-bold mb-3">وبلاگ</h1>
    <div class="row">
        <div class="col-md-8">
            <article class="card rounded-3 mb-2">
                <div class="card-body">
                    <?php echo the_post_thumbnail('full', ['class' => 'img-100 rounded-3']);?>
                    <h2 class="fs-3 font-bold mt-3 mb-3"><?php the_title();?></h2>
                    <?php the_content(); ?>

                    <div class="text-center mt-4 mb-4">
                        <?php
                            $wordpress = get_post_meta($post->ID, 'wordpress', true);
                            $github = get_post_meta($post->ID, 'github', true);
                            $download = get_post_meta($post->ID, 'download', true);
                            $eyes = get_post_meta($post->ID, 'eyes', true);
                            $links = get_post_meta($post->ID, 'links', true);
                        ?>

                        <?php if(!empty($wordpress)){ ?>
                            <a href="<?php echo $wordpress; ?>" class="btn btn-outline-info rounded-5"><i class="fa-brands fa-wordpress"></i> مخزن وردپرس </a>
                        <?php
                            } 
                        ?>

                        <?php if(!empty($github)){ ?>
                            <a href="<?php echo $github; ?>" class="btn btn-outline-dark rounded-5"><i class="fa-brands fa-github"></i> گیت هاب </a>
                        <?php
                            } 
                        ?>

                        <?php if(!empty($download)){ ?>
                            <a href="<?php echo $download; ?>" class="btn btn-outline-primary rounded-5"><i class="fa-duotone fa-download"></i> دانلود فایل </a>
                        <?php
                            } 
                        ?>

                        <?php if(!empty($eyes)){ ?>
                            <a href="<?php echo $eyes; ?>" class="btn btn-outline-success rounded-5"><i class="fa-duotone fa-eye"></i> پیش نمایش </a>
                        <?php
                            } 
                        ?>
                    </div>
                    <?php if(!empty($links)){ ?>
                        <a href="<?php echo $links; ?>">
                            <span class="badge bg-secondary text-white mb-2"><i class="fa-duotone fa-link"></i> پیوند منابع</span>
                        </a>
                    <?php
                        } 
                    ?>
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
            <section class="card rounded-3 mb-2">
                <div class="card-body">
                    <i class="fa-duotone fa-tags"></i> <?php the_tags('برچسب ها : ', ', ', ''); ?>
                </div>
            </section>
            <section class="card rounded-4 border-0 mb-3">
                <div class="card-body">
                    <div class="fs-2 mb-3 font-bold">
                        <i class="fa-duotone fa-list-radio"></i> <span class="font-bold">نوشته های مرتبط</span>
                    </div>
                    <ul class="lists">
                        <?php
                            $custom_terms = wp_get_post_terms($post->ID, 'category');

                            if( $custom_terms ){
                                $tax_query = array();
                                foreach( $custom_terms as $custom_term ) {

                                    $tax_query[] = array(
                                        'taxonomy' => 'category',
                                        'field' => 'slug',
                                        'terms' => $custom_term->slug,
                                    );

                                }
                                $args = array(
                                    'post_type' => 'post',
                                    'posts_per_page' => 10,
                                    'tax_query' => $tax_query
                                );
                                $loop = new WP_Query($args);
                                if( $loop->have_posts() ) {
                                    while( $loop->have_posts() ) : $loop->the_post(); 
                        ?>
                        <li class="mb-2">
                            <a href="<?php the_permalink() ?>" class="text-decoration-none" title="<?php the_title() ?>">
                                <?php the_title() ?> 
                                <span class="float-left">
                                    <i class="fa-duotone fa-eye"></i> 
                                    <?php
                                        if ( function_exists( 'get_post_view_count' ) ) {
                                            echo get_post_view_count( get_the_ID() );
                                        }
                                    ?>
                                </span>
                            </a>
                        </li>
                        <hr class="mb-2">
                        <?php
                        endwhile;
                                }
                                wp_reset_query();
                            }
                        ?>
                    </ul>
                </div>
            </section>
            <section class="card rounded-4 border-0 mb-3">
                <div class="card-body">
                    <div class="fs-2 mb-3 font-bold">
                        <i class="fa-duotone fa-comments"></i> <span class="font-bold"> نظرات </span>
                    </div>
                    <?php comments_template();?>
                </div>
            </section>
        </div>
        <?php get_sidebar();?>
    </div>
</section>