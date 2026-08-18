<?php

define("RAYIUM_URI", get_template_directory_uri());
define("RAYIUM_PATH", get_template_directory() . DIRECTORY_SEPARATOR);
define("RAYIUM_STYLE", get_stylesheet_uri());
define("RAYIUM_INC", RAYIUM_PATH . "inc/");
define("RAYIUM_ADMIN", RAYIUM_INC . "admin/");

require_once RAYIUM_INC . "enqueue.php";
