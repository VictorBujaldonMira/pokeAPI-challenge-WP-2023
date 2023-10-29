<?php

class Pokemon_Shortcodes {

    public function __construct() {
        add_shortcode('pokemon_grid', array($this, 'generate_pokemon_grid'));
    }

    public function generate_pokemon_grid($atts) {
        // Determine the current page number.
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        // Setup the query arguments to fetch Pokemon posts.
        $query_args = array(
            'post_type' => 'pokemon',
            'posts_per_page' => 6,
            'paged' => $paged
        );

        $query = new WP_Query($query_args);
        // Start building the output for the Pokemon grid.
        $output = '<div id="pokemon__grid">';
        $output .= '<div id="pokemon__type-filter"></div>';
        $output .= '<button class="reset-filter btn btn-outline-primary">Reset filter</button>';
        $output .= '<div class="pokemon__grid-list mt-4">';

        // Loop through each Pokemon post and build its HTML.
        while ($query->have_posts()) {
            $query->the_post();
            $pokemon_image = get_the_post_thumbnail_url();
            $output .= '<div class="pokemon-item '.get_post_meta(get_the_ID(), '_primary_type', true) ." ". get_post_meta(get_the_ID(), '_secondary_type', true) .'">';
            $output .= '<a href="'.get_permalink().'">';
            $output .= '<img src="' . $pokemon_image . '" alt="' . get_the_title() . '">';
            $output .= '</a>';
            $output .= '</div>';
        }

        $output .= '</div>'; // Ending #pokemon__grid
        $output .= '</div>'; // Ending .pokemon__grid-list

        // Add pagination links.
        $output .= '<div class="pokemon__pagination mt-4 mb-5">';
        $big = 999999999; // Arbitrary big number to replace for real paged value.
        $output .= paginate_links(array(
            'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
            'format' => '?paged=%#%',
            'current' => max(1, get_query_var('paged')),
            'total' => $query->max_num_pages
        ));

        $output .= '</div>'; // Ending .pokemon__pagination

        wp_reset_postdata();

        return $output;
    }
}

new Pokemon_Shortcodes();