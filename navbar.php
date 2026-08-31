<?php

class Navbar_Walker extends Walker_Nav_Menu {
    
    public function start_lvl(&$output, $depth = 0, $args = array()) {
        $output .= '<div class="sub-menu">';
    }

    public function end_lvl(&$output, $depth = 0, $args = array()) {
        $output .= '</div>';
    }

    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $has_children = !empty($args->walker->has_children);
        $icon = $depth == 0 ? '🔹' : '▫️';
        
        $output .= '<div class="menu-item">';
        $output .= '<div class="menu-header" onclick="toggleMenu(this)">';
        $output .= '<span class="title">' . esc_html($item->title) . '</span>';
        $output .= '<span class="arrow">';
        
        if ($has_children) {
            $output .= '<i class="fa-duotone fa-angles-down"></i>';
        } else {
            $output .= '<span style="opacity:0.3;">-</span>';
        }
        
        $output .= '</span>';
        $output .= '</div>';
        
        // If no children, close the menu-item div here
        if (!$has_children) {
            $output .= '</div>';
        }
    }

    public function end_el(&$output, $item, $depth = 0, $args = array()) {
        // Close menu-item if it has children (started in start_el)
        if (!empty($args->walker->has_children)) {
            $output .= '</div>';
        }
    }
}

