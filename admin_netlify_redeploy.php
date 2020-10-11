<?php

/**
 * @package admin_netlify_redeploy
 * @version 1
 */
/*
Plugin Name: Admin Netlify Redeploy
Description: Add an admin area with a redeploy button for Netlify. Useful for headless wordpress builds hosted on Netlify.
Author: Danny Libin
Version: 1
Author URI: https://dlibin.net/
*/

add_action('admin_menu', 'netlify_redeploy_menu');

function netlify_redeploy_menu()
{
  add_menu_page('Netlify Redeploy Page', 'Redeploy Site', 'manage_options', 'netlify-redeploy', 'netlify_redeploy_page');
}

function netlify_redeploy_page()
{

  // This function creates the output for the admin page.
  // It also checks the value of the $_POST variable to see whether
  // there has been a form submission. 

  // The check_admin_referer is a WordPress function that does some security
  // checking and is recommended good practice.

  // General check for user permissions.
  if (!current_user_can('manage_options')) {
    wp_die(__('You do not have sufficient pilchards to access this page.'));
  }

  // Start building the page

  echo '<div class="wrap">';

  echo '<h2>Redeploy Site</h2>';

  // Check whether the button has been pressed AND also check the nonce
  if (isset($_POST['redeploy_button']) && check_admin_referer('redeploy_button_clicked')) {
    // the button has been pressed AND we've passed the security check
    trigger_netlify_rebuild();
    //echo '<p>Button pressed!</p>';
  }

  echo '<form action="options-general.php?page=netlify-redeploy" method="post">';

  echo '<p>Press this button when you are finished making changes to your site here on wordpress to redeploy it. This will apply the changes to the production/live site.</p>';
  echo '<p>Note that builds can take up to several minutes, so try to do all of your updates before redeploying.</p>';
  echo '<p>Don\'t worry, if a redeploy fails, the last working version of the site will still be live.</p>';

  // this is a WordPress security feature - see: https://codex.wordpress.org/WordPress_Nonces
  wp_nonce_field('redeploy_button_clicked');
  echo '<input type="hidden" value="true" name="redeploy_button" />';
  submit_button('Redeploy Site');
  echo '</form>';

  echo '<h3>Current deployment status:</h3>';
  echo '<p style="color: gray;">(Refresh this page a few minutes after deploying, this updates to "Success" when build finishes successfully): </p>';
  echo '<img src="https://api.netlify.com/api/v1/badges/XXXXXX/deploy-status" alt="Netlify Status">';
  echo '<p><span style="color: red;">**</span> If you see a "Failed" status here (instead of "Success" or "Building") contact me to troubleshoot.</p>';

  echo '</div>';
}

function trigger_netlify_rebuild()
{

  $curl = curl_init();
  curl_setopt($curl, CURLOPT_POST, 1);
  curl_setopt($curl, CURLOPT_POSTFIELDS, new stdClass());
  curl_setopt($curl, CURLOPT_URL, 'https://api.netlify.com/build_hooks/XXXXXXXX');

  $result = curl_exec($curl);

  if ($result) {
    echo '<div id="message" class="updated fade"><p>'
      . 'Site redeploy has been triggered.' . '</p></div>';
  } else {
    echo '<div id="message" class="error fade"><p>'
      . 'Site redeploy has FAILED to be triggered. Contact me to troubleshoot' . '</p></div>';
  }
}
