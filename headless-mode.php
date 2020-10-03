<?php

/**
 * @package Headless_Mode
 * @version 1.0.0
 */
/*
Plugin Name: Headless mode
Description: Redirect all front end traffic to admin
Author: Danny Libin
Version: 1
Author URI: https://dlibin.net/
*/

add_action('wp', 'headless_redirect');

function headless_redirect()
{
  if (!is_admin()) {
    wp_safe_redirect(get_admin_url());
  }
}
