<?php

class Pokemon_REST_API {

    public function __construct() {
        add_action('rest_api_init', array($this, 'register_routes'));
    }

    public function register_routes() {
        register_rest_route('pokemon', '/list', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_pokemon_list'),
        ));

        register_rest_route('pokemon', '/details/(?P<id>\d+)', array(
            'methods' => 'GET',
            'callback' => array($this, 'get_pokemon_details'),
        ));
    }

    public function get_pokemon_list() {
        $args = array(
            'post_type' => 'pokemon',
            'posts_per_page' => -1
        );

        $query = new WP_Query($args);
        $pokemon_list = array();

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $pokedex_recent_version = get_post_meta(get_the_ID(), '_recent_pokedex_number', true);
                $pokemon_list[] = array(
                    'name' => get_the_title(),
                    'pokedex_id_recent_version' => $pokedex_recent_version
                );
            }
        }

        return $pokemon_list;
    }

    public function get_pokemon_details($data) {
        $post_id = $data['id'];
        $post = get_post($post_id);

        if (!$post || $post->post_type !== 'pokemon') {
            return new WP_Error('no_post', 'Invalid Pokemon ID.', array('status' => 404));
        }

        $name = $post->post_title;
        $description = $post->post_content;
        $featured_image = get_the_post_thumbnail_url($post_id);
        $primary_type = get_post_meta($post->ID, '_primary_type', true);
        $secondary_type = get_post_meta($post->ID, '_secondary_type', true);
        $weight = get_post_meta($post->ID, '_weight', true);
        $old_pokedex_number = get_post_meta($post->ID, '_old_pokedex_number', true);
        $old_pokedex_name = get_post_meta($post->ID, '_old_pokedex_name', true);
        $recent_pokedex_number = get_post_meta($post->ID, '_recent_pokedex_number', true);
        $recent_pokedex_name = get_post_meta($post->ID, '_recent_pokedex_name', true);
        $attacks = unserialize(get_post_meta($post->ID, "_attacks", true));

        return array(
            'name' => $name,
            'description' => $description,
            'featured_image' => $featured_image,
            'primary_type' => $primary_type,
            'secondary_type' => $secondary_type,
            'weight' => $weight,
            'old_pokedex_number' => $old_pokedex_number,
            'old_pokedex_name' => $old_pokedex_name,
            'recent_pokedex_number' => $recent_pokedex_number,
            'recent_pokedex_name' => $recent_pokedex_name,
            'attacks' => $attacks
        );
    }
}

new Pokemon_REST_API();