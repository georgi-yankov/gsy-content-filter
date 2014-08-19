<?php

if (!class_exists('GSY_Content_Filter')) {

    class GSY_Content_Filter {

        public function __construct() {
            add_action('admin_enqueue_scripts', array($this, 'add_styles'));
            add_action('admin_enqueue_scripts', array($this, 'add_scripts'));
        }

        public function init() {
            
        }

        public function add_styles() {
            $src = plugins_url('../css/style.css', __FILE__);
            wp_enqueue_style('gsy-content-filter-style', $src);
        }

        public function add_scripts() {
            $src = plugins_url('../js/script.js', __FILE__);
            wp_enqueue_script('gsy-content-filter-script', $src, array('jquery'), false, true);
        }

    }

}