<?php

/**
 * @package api_get_menu
 * @version 1
 */
/*
Plugin Name: REST API Get Menu
Description: Expose main menu via REST API
Author: Danny Libin
Version: 1
Author URI: https://dlibin.net/
*/

function get_main_menu()
{
  return wp_get_nav_menu_items('Header Main Menu'); // Replace menu name here
}

// Accessible via: https://website.com/wp-json/wp/v2/menu
add_action('rest_api_init', function () {
  register_rest_route('wp/v2', 'menu', array(
    'methods' => 'GET',
    'callback' => 'get_main_menu',
  ));
});
