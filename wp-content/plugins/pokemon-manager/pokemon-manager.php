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