<?php

class Pokemon_Post_Type {

    // Action to register the code
    public function __construct() {
        add_action('init', array($this, 'register_post_type'));
    }

    // Register the code
    public function register_post_type() {
        register_post_type('pokemon',
            array(
                'labels' => array(
                    'name' => __('Pokémon'),
                    'singular_name' => __('Pokémon')
                ),
                'public' => true,
                'has_archive' => true,
                'supports' => array('title', 'editor', 'thumbnail'),
                'menu_icon' => 'data:image/svg+xml;base64,' . base64_encode('<svg version="1.0" xmlns="http://www.w3.org/2000/svg" width="30.000000pt" height="30.000000pt" viewBox="0 0 30.000000 30.000000" preserveAspectRatio="xMidYMid meet"><g transform="translate(0.000000,30.000000) scale(0.100000,-0.100000)" fill="black" stroke="none"><path d="M91 262 c-38 -20 -71 -73 -71 -112 0 -62 68 -130 130 -130 39 0 92 33 112 71 23 43 23 75 0 118 -20 38 -73 71 -112 71 -14 0 -41 -8 -59 -18z m79 -98 c11 -12 10 -18 -3 -32 -16 -15 -18 -15 -34 0 -13 14 -14 20 -3 32 7 9 16 16 20 16 4 0 13 -7 20 -16z m-50 -39 c16 -19 44 -19 60 0 7 8 28 15 47 15 31 0 34 -2 28 -22 -13 -42 -50 -70 -99 -75 -42 -5 -48 -3 -81 30 -46 46 -46 67 -1 67 18 0 39 -7 46 -15z"/> </g> </svg>')
            )
        );

        add_action('init', 'register_post_type');
    }
}