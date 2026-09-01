<nav class="navbar navbar-expand-lg navbar-light p-3">
    <a href="#" id="menu-toggle">
        <span class="navbar-toggler-icon"></span>
    </a>

    <div class="d-flex">
        <button class="btn btn-outline-dark rounded-5" id="dark-button">
            تاریک 
            <i class="fa-duotone fa-moon"></i>
        </button>
        <button class="btn btn-outline-primary rounded-5" id="light-button" style="display:none;">
            روشن
            <i class="fa-duotone fa-sun"></i>
        </button>
        <form role="search" action="<?php echo esc_url(home_url('/')); ?>" method="get">
            <input class="form-control me-2 rounded-5" name="s" type="search" placeholder="جستجو در سایت ..."
                aria-label="Search" />
        </form>
    </div>
</nav>