<?php
class Pokemon_Ajax {
    // Load actions for ajax Wordpress
    public function __construct() {
        add_action('wp_ajax_load_old_pokedex', array($this, 'load_old_pokedex'));
        add_action('wp_ajax_nopriv_load_old_pokedex', array($this, 'load_old_pokedex'));
    }

    public function load_old_pokedex() {
        $post_id = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;

        if (!$post_id) {
            wp_send_json_error();
            return;
        }

        // Get data to send
        $pokedex_num = get_post_meta($post_id, '_old_pokedex_number', true);
        $game_version = get_post_meta($post_id, '_old_pokedex_name', true); 

        // Send data in Json format in case of success
        if ($pokedex_num) {
            $json = wp_send_json_success(array(
                'pokedex_num' => $pokedex_num,
                'game_version' => $game_version
            ));
            var_dump($json);
        } else {
            wp_send_json_error();
        }
    }
}

new Pokemon_Ajax();