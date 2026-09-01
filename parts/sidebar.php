<div class="bg-light border-right" id="sidebar-wrapper">
    <div class="sidebar-heading text-center mt-3">
        <figure class="imgLogo">
            <img src="<?php echo RAYIUM_URI; ?>/img/Rayium_light.png" style="width: 200px; height: auto;" alt="" srcset="">
        </figure>
    </div>
    <div class="mt-3 p-3">
        <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-outline-primary btn-block rounded-5 mb-4">
            <i class="fa-duotone fa-home"></i>
            خانه 
        </a>
        <?php
            wp_nav_menu(array(
                'theme_location' => 'primary-menu',
                'walker'         => new Navbar_Walker(),
                'container'      => 'div',
                'container_class'=> 'menu-container mb-4',
                'depth'          => 2,
                'fallback_cb'    => false
            ));
        ?>
        <?php 
            global $user_ID;
            if($user_ID){ ?>
                <div class="row mt-4">
                    <div class="col-6">
                        <a href="<?php echo site_url(); ?>/customers" class="btn btn-outline-success fs-1 btn-block rounded-5 ms-2" title="نمایه کاربر">
                            <i class="fa-duotone fa-user"></i>
                        </a>
                    </div>
                    <div class="col-6">
                        <a href="<?php echo site_url(); ?>/wp-login.php?action=logout" class="btn btn-outline-danger fs-1 btn-block rounded-5" title="خروج">
                            <i class="fa-duotone fa-sign-out"></i>
                        </a>
                    </div>
                </div>
        <?php }else{ ?>
                <?php echo do_shortcode("[authora-login]"); ?>
        <?php } ?>
    </div>
</div>