<?php

class Pokemon_Shortcodes {

    public function __construct() {
        add_shortcode('pokemon_grid', array($this, 'generate_pokemon_grid'));
    }

    public function generate_pokemon_grid($atts) {
        $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

        $query_args = array(
            'post_type' => 'pokemon',
            'posts_per_page' => 6,
            'paged' => $paged
        );

        $query = new WP_Query($query_args);
        $output = '<div id="pokemon__grid"><div id="pokemon__type-filter"></div><button class="reset-filter btn btn-outline-primary">Reset filter</button><div class="pokemon__grid-list mt-4">';

        while ($query->have_posts()) {
            $query->the_post();
            $pokemon_image = get_the_post_thumbnail_url();
            $output .= '<div class="pokemon-item '.get_post_meta(get_the_ID(), '_primary_type', true) ." ". get_post_meta(get_the_ID(), '_secondary_type', true) .'">';
            $output .= '<img src="' . $pokemon_image . '" alt="' . get_the_title() . '">';
            $output .= '</div>';
        }

        $output .= '</div>'; // Ending #pokemon__grid
        $output .= '</div>'; // Ending .pokemon__grid-list

        $output .= '<div class="pokemon__pagination mt-4 mb-5">';

        $big = 999999999;
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