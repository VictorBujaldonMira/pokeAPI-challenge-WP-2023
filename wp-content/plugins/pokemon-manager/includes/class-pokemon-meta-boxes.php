<?php

class Pokemon_Meta_Boxes {

    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_post_data'));
    }

    public function add_meta_boxes() {
        // Register metaboxes
        add_meta_box('pokemon_details', 'Pokémon Details', array($this, 'display_meta_boxes'), 'pokemon', 'normal', 'high');
    }

    public function display_meta_boxes($post) {
        // Fetch current meta values
        $primary_type = get_post_meta($post->ID, '_primary_type', true);
        $secondary_type = get_post_meta($post->ID, '_secondary_type', true);
        $weight = get_post_meta($post->ID, '_weight', true);
        $old_pokedex_number = get_post_meta($post->ID, '_old_pokedex_number', true);
        $recent_pokedex_number = get_post_meta($post->ID, '_recent_pokedex_number', true);
        $attacks = get_post_meta($post->ID, '_attacks', true);

        // Nonce for verification
        wp_nonce_field('pokemon_save_data', 'pokemon_meta_box_nonce');

        // Display the fields
        echo '<label for="primary_type">Primary Type:</label>';
        echo '<input type="text" id="primary_type" name="primary_type" value="' . esc_attr($primary_type) . '"><br>';

        echo '<label for="secondary_type">Secondary Type:</label>';
        echo '<input type="text" id="secondary_type" name="secondary_type" value="' . esc_attr($secondary_type) . '"><br>';

        echo '<label for="weight">Weight:</label>';
        echo '<input type="text" id="weight" name="weight" value="' . esc_attr($weight) . '"><br>';

        echo '<label for="old_pokedex_number">Old Pokedex Number:</label>';
        echo '<input type="text" id="old_pokedex_number" name="old_pokedex_number" value="' . esc_attr($old_pokedex_number) . '"><br>';

        echo '<label for="recent_pokedex_number">Recent Pokedex Number:</label>';
        echo '<input type="text" id="recent_pokedex_number" name="recent_pokedex_number" value="' . esc_attr($recent_pokedex_number) . '"><br>';

        echo '<label for="attacks">Attacks (JSON format):</label>';
        echo '<textarea id="attacks" name="attacks" rows="5" cols="50">' . esc_textarea($attacks) . '</textarea>';
    }

    public function save_post_data($post_id) {
    }
}