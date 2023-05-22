<?php
/**
 * Plugin Name: Spotify Plugin by Issa Abdoulkarim et Maxime RICHAUDEAU
 * Plugin URI: https://github.com/AbdoulkarimIssa/plgn-wordpress
 * Description: Plugin de gestion spotify.
 * Author: Issa&Maxime
 * Version: 1.0
 */


/**
 * Displays the menu.
 *
 * @return null
 */
function mr_aim_displayMenu(){
    require_once plugin_dir_path(__FILE__)."includes/mr_aim_index.php";
    return null;
}


/**
 * Adds a shortcode for displaying the menu.
 *
 * @param array $atts The shortcode attributes.
 * @return null
 */
add_shortcode('mr_aim_sc', 'mr_aim_displayMenu');

/**
 * Requires the adminMenu.php file.
 */
 require_once plugin_dir_path(__FILE__)."includes/adminMenu.php";
