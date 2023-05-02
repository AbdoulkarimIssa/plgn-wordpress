<?php
require_once("spotify.php");
require_once("sqlitedb/mr_aim_insert_records.php");
// Paramètres de la requête
$inputUtilisateur ="Rohff";
$artistId = "";
$inputUtilisateur = str_replace(' ', '+', $inputUtilisateur);
$artistSearch = 'https://api.spotify.com/v1/search?q='.$inputUtilisateur.'&type=artist&limit=1';
$url = 'https://api.spotify.com/v1/search?q=rohff&type=artist';
$authorization = 'Authorization: Bearer '. $accessToken;

function getInfosSpotify($accessToken, $url) {
    // Options de la requête
    $get = curl_init();
    $authorization = 'Authorization: Bearer '. $accessToken;
    curl_setopt($get, CURLOPT_URL, $url);
    curl_setopt($get, CURLOPT_HTTPHEADER, array($authorization));
    curl_setopt($get, CURLOPT_RETURNTRANSFER, true);

    // Exécution de la requête et récupération des résultats
    $response = curl_exec($get);
    $data = json_decode($response,true);


    // Fermeture de la session cURL
    curl_close($get);
    
    return $data;
}

function getArtistInfo($accessToken,$Url){
    // Options de la requête
    $data = getInfosSpotify($accessToken,$Url);
    $artistId = $data['artists']['items'][0]['id'];
    $urlArtistInfos = 'https://api.spotify.com/v1/artists/'.$artistId;
    $get = curl_init();
    $authorization = 'Authorization: Bearer '. $accessToken;
    curl_setopt($get, CURLOPT_URL, $urlArtistInfos);
    curl_setopt($get, CURLOPT_HTTPHEADER, array($authorization));
    curl_setopt($get, CURLOPT_RETURNTRANSFER, true);

    // Exécution de la requête et récupération des résultats
    $response = curl_exec($get);
    //echo $response;
    $data = json_decode($response,true);

    $id = $data['id'];
    $name = $data['name'];
    $popularity = $data['popularity'];

    curl_close($get);

    return array('id' => $id, 'name' => $name, 'popularity' => $popularity);
    // Fermeture de la session cURL
}

// $data = getInfosSpotify($accessToken,$artistUrl);

// $artistId = $data['artists']['items'][0]['id'];
// echo $artistId;

$artist = getArtistInfo($accessToken,$artistSearch);
foreach ($artist as $cle => $valeur) {
    echo $cle . " ==> " . $valeur;
}
insertArtist($artist['id'],$artist['name'],$artist['popularity'],$tableSchemaDictionnary,$conn);
