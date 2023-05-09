<?php
require_once("spotify.php");
//require_once("sqlitedb_mr_aim_insert_records.php");
// Paramètres de la requête
// $inputUtilisateur ="Rohff";
$artistId = "";
$AlbumSearch = str_replace(' ', '+', $inputUtilisateur);

$url = 'https://api.spotify.com/v1/search?q=rohff&type=artist';
$authorization = 'Authorization: Bearer '. $accessToken;
$albumUrlInfos ='https://api.spotify.com/v1/search?q='.str_replace(' ', '+', $inputUtilisateur).'&type=album&limit=1';

function getInfosSpotify($accessToken, $inputUtilisateur,$type) {
    $inputUtilisateur = str_replace(' ', '+', $inputUtilisateur);
    $url= 'https://api.spotify.com/v1/search?q='.$inputUtilisateur.'&type='.$type.'&limit=1';
    // Options de la requête
    $get = curl_init();
    $authorization = 'Authorization: Bearer '. $accessToken;
    curl_setopt($get, CURLOPT_URL, $url);
    curl_setopt($get, CURLOPT_HTTPHEADER, array($authorization));
    curl_setopt($get, CURLOPT_RETURNTRANSFER, true);

    // Exécution de la requête et récupération des résultats
    $response = curl_exec($get);
    $data = json_decode($response,true);

    // echo "data => ".$data;
    // Fermeture de la session cURL
    curl_close($get);
    
    return $data;
}

function getArtistInfo($accessToken,$inputUtilisateur,$tableSchemaDictionnary,$conn){

    // Options de la requête
    $type ="artist";
    $data = getInfosSpotify($accessToken,$inputUtilisateur,$type);
    $artistId = $data['artists']['items'][0]['id'];
    $urlArtistInfos = 'https://api.spotify.com/v1/artists/'.$artistId;
    $get = curl_init();
    $authorization = 'Authorization: Bearer '. $accessToken;
    curl_setopt($get, CURLOPT_URL, $urlArtistInfos);
    curl_setopt($get, CURLOPT_HTTPHEADER, array($authorization));
    curl_setopt($get, CURLOPT_RETURNTRANSFER, true);

    // Exécution de la requête et récupération des résultats
    $response = curl_exec($get);
    // echo "response => ".$response;
    $data = json_decode($response,true);

    $id = $data['id'];
    $name = $data['name'];
    $popularity = $data['popularity'];

    // echo "id => ".$id;
    // echo $name;
    // echo $popularity;

    curl_close($get);
    
    insertArtist($id,$name,$popularity,$tableSchemaDictionnary,$conn);

   // return array('id' => $id, 'name' => $name, 'popularity' => $popularity);
    // Fermeture de la session cURL
    return $id;

}

// function getAlbum($accessToken,$Url){
//     $data = getInfosSpotify($accessToken,$Url);

//     $id = $data['albums']['items'][0]['id'];
//     $name = $data['albums']['items'][0]['name'];
//     $idArtiste = $data['albums']['items'][0]['artists'][0]['id'];
//     $image = $data['albums']['items'][0]['images'][1]['url'];
//     $releaseDate = $data['albums']['items'][0]['release_date'];
//     $tracksNumber =$data['albums']['items'][0]['total_tracks'];

//     $get = curl_init();
//     $url="";
//     $albumUrl = "https://api.spotify.com/v1/albums/".$id."/tracks";

//     // insertion de l'auteur 

    
//     $authorization = 'Authorization: Bearer '. $accessToken;
//     curl_setopt($get, CURLOPT_URL, $url);
//     curl_setopt($get, CURLOPT_HTTPHEADER, array($authorization));
//     curl_setopt($get, CURLOPT_RETURNTRANSFER, true);
//     $response = curl_exec($get);
//     //echo $response;
//     $tracks = json_decode($response,true);

// }


// $data = getInfosSpotify($accessToken,$artistUrl);

// $artistId = $data['artists']['items'][0]['id'];
// echo $artistId;

// $artist = getArtistInfo($accessToken,$artistSearch);
// foreach ($artist as $cle => $valeur) {
//     echo $cle . " ==> " . $valeur;
// }
// insertArtist($artist['id'],$artist['name'],$artist['popularity'],$tableSchemaDictionnary,$conn);

function dispatch($accessToken, $type, $inputUtilisateur,$tableSchemaDictionnary,$conn){
    if ($type == 'Artist'){
        $artist_id = getArtistInfo($accessToken, $inputUtilisateur,$tableSchemaDictionnary,$conn);
        // $album_ids = getArtistAlbums($accessToken, $artist_id)
        // foreach ($album_id as $cle => $valeur) {
        //         getAlbumSongs($accessToken, $valeur);
		// }
        echo $artist_id;    

    }
    elseif($type == 'Album'){
        // $info = getAlbumInfo(); --> array("album_id":value, "artist_id":value)
        //getAlbumSongs($accessToken, $info['album_id']);$
        //$artist_id = getArtistInfo();

        echo "album";
    }
    else{
        // $info = getOneSong($accessToken, $inputUtilisateur); --> array("album_id":value, "artist_id":value)
    }
        echo "chanson";
}