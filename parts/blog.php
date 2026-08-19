<!-- Section Blog -->
    <div class="mt-5">
        <h1 class="fs-2 font-bold mb-3">وبلاگ</h1>
        <div class="row">
            <div class="col-md-8">
                <?php
                    if(have_posts()){
                        while(have_posts()){
                            the_post();
                ?>
                <div class="card rounded-3 mb-2">
                    <div class="card-body">
                        <?php echo the_post_thumbnail('full', ['class' => 'img-100 rounded-3']);?>
                        <h2 class="fs-3 font-bold mt-3 mb-3"><?php the_title();?></h2>
                        <p class="text-muted">
                            <?php echo get_the_excerpt(); ?>
                        </p>
                        <div class="mt-3">
                            <?php echo getPostLikeLink( get_the_ID() ); ?>
                            <div class="float-left">
                                <i class="fa-duotone fa-comment"></i> <?php echo comments_number(); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                        }
                    }
                ?>
            </div>
            <div class="col-md-4">
                <div class="card rounded-3 mb-3">
                    <div class="card-body">
                        <h2 class="fs-3 font-bold"><i class="fa-duotone fa-circle-dot"></i> آخرین نوشته
                        </h2>
                        <ul class="mt-3">
                            <li class="mb-2">
                                <a href="#">آیا گوگل ناخواسته در حال ایجاد فرصت برای HarmonyOS است؟</a>

                            </li>
                            <hr class="mb-2">
                            <li class="mb-2">
                                <a href="#">آیا گوگل ناخواسته در حال ایجاد فرصت برای HarmonyOS است؟</a>
                            </li>
                            <hr class="mb-2">
                            <li class="mb-2">
                                <a href="#">آیا گوگل ناخواسته در حال ایجاد فرصت برای HarmonyOS است؟</a>
                            </li>
                            <hr class="mb-2">
                            <li class="mb-2">
                                <a href="#">آیا گوگل ناخواسته در حال ایجاد فرصت برای HarmonyOS است؟</a>
                            </li>
                            <hr class="mb-2">
                            <li class="mb-2">
                                <a href="#">آیا گوگل ناخواسته در حال ایجاد فرصت برای HarmonyOS است؟</a>
                            </li>
                            <hr class="mb-2">
                            <li class="mb-2">
                                <a href="#">آیا گوگل ناخواسته در حال ایجاد فرصت برای HarmonyOS است؟</a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="card rounded-3 mb-3">
                    <div class="card-body">
                        <h2 class="fs-3 font-bold"><i class="fa-duotone fa-comments"></i> آخرین نظرات </h2>
                        <ul class="mt-3">
                            <li class="mb-2">
                                <a href="#">آیا گوگل ناخواسته در حال ایجاد فرصت برای HarmonyOS است؟</a>

                            </li>
                            <hr class="mb-2">
                            <li class="mb-2">
                                <a href="#">آیا گوگل ناخواسته در حال ایجاد فرصت برای HarmonyOS است؟</a>
                            </li>
                            <hr class="mb-2">
                            <li class="mb-2">
                                <a href="#">آیا گوگل ناخواسته در حال ایجاد فرصت برای HarmonyOS است؟</a>
                            </li>
                            <hr class="mb-2">
                            <li class="mb-2">
                                <a href="#">آیا گوگل ناخواسته در حال ایجاد فرصت برای HarmonyOS است؟</a>
                            </li>
                            <hr class="mb-2">
                            <li class="mb-2">
                                <a href="#">آیا گوگل ناخواسته در حال ایجاد فرصت برای HarmonyOS است؟</a>
                            </li>
                            <hr class="mb-2">
                            <li class="mb-2">
                                <a href="#">آیا گوگل ناخواسته در حال ایجاد فرصت برای HarmonyOS است؟</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>