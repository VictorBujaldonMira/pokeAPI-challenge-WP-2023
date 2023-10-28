<?php
/*
Plugin Name: Pokémon Manager
Description: Plugin to manage Pokémon information.
Version: 1.0
Author: Víctor
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Load classes
require_once plugin_dir_path(__FILE__) . 'includes/class-pokemon-post-type.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-pokemon-meta-boxes.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-pokemon-ajax.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-pokemon-shortcodes.php';

// On plugin load, initialize classes
function init_pokemon_manager() {
    new Pokemon_Post_Type();
    new Pokemon_Meta_Boxes();
}

add_action('plugins_loaded', 'init_pokemon_manager');

// Enqueue scripts in admin
function enqueue_pokemon_manager_scripts() {
    wp_enqueue_script('pokemon-admin', plugin_dir_url(__FILE__) . 'assets/js/dist/pokemon-admin.js', array('jquery'), '1.0', true);
}

add_action('admin_enqueue_scripts', 'enqueue_pokemon_manager_scripts');

// Call single-pokemon template
function single_pokemon_template($single_template) {
    global $post;

    if ($post->post_type == 'pokemon') {
        if ( $override = locate_template( 'single-pokemon.php' ) ) {
            $single_template = $override;
        } else {
            $single_template = plugin_dir_path(__FILE__) . 'templates/single-pokemon.php';
        }
    }

    return $single_template;
}

add_filter('single_template', 'single_pokemon_template');

// Enqueue styles and scripts
function enqueue_styles_and_scripts(){
    wp_register_style('pokemon-style', plugin_dir_url(__FILE__) . 'assets/css/dist/app.min.css', array(), '1.0');
    wp_enqueue_style('pokemon-style');

    wp_enqueue_script('pokemon-ajax', plugin_dir_url(__FILE__) . 'assets/js/dist/pokemon-ajax.js', array(), '1.0', true);

    wp_add_inline_script('pokemon-ajax', 'window.ajaxurl = "' . admin_url('admin-ajax.php') . '";', 'before');

    wp_enqueue_script('pokemon-filter', plugin_dir_url(__FILE__) . 'assets/js/dist/pokemon-filter.js', array(), '1.0', true);
}

add_action('wp_enqueue_scripts', 'enqueue_styles_and_scripts');