<?php

class Navbar_Walker extends Walker_Nav_Menu {
    
    public function start_lvl(&$output, $depth = 0, $args = array()) {
        $output .= '<div class="sub-menu">';
    }

    public function end_lvl(&$output, $depth = 0, $args = array()) {
        $output .= '</div>';
    }

    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
        $has_children = !empty($item->classes) && in_array('menu-item-has-children', $item->classes);
        $icon = $depth == 0 ? '🔹' : '▫️';
        
        $output .= '<div class="menu-item">';
        $output .= '<div class="menu-header" onclick="toggleMenu(this)">';
        
        $attributes = '';
        if (!empty($item->attr_title)) {
            $attributes .= ' title="' . esc_attr($item->attr_title) . '"';
        }
        if (!empty($item->target)) {
            $attributes .= ' target="' . esc_attr($item->target) . '"';
        }
        if (!empty($item->xfn)) {
            $attributes .= ' rel="' . esc_attr($item->xfn) . '"';
        }
        if (!empty($item->url)) {
            $attributes .= ' href="' . esc_url($item->url) . '"';
        }
        
        if (!empty($item->url)) {
            $output .= '<a' . $attributes . ' class="menu-link">';
        }
        
        $output .= '<span class="title">' . esc_html($item->title) . '</span>';
        
        if (!empty($item->url)) {
            $output .= '</a>';
        }
        
        $output .= '<span class="arrow">';
        
        if ($has_children) {
            $output .= '<i class="fa-duotone fa-angles-down"></i>';
        } else {
            $output .= '<span>'.esc_html($icon).'</span>';
        }
        
        $output .= '</span>';
        $output .= '</div>';
        
        if (!$has_children) {
            $output .= '</div>';
        }
    }

    public function end_el(&$output, $item, $depth = 0, $args = array()) {
        if (!empty($item->classes) && in_array('menu-item-has-children', $item->classes)) {
            $output .= '</div>';
        }
    }
}