<?php

class Pokemon_Redirect {

    // Add the actions to redirect
    public function __construct() {
        add_action('init', array($this, 'add_random_pokemon_rewrite_rule'));
        add_filter('query_vars', array($this, 'add_random_pokemon_query_var'));
        add_action('template_redirect', array($this, 'redirect_to_random_pokemon'));
    }

    //  This method makes WordPress aware of the "/random" URL endpoint and maps it to a custom query variable 'random_pokemon'.
    public function add_random_pokemon_rewrite_rule() {
        add_rewrite_rule('^random/?', 'index.php?random_pokemon=1', 'top');
    }

    // This allows WordPress to recognize the 'random_pokemon' variable when processing requests.
    public function add_random_pokemon_query_var($query_vars) {
        $query_vars[] = 'random_pokemon';
        return $query_vars;
    }

    public function redirect_to_random_pokemon() {
        global $wp_query;

        // Check if the 'random_pokemon' query variable is set.
        if (isset($wp_query->query_vars['random_pokemon'])) {
            $args = array(
                'post_type' => 'pokemon',
                'posts_per_page' => 1,
                'orderby' => 'rand'
            );
            // Execute a query to fetch a random Pokemon post.
            $random_pokemon_query = new WP_Query($args);
            // If a post is found, redirect the user to that post.
            if ($random_pokemon_query->have_posts()) {
                while ($random_pokemon_query->have_posts()) {
                    $random_pokemon_query->the_post();
                    wp_redirect(get_the_permalink(), 301);
                    exit;
                }
            }
        }
    }
}

new Pokemon_Redirect();