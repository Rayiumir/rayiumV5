<!-- Section Blog -->
<section class="mt-5">
    <h2 class="font-bold mb-3">اطلاعات نویسنده</h2>
    <div class="row">

        <div class="col-md-8">
            <div class="card rounded-4 mb-2">
                <div class="card-body"> 
                    <div class="text-center">
                        <figure class="avatar">
                            <?php echo get_avatar( get_the_author_meta('user_email'), '150', '' ); ?>
                        </figure>
                        <h3 class="font-bold mt-3 mb-3"><?php the_author(); ?></h3>
                    </div>
                    <?php the_author_meta('description'); ?>
                    <div class="text-center mt-3">
                        <?php
                            if ( get_the_author_meta( 'linkedin' ) ) {
                                echo '<a href="' . esc_url( get_the_author_meta( 'linkedin' ) ) . '" class="ms-2"><i class="fa-brands fa-linkedin fa-2x"></i></a>';
                            }
                        ?>
                        <?php
                            if ( get_the_author_meta( 'github' ) ) {
                                echo '<a href="' . esc_url( get_the_author_meta( 'github' ) ) . '" class="ms-2"><i class="fa-brands fa-github fa-2x"></i></a>';
                            }
                        ?>
                        <?php
                            if ( get_the_author_meta( 'cv' ) ) {
                                echo '<a href="' . esc_url( get_the_author_meta( 'cv' ) ) . '" class="ms-2"><i class="fa-duotone fa-file-user fa-2x"></i></a>';
                            }
                        ?>
                        <?php
                            if ( get_the_author_meta( 'whatsapp' ) ) {
                                echo '<a href="' . esc_url( get_the_author_meta( 'whatsapp' ) ) . '" class="ms-2"><i class="fa-brands fa-whatsapp fa-2x"></i></a>';
                            }
                        ?>
                        <?php
                            if ( get_the_author_meta( 'twitter' ) ) {
                                echo '<a href="' . esc_url( get_the_author_meta( 'twitter' ) ) . '" class="ms-2"><i class="fa-brands fa-square-x-twitter fa-2x"></i></a>';
                            }
                        ?>
                        <?php
                            if ( get_the_author_meta( 'telegram' ) ) {
                                echo '<a href="' . esc_url( get_the_author_meta( 'telegram' ) ) . '" class="ms-2"><i class="fa-brands fa-telegram fa-2x"></i></a>';
                            }
                        ?>
                        <?php
                            if ( get_the_author_meta( 'instagram' ) ) {
                                echo '<a href="' . esc_url( get_the_author_meta( 'instagram' ) ) . '" class="ms-2"><i class="fa-brands fa-instagram fa-2x"></i></a>';
                            }
                        ?>
                    </div>
                </div>
            </div>
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