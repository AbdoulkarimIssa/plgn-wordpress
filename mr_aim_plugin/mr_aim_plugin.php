<?php
/*
Plugin Name: Spotify Plugin by Issa Abdoulkarim et Maxime RICHAUDEAU
Plugin URI: https://github.com/AbdoulkarimIssa/plgn-wordpress
Description: Plugin de gestion spotify.
Author: Issa&Maxime
Version: 1.0
*/

add_action("wp_footer", "mr_aim_test_db");

function mr_aim_test_db(){
       require_once plugin_dir_path(__FILE__)."sqlitedb/mr_aim_pdo2create.php";
 }

<<<<<<< HEAD
// $client_id = 'fa5fe9ed9ae748579b4239bee89506fb';
// $client_secret = '3e195efd3c9141968ed5cee686bfdc2b';

// $authOptions = array(
//     'url' => 'https://accounts.spotify.com/api/token',
//     'headers' => array(
//         'Authorization' => 'Basic ' . base64_encode("$client_id:$client_secret")
//     ),
//     'form_params' => array(
//         'grant_type' => 'client_credentials'
//     )
// );

// $curl = curl_init();
// curl_setopt_array($curl, array(
//     CURLOPT_URL => $authOptions['url'],
//     CURLOPT_RETURNTRANSFER => true,
//     CURLOPT_POST => true,
//     CURLOPT_POSTFIELDS => http_build_query($authOptions['form_params']),
//     CURLOPT_HTTPHEADER => array(
//         'Authorization: ' . $authOptions['headers']['Authorization'],
//         'Content-Type: application/x-www-form-urlencoded'
//     )
// ));

// $response = curl_exec($curl);
// $tokenData = json_decode($response, true);
// $accessToken = $tokenData['access_token'];

// echo $accessToken;
// curl_close($curl);

=======
>>>>>>> ac7695f (teste2)
