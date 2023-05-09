<?php
require_once("spotify.php");

function getInfosSpotify($accessToken, $inputUtilisateur,$type) {
    $inputUtilisateur =  urlencode($inputUtilisateur);
    $url= "https://api.spotify.com/v1/search?q=".$inputUtilisateur."&type=".$type."&limit=1";
    echo $url."\n";
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

function getArtist($accessToken,$inputUtilisateur,$tableSchemaDictionnary,$conn){
    $url_type = 'artist';
    $data = getInfosSpotify($accessToken,$inputUtilisateur,$url_type);
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

    $idSpotifyArtist = $data['id'];
    $name = $data['name'];
    $popularity = $data['popularity'];

    echo "id => ".$idSpotifyArtist;
    echo $name;
    echo $popularity;

    curl_close($get);
    
    insertArtist($idSpotifyArtist,$name,$popularity,$tableSchemaDictionnary,$conn);

   // return array('id' => $id, 'name' => $name, 'popularity' => $popularity);
    // Fermeture de la session cURL
    return $idSpotifyArtist;

}

function getAlbum($accessToken,$inputUtilisateur,$tableSchemaDictionnary,$conn){
    $url_type ="album";
    $data = getInfosSpotify($accessToken,$inputUtilisateur,$url_type);
    
    // Single Album Parser
    $IdSpotifyAlbum = $data['albums']['items'][0]['id'];
    $name = $data['albums']['items'][0]['name'];
    $IdSpotifyArtist = $data['albums']['items'][0]['artists'][0]['id'];
    $image = $data['albums']['items'][0]['images'][1]['url'];
    $releaseDate = $data['albums']['items'][0]['release_date'];
    $tracksNumber =$data['albums']['items'][0]['total_tracks'];

    insertAlbum($IdSpotifyAlbum, $IdSpotifyArtist, $image, $name, $releaseDate, $tracksNumber, $tableSchemaDictionnary, $conn);
    return array('artist_id'=>$IdSpotifyArtist, 'album_id'=>$IdSpotifyAlbum);

}

function getSong($accessToken,$inputUtilisateur,$tableSchemaDictionnary,$conn){
    $url_type ="track";
    $data = getInfosSpotify($accessToken, $inputUtilisateur,$url_type);
    
    // Single Song Parser
    $album_type = $data['tracks']['items'][0]['album']['album_type'];
    $IdSpotifyAlbum = $data['tracks']['items'][0]['album']['id'];
    $IdSpotifySong =  $data['tracks']['items'][0]['id'];
    $IdSpotifyArtist = $data['tracks']['items'][0]['artists'][0]['id'];
    $name = $data['tracks']['items'][0]['name'];
    $duration = $data['tracks']['items'][0]['duration_ms'];
    $popularity = $data['tracks']['items'][0]['popularity'];
    $tracksNumber = $data['tracks']['items'][0]['album']['total_tracks'];
    $songNumber = $data['tracks']['items'][0]['track_number'];

    insertSong($IdSpotifySong, $IdSpotifyAlbum, $IdSpotifyArtist, $name, $duration, $popularity, $tracksNumber, $songNumber, $tableSchemaDictionnary, $conn);

    if ($album_type != "album") {
        return array("artist_id"=>$idArtist, "album_id"=>$album_id);
    }

}


function dispatch($accessToken, $type, $inputUtilisateur,$tableSchemaDictionnary,$conn){
    if ($type == 'Artist'){
        $artist_id = getArtist($accessToken,$inputUtilisateur,$tableSchemaDictionnary,$conn);
        echo $artist_id;    

    }
    elseif($type == 'Album'){
        $info = getAlbum($accessToken,$inputUtilisateur,$tableSchemaDictionnary,$conn);
        //getAlbumSongs($accessToken, $info['album_id']);
        // $artist_id = getArtistInfo($accessToken,$info['idArtist'],$tableSchemaDictionnary,$conn);
        echo "album";
        echo $info;
    }
    else{
        $info = getSong($accessToken,$inputUtilisateur,$tableSchemaDictionnary,$conn); #--> array("album_id":value, "artist_id":value);
        echo "chanson";
        echo $info;
    }
}
