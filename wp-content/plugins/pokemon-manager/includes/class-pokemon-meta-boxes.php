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
        // Fetch all post meta data at once
        $meta_data = get_post_meta($post->ID);

        // Nonce for verification
        wp_nonce_field('pokemon_save_data', 'pokemon_meta_box_nonce');

        // Define fields and their respective labels
        $fields = [
            'primary_type' => 'Primary Type',
            'secondary_type' => 'Secondary Type',
            'weight' => 'Weight',
            'old_pokedex_number' => 'Old Pokedex Number',
            'old_pokedex_name' => 'Old Pokedex Game',
            'recent_pokedex_number' => 'Recent Pokedex Number',
            'recent_pokedex_name' => 'Recent Pokedex Game',
        ];

        $output = '';

        foreach ($fields as $field => $label) {
            $value = isset($meta_data["_$field"]) ? esc_attr($meta_data["_$field"][0]) : '';
            $output .= '<label for="' . $field . '">' . $label . ':</label>';
            $output .= '<input type="text" id="' . $field . '" name="' . $field . '" value="' . $value . '"><br>';
        }

        $output .= '<div id="attacks_container">';

        // Load the attacks if exists
        if(empty(unserialize(get_post_meta($post->ID, "_attacks", true)))){
            $output .= $this->generateAttackField();
        }else{
            $array_data = unserialize(get_post_meta($post->ID, "_attacks", true));

            foreach ($array_data as $key => $item) {
                $output .= $this->generateAttackField($key, $item['name'], $item['description']);
            }
        }

        $output .= '</div>'; // #attacks_container
        $output .= '<button type="button" id="add_attack">Add Another Attack</button>';

        echo $output;
    }

    private function generateAttackField($index = 0, $nameValue = '', $descriptionValue = '') {
        return '<div class="attack_field_group">
            <input type="text" id="attacks[' . $index . '][name]" name="attacks[' . $index . '][name]" value="' . esc_attr($nameValue) . '">
            <input type="text" id="attacks[' . $index . '][description]" name="attacks[' . $index . '][description]" value="' . esc_attr($descriptionValue) . '">
        </div>';
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

        // List of meta keys and their corresponding input names
        $meta_keys = [
            '_primary_type' => 'primary_type',
            '_secondary_type' => 'secondary_type',
            '_weight' => 'weight',
            '_old_pokedex_number' => 'old_pokedex_number',
            '_old_pokedex_name' => 'old_pokedex_name',
            '_recent_pokedex_number' => 'recent_pokedex_number',
            '_recent_pokedex_name' => 'recent_pokedex_name'
        ];

        // Update post meta for each key
        foreach ($meta_keys as $meta_key => $input_name) {
            if (isset($_POST[$input_name])) {
                $value = sanitize_text_field($_POST[$input_name]);
                update_post_meta($post_id, $meta_key, $value);
            }
        }

        // Handle attacks
        $attacks = (isset($_POST['attacks']) && is_array($_POST['attacks'])) ? $_POST['attacks'] : [];
        update_post_meta($post_id, '_attacks', maybe_serialize($attacks));
    }
}