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

// On plugin load, initialize classes
function init_pokemon_manager() {
    new Pokemon_Post_Type();
    new Pokemon_Meta_Boxes();
}

add_action('plugins_loaded', 'init_pokemon_manager');

function enqueue_pokemon_manager_scripts() {
    wp_enqueue_script('pokemon-admin', plugin_dir_url(__FILE__) . 'assets/js/dist/pokemon-admin.js', array('jquery'), '1.0', true);
}

add_action('admin_enqueue_scripts', 'enqueue_pokemon_manager_scripts');

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