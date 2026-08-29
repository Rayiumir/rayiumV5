<?php get_header(); ?>
    <div class="d-flex" id="wrapper">
        <?php echo get_template_part('parts/sidebar'); ?>
        <div id="page-content-wrapper">
            <?php echo get_template_part('parts/navbar'); ?>
             <main class="container mt-5">
                <?php echo get_template_part('parts/courses'); ?>
                <?php echo get_template_part('parts/posts/archive'); ?>
             </main>
        </div>
    </div>
<?php get_footer();?>