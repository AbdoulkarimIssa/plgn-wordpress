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

function getArtistBySearch($accessToken,$inputUtilisateur,$tableSchemaDictionnary,$conn){
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

function getAlbumBySearch($accessToken,$inputUtilisateur,$tableSchemaDictionnary,$conn){
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

function getSongBySearch($accessToken,$inputUtilisateur,$tableSchemaDictionnary,$conn){
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

    if ($album_type == "album") {
        return array("artist_id"=>$IdSpotifyArtist, "album_id"=>$IdSpotifyAlbum);
    }


}


function getArtistAlbums($accessToken,$artist_id,$tableSchemaDictionnary,$conn){

    $url= "https://api.spotify.com/v1/artists/".$artist_id."/albums?limit=10";
    $get = curl_init();
    $authorization = 'Authorization: Bearer '. $accessToken;
    curl_setopt($get, CURLOPT_URL, $url);
    curl_setopt($get, CURLOPT_HTTPHEADER, array($authorization));
    curl_setopt($get, CURLOPT_RETURNTRANSFER, true);


    // Exécution de la requête et récupération des résultats
    $response = curl_exec($get);
    $data = json_decode($response,true);
    curl_close($get);

    $items = $data['items'];
    $album_ids = array();
    foreach($items as $item){
        $IdSpotifyAlbum = $item['id'];
        $name = $item['name'];
        $IdSpotifyArtist = $artist_id;
        $image = $item['images'][1]['url'];
        $releaseDate = $item['release_date'];
        $tracksNumber =$item['total_tracks'];
        array_push($album_ids, $IdSpotifyAlbum);
        insertAlbum($IdSpotifyAlbum, $IdSpotifyArtist, $image, $name, $releaseDate, $tracksNumber, $tableSchemaDictionnary, $conn);
    }
    return $album_ids;
}

function getAlbumSongs($accessToken,$artist_id, $album_id,$tableSchemaDictionnary,$conn){
    
    $url= "https://api.spotify.com/v1/albums/".$album_id."/tracks?limit=30";
    $get = curl_init();
    $authorization = 'Authorization: Bearer '. $accessToken;
    curl_setopt($get, CURLOPT_URL, $url);
    curl_setopt($get, CURLOPT_HTTPHEADER, array($authorization));
    curl_setopt($get, CURLOPT_RETURNTRANSFER, true);


    // Exécution de la requête et récupération des résultats
    $response = curl_exec($get);
    $data = json_decode($response,true);
    curl_close($get);
    
    $items = $data['items'];
    foreach($items as $item){
        $IdSpotifySong = $item['id'];
        $IdSpotifyAlbum = $album_id;
        $IdSpotifyArtist = $artist_id;
        $name = $item['name'];
        $duration = $item['duration_ms'];
        $popularity = 0; // no data available
        $tracksNumber = $data['total'];
        $songNumber = $data['track_number'];
        insertSong($IdSpotifySong, $IdSpotifyAlbum, $IdSpotifyArtist, $name, $duration, $popularity, $tracksNumber, $songNumber, $tableSchemaDictionnary, $conn);
    }
}

function getArtistById($accessToken,$artist_id,$tableSchemaDictionnary,$conn){
    $url= "https://api.spotify.com/v1/artists/".$artist_id;
    $get = curl_init();
    $authorization = 'Authorization: Bearer '. $accessToken;
    curl_setopt($get, CURLOPT_URL, $url);
    curl_setopt($get, CURLOPT_HTTPHEADER, array($authorization));
    curl_setopt($get, CURLOPT_RETURNTRANSFER, true);


    // Exécution de la requête et récupération des résultats
    $response = curl_exec($get);
    $data = json_decode($response,true);
    curl_close($get);
    
    $idSpotifyArtist = $artist_id;
    $name = $data['name'];
    $popularity = $data['popularity'];
    insertArtist($idSpotifyArtist,$name,$popularity,$tableSchemaDictionnary,$conn);
}


function getAlbumById($accessToken,$artist_id, $album_id,$tableSchemaDictionnary,$conn){
    $url= "https://api.spotify.com/v1/albums/".$artist_id;
    $get = curl_init();
    $authorization = 'Authorization: Bearer '. $accessToken;
    curl_setopt($get, CURLOPT_URL, $url);
    curl_setopt($get, CURLOPT_HTTPHEADER, array($authorization));
    curl_setopt($get, CURLOPT_RETURNTRANSFER, true);


    // Exécution de la requête et récupération des résultats
    $response = curl_exec($get);
    $data = json_decode($response,true);
    curl_close($get);
    
    $IdSpotifyAlbum = $album_id;
    $name = $data['name'];
    $IdSpotifyArtist = $artist_id;
    $image = $data['images'][0]['url'];
    $releaseDate = $data['release_date'];
    $tracksNumber =$data['total_tracks'];
    insertAlbum($IdSpotifyAlbum, $IdSpotifyArtist, $image, $name, $releaseDate, $tracksNumber, $tableSchemaDictionnary, $conn);
}


function dispatch($accessToken, $type, $inputUtilisateur,$tableSchemaDictionnary,$conn){
    if ($type == 'Artist'){
        $artist_rows = selectRecords($type, $inputUtilisateur, $conn);
        if ($artist_rows == 0){
            $artist_id = getArtistBySearch($accessToken,$inputUtilisateur,$tableSchemaDictionnary,$conn);
            $albums_id_list = getArtistAlbums($accessToken,$artist_id,$tableSchemaDictionnary,$conn);   
            foreach($albums_id_list as $album_id){
                getAlbumSongs($accessToken,$artist_id, $album_id,$tableSchemaDictionnary,$conn);
            }
        } 
    }
    elseif($type == 'Album'){
        $album_rows = selectRecords($type, $inputUtilisateur, $conn);
        if ($album_rows == 0){
            $info = getAlbumBySearch($accessToken,$inputUtilisateur,$tableSchemaDictionnary,$conn);
            $artist_id = $info['artist_id'];
            $album_id = $info['album_id'];
            getArtistById($accessToken,$artist_id,$tableSchemaDictionnary,$conn);
            getAlbumSongs($accessToken,$artist_id, $album_id,$tableSchemaDictionnary,$conn);
        }
    }
    else{
        $song_rows = selectRecords($type, $inputUtilisateur, $conn);
        if ($song_rows == 0){
            $info = getSongBySearch($accessToken,$inputUtilisateur,$tableSchemaDictionnary,$conn); #--> array("album_id":value, "artist_id":value);
            $artist_id = $info['artist_id'];
            $album_id = $info['album_id'];
            getArtistById($accessToken,$artist_id,$tableSchemaDictionnary,$conn);
            getAlbumById($accessToken,$artist_id, $album_id,$tableSchemaDictionnary,$conn);
            getAlbumSongs($accessToken,$artist_id, $album_id,$tableSchemaDictionnary,$conn);
        }
    }
}

