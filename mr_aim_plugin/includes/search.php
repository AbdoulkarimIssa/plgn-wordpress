<?php
require_once("spotify.php");
// Paramètres de la requête
$url = 'https://api.spotify.com/v1/search?q=rohff&type=artist';
$authorization = 'Authorization: Bearer '. $accessToken;

// Initialisation de la session cURL
$get = curl_init();

// Options de la requête
curl_setopt($get, CURLOPT_URL, $url);
// curl_setopt($get, CURLOPT_HTTPHEADER, array(
//     "Authorization: ". $accessToken));
curl_setopt($get, CURLOPT_HTTPHEADER, array($authorization));
curl_setopt($get, CURLOPT_RETURNTRANSFER, true);

// Exécution de la requête et récupération des résultats
$response = curl_exec($get);
$data = json_decode($response,true);
//echo $response;
foreach ($data["artists"]["items"][0] as $cle => $valeur) {
    echo "La clé ".$cle." a pour valeur ".$valeur."\n";
}
// Fermeture de la session cURl
curl_close($get);