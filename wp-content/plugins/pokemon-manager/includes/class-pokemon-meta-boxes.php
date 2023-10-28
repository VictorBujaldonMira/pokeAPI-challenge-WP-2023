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
        $old_pokedex_name = get_post_meta($post->ID, '_old_pokedex_name', true);
        $recent_pokedex_number = get_post_meta($post->ID, '_recent_pokedex_number', true);
        $recent_pokedex_name = get_post_meta($post->ID, '_recent_pokedex_name', true);

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

        echo '<label for="old_pokedex_game">Old Pokedex Game:</label>';
        echo '<input type="text" id="old_pokedex_name" name="old_pokedex_name" value="' . esc_attr($old_pokedex_name) . '"><br>';

        echo '<label for="recent_pokedex_number">Recent Pokedex Number:</label>';
        echo '<input type="text" id="recent_pokedex_number" name="recent_pokedex_number" value="' . esc_attr($recent_pokedex_number) . '"><br>';

        echo '<label for="recent_pokedex_name">Recent Pokedex Game:</label>';
        echo '<input type="text" id="recent_pokedex_name" name="recent_pokedex_name" value="' . esc_attr($recent_pokedex_name) . '"><br>';

        echo '<div id="attacks_container">';

        // Load the attacks if exists
        if(empty(unserialize(get_post_meta($post->ID, "_attacks", true)))){
            echo '<div class="attack_field_group">';
            echo '<label for="attacks[0][name]">Attack Name:</label>';
            echo '<input type="text" id="attacks[0][name]" name="attacks[0][name]">';

            echo '<label for="attacks[0][description]">Attack Description:</label>';
            echo '<input type="text" id="attacks[0][description]" name="attacks[0][description]">';
            echo '</div>'; // .attack_field_group
        }else{
            $array_data = unserialize(get_post_meta($post->ID, "_attacks", true));

            foreach ($array_data as $key => $item) {
                echo '<div class="attack_field_group">';
                echo '<input type="text" id="attacks['.$key.'][name]" name="attacks['.$key.'][name]" value="' . $item['name'] . '">';

                echo '<input type="text" id="attacks['.$key.'][description]" name="attacks['.$key.'][description]" value="' . $item['description'] . '">';
                echo '</div>'; // .attack_field_group
            }
        }
        echo '</div>'; // #attacks_container

        echo '<button type="button" id="add_attack">Add Another Attack</button>';
    }

    public function save_post_data($post_id) {
        // Check nonce for security
        if (!isset($_POST['pokemon_meta_box_nonce']) || !wp_verify_nonce($_POST['pokemon_meta_box_nonce'], 'pokemon_save_data')) {
            return;
        }

        // Check if not autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check permissions
        if ('pokemon' !== $_POST['post_type'] || !current_user_can('edit_post', $post_id)) {
            return;
        }

        // Save meta data
        update_post_meta($post_id, '_primary_type', sanitize_text_field($_POST['primary_type']));
        update_post_meta($post_id, '_secondary_type', sanitize_text_field($_POST['secondary_type']));
        update_post_meta($post_id, '_weight', sanitize_text_field($_POST['weight']));
        update_post_meta($post_id, '_old_pokedex_number', sanitize_text_field($_POST['old_pokedex_number']));
        update_post_meta($post_id, '_old_pokedex_name', sanitize_text_field($_POST['old_pokedex_name']));
        update_post_meta($post_id, '_recent_pokedex_number', sanitize_text_field($_POST['recent_pokedex_number']));
        update_post_meta($post_id, '_recent_pokedex_name', sanitize_text_field($_POST['recent_pokedex_name']));

        $attacks = isset($_POST['attacks']) && is_array($_POST['attacks']) ? $_POST['attacks'] : array();
        update_post_meta($post_id, '_attacks', maybe_serialize($attacks));
    }
}