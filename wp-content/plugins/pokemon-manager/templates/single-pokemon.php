<?php
get_header();

if (have_posts()):
    while (have_posts()): the_post();

        $attacks = maybe_unserialize(get_post_meta(get_the_ID(), '_attacks', true));

        // Show data Pokemon
        echo '<div class="container mt-5"><div class="row"><div class="col-sm-8">';
        echo '<h1>' . get_the_title() . '</h1>';
        echo '<span class="pokemon__tpye font-weight-bold '.esc_html(get_post_meta(get_the_ID(), '_primary_type', true)).'">' . esc_html(get_post_meta(get_the_ID(), '_primary_type', true)) . '</span>';
        echo '<span class="pokemon__tpye font-weight-bold '.esc_html(get_post_meta(get_the_ID(), '_secondary_type', true)).'">' . esc_html(get_post_meta(get_the_ID(), '_secondary_type', true)) . '</span>';
        echo '<p>' . get_the_content() . '</p>';
        echo '<div class="pokemon__data">';
        echo '<span class="font-weight-bold">Weight: </span><span>' . esc_html(get_post_meta(get_the_ID(), '_weight', true)) . '</span><br>';
        echo '<span class="font-weight-bold">Old Pokedex Number: </span><span>' . esc_html(get_post_meta(get_the_ID(), '_old_pokedex_number', true)) . '</span><br>';
        echo '<span class="font-weight-bold">Recent Pokedex Number: </span><span>' . esc_html(get_post_meta(get_the_ID(), '_recent_pokedex_number', true)) . '</span><br>';

        if ($attacks && is_array($attacks)) {
            echo '<h2 class="mt-3">Attacks</h2>';
            echo '<ul class="pokemon__attack-list">';
            foreach ($attacks as $attack) {
                echo '<li><strong>' . esc_html($attack['name']) . '</strong>: ' . esc_html($attack['description']) . '</li>';
            }
            echo '</ul>';
        }
        echo '</div>';
        echo '</div>'; // Ending col-sm-8
        echo '<div class="col-sm-4">';
        echo '<div class="sticky-top pt-5">';
        if (has_post_thumbnail()) {
            echo '<img src="' . esc_url(wp_get_attachment_image_src(get_post_thumbnail_id(), 'full')[0]) . '" alt="' . esc_attr(get_the_title()) . '">';
        }
        echo '</div>'; // Ending fixed div
        echo '</div>'; // Ending col-sm-4
        echo '</div>'; // Ending row
        echo '</div>'; // Ending container

    endwhile;
endif;

get_footer();