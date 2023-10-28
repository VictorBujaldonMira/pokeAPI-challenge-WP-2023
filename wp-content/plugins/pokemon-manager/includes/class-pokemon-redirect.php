<?php

class Pokemon_Redirect {

    public function __construct() {
        add_action('init', array($this, 'add_random_pokemon_rewrite_rule'));
        add_filter('query_vars', array($this, 'add_random_pokemon_query_var'));
        add_action('template_redirect', array($this, 'redirect_to_random_pokemon'));
    }

    public function add_random_pokemon_rewrite_rule() {
        add_rewrite_rule('^random/?', 'index.php?random_pokemon=1', 'top');
    }

    public function add_random_pokemon_query_var($query_vars) {
        $query_vars[] = 'random_pokemon';
        return $query_vars;
    }

    public function redirect_to_random_pokemon() {
        global $wp_query;

        if (isset($wp_query->query_vars['random_pokemon'])) {
            $args = array(
                'post_type' => 'pokemon',
                'posts_per_page' => 1,
                'orderby' => 'rand'
            );
            $random_pokemon_query = new WP_Query($args);
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