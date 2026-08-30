<div class="bg-light border-right" id="sidebar-wrapper">
    <div class="sidebar-heading text-center mt-3">
        <figure class="imgLogo">
            <img src="<?php echo RAYIUM_URI; ?>/img/Rayium_light.png" style="width: 200px; height: auto;" alt="" srcset="">
        </figure>
    </div>
    <div class="mt-3 p-3">
        <div class="menu-container">

            <!-- آیتم ۱ -->
            <div class="menu-item">
                <div class="menu-header" onclick="toggleMenu(this)">
                    <span class="title">محصولات</span>
                    <span class="arrow"><i class="fa-duotone fa-angles-down"></i></span>
                </div>
                <div class="sub-menu">
                    <a href="#">🔹 گوشی موبایل</a>
                    <a href="#">🔹 لپ‌تاپ</a>
                    <a href="#">🔹 تبلت</a>
                    <a href="#">🔹 هدفون</a>
                </div>
            </div>

            <!-- آیتم ۲ -->
            <div class="menu-item">
                <div class="menu-header" onclick="toggleMenu(this)">
                    <span class="title">خدمات</span>
                    <span class="arrow"><i class="fa-duotone fa-angles-down"></i></span>
                </div>
                <div class="sub-menu">
                    <a href="#">🔸 طراحی وب</a>
                    <a href="#">🔸 سئو</a>
                    <a href="#">🔸 پشتیبانی</a>
                </div>
            </div>

            <!-- آیتم ۳ -->
            <div class="menu-item">
                <div class="menu-header" onclick="toggleMenu(this)">
                    <span class="title">حساب کاربری</span>
                    <span class="arrow"><i class="fa-duotone fa-angles-down"></i></span>
                </div>
                <div class="sub-menu">
                    <a href="#">🔹 پروفایل</a>
                    <a href="#">🔹 تنظیمات</a>
                    <a href="#">🔹 خروج</a>
                </div>
            </div>

            <!-- آیتم ۴ (بدون زیرمنو) -->
            <div class="menu-item">
                <div class="menu-header" style="cursor:default;">
                    <span class="title">تماس با ما</span>
                    <span class="arrow" style="opacity:0.3;">-</span>
                </div>
            </div>

        </div>
        <?php 
            global $user_ID;
            if($user_ID){ ?>
                <div class="row">
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