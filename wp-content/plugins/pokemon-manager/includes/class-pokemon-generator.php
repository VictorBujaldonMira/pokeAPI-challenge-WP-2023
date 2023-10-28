<?php

class Pokemon_Generator {

    public function __construct() {
        add_action('init', array($this, 'add_generate_pokemon_rewrite_rule'));
        add_filter('query_vars', array($this, 'add_generate_pokemon_query_var'));
        add_action('template_redirect', array($this, 'generate_random_pokemon'));
    }

    public function add_generate_pokemon_rewrite_rule() {
        add_rewrite_rule('^generate/?', 'index.php?generate_pokemon=1', 'top');
    }

    public function add_generate_pokemon_query_var($query_vars) {
        $query_vars[] = 'generate_pokemon';
        return $query_vars;
    }

    public function generate_random_pokemon() {
        global $wp_query;

        if (isset($wp_query->query_vars['generate_pokemon'])) {
            if (current_user_can('publish_posts')) {
                $random_id = mt_rand(1, 500);
                $pokemon_data = $this->fetch_pokemon_data($random_id);
                if ($pokemon_data) {
                    $post_id = $this->insert_pokemon_as_post($pokemon_data);
                    wp_redirect(get_permalink($post_id));
                    exit;
                }
            } else {
                wp_die('You do not have sufficient permissions to generate a Pokémon.');
            }
        }
    }

    private function fetch_pokemon_data($id) {
        $pokemon_endpoint = "https://pokeapi.co/api/v2/pokemon/{$id}";
        $species_endpoint = "https://pokeapi.co/api/v2/pokemon-species/{$id}";

        $pokemon_response = wp_remote_get($pokemon_endpoint);
        $species_response = wp_remote_get($species_endpoint);

        if (is_wp_error($pokemon_response) || is_wp_error($species_response)) {
            return false;
        }

        $pokemon_data = json_decode(wp_remote_retrieve_body($pokemon_response), true);
        $species_data = json_decode(wp_remote_retrieve_body($species_response), true);

        $old_game_index = reset($pokemon_data['game_indices'])['game_index'];
        $old_game_name = reset($pokemon_data['game_indices'])['version']['name'];
        $new_game_index = end($pokemon_data['game_indices'])['game_index'];
        $new_game_name = end($pokemon_data['game_indices'])['version']['name'];

        if (!empty($pokemon_data['types'][1]['type']['name'])) {
            $secondary_type = $pokemon_data['types'][1]['type']['name'];
        } else {
            $secondary_type = null;
        }

        $abilities = [];
        foreach ($pokemon_data['abilities'] as $ability_data) {
            $ability_name = $ability_data['ability']['name'];
            $ability_effect = $this->fetch_ability_effect($ability_data['ability']['url']);

            $abilities[] = [
                'name' => $ability_name,
                'description' => $ability_effect
            ];
        }

        return [
            'name' => $pokemon_data['forms'][0]['name'],
            'description' => $species_data['flavor_text_entries'][0]['flavor_text'],
            'image_url' => $pokemon_data['sprites']['other']['official-artwork']['front_default'],
            'primary_type' => $pokemon_data['types'][0]['type']['name'],
            'secondary_type' => $secondary_type,
            'weight' => $pokemon_data['weight'],
            'old_pokedex_number' => $old_game_index,
            'old_pokedex_name' => $old_game_name,
            'new_pokedex_number' => $new_game_index,
            'new_pokedex_name' => $new_game_name,
            'abilities' => $abilities
        ];
    }

    private function insert_pokemon_as_post($data) {
        $post_id = wp_insert_post([
            'post_title' => sanitize_text_field($data['name']),
            'post_content' => sanitize_text_field($data['description']),
            'post_type' => 'pokemon',
            'post_status' => 'publish'
        ]);

        if ($post_id) {
            // Set the Pokemon image as the featured image for the post
            $attachment_id = $this->upload_image_from_url($data['image_url'], $post_id);
            if ($attachment_id) {
                set_post_thumbnail($post_id, $attachment_id);
            }

            update_post_meta($post_id, '_primary_type', sanitize_text_field($data['primary_type']));
            update_post_meta($post_id, '_secondary_type', sanitize_text_field($data['secondary_type']));
            update_post_meta($post_id, '_weight', sanitize_text_field($data['weight']));
            update_post_meta($post_id, '_old_pokedex_number', sanitize_text_field($data['old_pokedex_number']));
            update_post_meta($post_id, '_old_pokedex_name', sanitize_text_field($data['old_pokedex_name']));
            update_post_meta($post_id, '_recent_pokedex_number', sanitize_text_field($data['new_pokedex_number']));
            update_post_meta($post_id, '_recent_pokedex_name', sanitize_text_field($data['new_pokedex_name']));
            update_post_meta($post_id, '_attacks', maybe_serialize($data['abilities']));
        }

        return $post_id;
    }

    private function upload_image_from_url($image_url, $post_id) {
        require_once(ABSPATH . 'wp-admin/includes/image.php');

        $image_data = file_get_contents($image_url);
        $filename = basename($image_url);
        $upload_dir = wp_upload_dir();
        $upload_path = $upload_dir['path'] . '/' . $filename;
        file_put_contents($upload_path, $image_data);

        $filetype = wp_check_filetype($filename, null);
        $attachment_data = [
            'post_mime_type' => $filetype['type'],
            'post_title' => sanitize_file_name($filename),
            'post_content' => '',
            'post_status' => 'inherit'
        ];
        $attachment_id = wp_insert_attachment($attachment_data, $upload_path, $post_id);
        $attachment_metadata = wp_generate_attachment_metadata($attachment_id, $upload_path);
        wp_update_attachment_metadata($attachment_id, $attachment_metadata);

        return $attachment_id;
    }

    private function fetch_ability_effect($url) {
        $response = wp_remote_get($url);

        if (is_wp_error($response)) {
            return null;
        }

        $ability_data = json_decode(wp_remote_retrieve_body($response), true);
        return $ability_data['effect_entries'][1]['short_effect'] ?? null;
    }
}

new Pokemon_Generator();
