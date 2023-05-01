<?php
/*
Plugin Name: Spotify Plugin by Issa Abdoulkarim et Maxime RICHAUDEAU
Plugin URI: https://github.com/AbdoulkarimIssa/plgn-wordpress
Description: Plugin de gestion spotify.
Author: Issa&Maxime
Version: 1.0
*/

// add_action("wp_footer", "mr_aim_test_db");

// function mr_aim_test_db(){
//        require_once plugin_dir_path(__FILE__)."sqlitedb/mr_aim_pdo2create.php";
//  }


function mr_aim_displayMenu(){
    require_once plugin_dir_path(__FILE__)."includes/mr_aim_index.php";
    return null;
}

add_shortcode('mr_aim_sc', 'mr_aim_displayMenu');
