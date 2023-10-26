<?php

class Pokemon_Meta_Boxes {

    public function __construct() {
        add_action('add_meta_boxes', array($this, 'add_meta_boxes'));
        add_action('save_post', array($this, 'save_post_data'));
    }

    public function add_meta_boxes() {
    }

    public function save_post_data($post_id) {
    }
}