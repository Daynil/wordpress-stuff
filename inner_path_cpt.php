<?php

/**
 * @package Inner_Path_CPT
 * @version 1.7.2
 */
/*
Plugin Name: Inner Path Custom Post Types
Description: Custom Post Types for the Inner Path site
Author: Danny Libin
Version: 1
Author URI: https://dlibin.net/
*/

// Our custom post type function
function create_inner_path_cpt()
{

  register_post_type(
    'subleaser',
    // CPT Options
    array(
      'labels' => array(
        'name' => __('Subleasers'),
        'singular_name' => __('Subleaser')
      ),
      'description' => 'Subleasers at Inner Path (and friends)',
      'public' => true,
      'rewrite' => array('slug' => 'subleasers'),
      'show_in_rest' => true,
      'supports' => array('title', 'editor', 'custom-fields')
    )
  );
}

function get_custom_fields($object, $field_name, $request)
{
  $post_id = $object['id'];
  return get_post_meta($post_id);
}

// Add custom fields from Custom Post Type UI to API response
function add_custom_fields_to_api()
{
  register_rest_field(
    'subleaser',
    'custom_fields', // Field name in JSON response
    array(
      'get_callback' => 'get_custom_fields',
      'update_callback' => null,
      'schema' => null
    )
  );
}

add_action('init', 'create_inner_path_cpt');

add_action('rest_api_init', 'add_custom_fields_to_api');
