<?php
/**
 * Requires the "spotify.php" file.
 */
require_once("spotify.php");

/**
 * Retrieves information from Spotify API based on the access token, user input, and type.
 *
 * @param string $accessToken The access token for Spotify API authentication.
 * @param string $inputUtilisateur The user input for search query.
 * @param string $type The type of information to retrieve (Artist, Album, Song).
 * @return array|null Returns the retrieved data from the Spotify API.
 */
function getInfosSpotify($accessToken, $inputUtilisateur, $type) {
    $inputUtilisateur = str_replace(' ', '+', $inputUtilisateur);
    $url = 'https://api.spotify.com/v1/search?q=' . $inputUtilisateur . '&type=' . $type . '&limit=1';
    // Options de la requête
    $get = curl_init();
    $authorization = 'Authorization: Bearer ' . $accessToken;
    curl_setopt($get, CURLOPT_URL, $url);
    curl_setopt($get, CURLOPT_HTTPHEADER, array($authorization));
    curl_setopt($get, CURLOPT_RETURNTRANSFER, true);

    // Exécution de la requête et récupération des résultats
    $response = curl_exec($get);
    $data = json_decode($response, true);

    // Fermeture de la session cURL
    curl_close($get);

    return $data;
}

/**
 * Retrieves an artist from Spotify API by search query and inserts the artist into the database.
 *
 * @param string $accessToken The access token for Spotify API authentication.
 * @param string $inputUtilisateur The user input for search query.
 * @param array $tableSchemaDictionnary The schema dictionary for the database table.
 * @param resource $conn The database connection resource.
 * @return string|null Returns the Spotify artist ID.
 */
function getArtistBySearch($accessToken, $inputUtilisateur, $tableSchemaDictionnary, $conn) {
    $url_type = 'artist';
    $data = getInfosSpotify($accessToken, $inputUtilisateur, $url_type);
    $artistId = $data['artists']['items'][0]['id'];
    $urlArtistInfos = 'https://api.spotify.com/v1/artists/' . $artistId;
    $get = curl_init();
    $authorization = 'Authorization: Bearer ' . $accessToken;
    curl_setopt($get, CURLOPT_URL, $urlArtistInfos);
    curl_setopt($get, CURLOPT_HTTPHEADER, array($authorization));
    curl_setopt($get, CURLOPT_RETURNTRANSFER, true);

    // Exécution de la requête et récupération des résultats
    $response = curl_exec($get);
    $data = json_decode($response, true);

    $idSpotifyArtist = $data['id'];
    $name = $data['name'];
    $popularity = $data['popularity'];

    curl_close($get);

    insertArtist($idSpotifyArtist, $name, $popularity, $tableSchemaDictionnary, $conn);

    return $idSpotifyArtist;
}

/**
 * Retrieves an album from Spotify API by search query and inserts the album into the database.
 *
 * @param string $accessToken The access token for Spotify API authentication.
 * @param string $inputUtilisateur The user input for search query.
 * @param array $tableSchemaDictionnary The schema dictionary for the database table.
 * @param resource $conn The database connection resource.
 * @return array Returns an array containing the Spotify artist ID and album ID.
 */
function getAlbumBySearch($accessToken, $inputUtilisateur, $tableSchemaDictionnary, $conn) {
    $url_type = "album";
    $data = getInfosSpotify($accessToken, $inputUtilisateur, $url_type);

    // Single Album Parser
    $IdSpotifyAlbum = $data['albums']['items'][0]['id'];
    $name = $data['albums']['items'][0]['name'];
    $IdSpotifyArtist = $data['albums']['items'][0]['artists'][0]['id'];
    $image = $data['albums']['items'][0]['images'][1]['url'];
    $releaseDate = $data['albums']['items'][0]['release_date'];
    $tracksNumber = $data['albums']['items'][0]['total_tracks'];

    insertAlbum($IdSpotifyAlbum, $IdSpotifyArtist, $image, $name, $releaseDate, $tracksNumber, $tableSchemaDictionnary, $conn);
    return array('artist_id' => $IdSpotifyArtist, 'album_id' => $IdSpotifyAlbum);
}

/**
 * Retrieves a song from Spotify API by search query and inserts the song into the database.
 *
 * @param string $accessToken The access token for Spotify API authentication.
 * @param string $inputUtilisateur The user input for search query.
 * @param array $tableSchemaDictionnary The schema dictionary for the database table.
 * @param resource $conn The database connection resource.
 * @return array|null Returns an array containing the Spotify artist ID and album ID if the song is part of an album.
 */
function getSongBySearch($accessToken, $inputUtilisateur, $tableSchemaDictionnary, $conn) {
    $url_type = "track";
    $data = getInfosSpotify($accessToken, $inputUtilisateur, $url_type);

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
        return array("artist_id" => $IdSpotifyArtist, "album_id" => $IdSpotifyAlbum);
    }
}

/**
 * Retrieves albums by artist from Spotify API and inserts them into the database.
 *
 * @param string $accessToken The access token for Spotify API authentication.
 * @param string $artist_id The Spotify artist ID.
 * @param array $tableSchemaDictionnary The schema dictionary for the database table.
 * @param resource $conn The database connection resource.
 * @return array Returns an array of album IDs.
 */
function getArtistAlbums($accessToken, $artist_id, $tableSchemaDictionnary, $conn) {
    $url = "https://api.spotify.com/v1/artists/" . $artist_id . "/albums?limit=10";
    $get = curl_init();
    $authorization = 'Authorization: Bearer ' . $accessToken;
    curl_setopt($get, CURLOPT_URL, $url);
    curl_setopt($get, CURLOPT_HTTPHEADER, array($authorization));
    curl_setopt($get, CURLOPT_RETURNTRANSFER, true);

    // Exécution de la requête et récupération des résultats
    $response = curl_exec($get);
    $data = json_decode($response, true);
    curl_close($get);

    $items = $data['items'];
    $album_ids = array();
    foreach ($items as $item) {
        $IdSpotifyAlbum = $item['id'];
        $name = $item['name'];
        $IdSpotifyArtist = $artist_id;
        $image = $item['images'][1]['url'];
        $releaseDate = $item['release_date'];
        $tracksNumber = $item['total_tracks'];
        array_push($album_ids, $IdSpotifyAlbum);
        insertAlbum($IdSpotifyAlbum, $IdSpotifyArtist, $image, $name, $releaseDate, $tracksNumber, $tableSchemaDictionnary, $conn);
    }
    return $album_ids;
}

/**
 * Retrieves songs from an album by artist from Spotify API and inserts them into the database.
 *
 * @param string $accessToken The access token for Spotify API authentication.
 * @param string $artist_id The Spotify artist ID.
 * @param string $album_id The Spotify album ID.
 * @param array $tableSchemaDictionnary The schema dictionary for the database table.
 * @param resource $conn The database connection resource.
 * @return void
 */
function getAlbumSongs($accessToken, $artist_id, $album_id, $tableSchemaDictionnary, $conn) {
    $url = "https://api.spotify.com/v1/albums/" . $album_id . "/tracks?limit=30";
    $get = curl_init();
    $authorization = 'Authorization: Bearer ' . $accessToken;
    curl_setopt($get, CURLOPT_URL, $url);
    curl_setopt($get, CURLOPT_HTTPHEADER, array($authorization));
    curl_setopt($get, CURLOPT_RETURNTRANSFER, true);

    // Exécution de la requête et récupération des résultats
    $response = curl_exec($get);
    $data = json_decode($response, true);
    curl_close($get);

    $items = $data['items'];
    foreach ($items as $item) {
        $IdSpotifySong = $item['id'];
        $IdSpotifyAlbum = $album_id;
        $IdSpotifyArtist = $artist_id;
        $name = $item['name'];
        $duration = $item['duration_ms'];
        $popularity = 0; // no data available
        $tracksNumber = $data['total'];
        $songNumber = $item['track_number'];
        insertSong($IdSpotifySong, $IdSpotifyAlbum, $IdSpotifyArtist, $name, $duration, $popularity, $tracksNumber, $songNumber, $tableSchemaDictionnary, $conn);
    }
}

/**
 * Retrieves an artist by artist ID from Spotify API and inserts the artist into the database.
 *
 * @param string $accessToken The access token for Spotify API authentication.
 * @param string $artist_id The Spotify artist ID.
 * @param array $tableSchemaDictionnary The schema dictionary for the database table.
 * @param resource $conn The database connection resource.
 * @return void
 */
function getArtistById($accessToken, $artist_id, $tableSchemaDictionnary, $conn) {
    $url = "https://api.spotify.com/v1/artists/" . $artist_id;
    $get = curl_init();
    $authorization = 'Authorization: Bearer ' . $accessToken;
    curl_setopt($get, CURLOPT_URL, $url);
    curl_setopt($get, CURLOPT_HTTPHEADER, array($authorization));
    curl_setopt($get, CURLOPT_RETURNTRANSFER, true);

    // Exécution de la requête et récupération des résultats
    $response = curl_exec($get);
    $data = json_decode($response, true);
    curl_close($get);

    $idSpotifyArtist = $artist_id;
    $name = $data['name'];
    $popularity = $data['popularity'];
    insertArtist($idSpotifyArtist, $name, $popularity, $tableSchemaDictionnary, $conn);
}

/**
 * Retrieves an album by album ID from Spotify API and inserts the album into the database.
 *
 * @param string $accessToken The access token for Spotify API authentication.
 * @param string $artist_id The Spotify artist ID.
 * @param string $album_id The Spotify album ID.
 * @param array $tableSchemaDictionnary The schema dictionary for the database table.
 * @param resource $conn The database connection resource.
 * @return void
 */
function getAlbumById($accessToken, $artist_id, $album_id, $tableSchemaDictionnary, $conn) {
    $url = "https://api.spotify.com/v1/albums/" . $artist_id;
    $get = curl_init();
    $authorization = 'Authorization: Bearer ' . $accessToken;
    curl_setopt($get, CURLOPT_URL, $url);
    curl_setopt($get, CURLOPT_HTTPHEADER, array($authorization));
    curl_setopt($get, CURLOPT_RETURNTRANSFER, true);

    // Exécution de la requête et récupération des résultats
    $response = curl_exec($get);
    $data = json_decode($response, true);
    curl_close($get);

    $IdSpotifyAlbum = $album_id;
    $name = $data['name'];
    $IdSpotifyArtist = $artist_id;
    $image = $data['images'][0]['url'];
    $releaseDate = $data['release_date'];
    $tracksNumber = $data['total_tracks'];
    insertAlbum($IdSpotifyAlbum, $IdSpotifyArtist, $image, $name, $releaseDate, $tracksNumber, $tableSchemaDictionnary, $conn);
}

/**
 * Dispatches the appropriate action based on the type of information and retrieves data from Spotify API.
 *
 * @param string $accessToken The access token for Spotify API authentication.
 * @param string $type The type of information to retrieve (Artist, Album, Song).
 * @param string $inputUtilisateur The user input for search query.
 * @param array $tableSchemaDictionnary The schema dictionary for the database table.
 * @param resource $conn The database connection resource.
 * @return void
 */
function dispatch($accessToken, $type, $inputUtilisateur, $tableSchemaDictionnary, $conn) {
    if ($type == 'Artist') {
        $artist_rows = selectRecords($type, $inputUtilisateur, $conn);
        if ($artist_rows == 0) {
            $artist_id = getArtistBySearch($accessToken, $inputUtilisateur, $tableSchemaDictionnary, $conn);
            $albums_id_list = getArtistAlbums($accessToken, $artist_id, $tableSchemaDictionnary, $conn);
            foreach ($albums_id_list as $album_id) {
                getAlbumSongs($accessToken, $artist_id, $album_id, $tableSchemaDictionnary, $conn);
            }
            echo "<h3> Data From Spotify <h3>";
            $artist_rows = selectRecords($type, $inputUtilisateur, $conn);
        }
        
    } elseif ($type == 'Album') {
        $album_rows = selectRecords($type, $inputUtilisateur, $conn);
        if ($album_rows == 0) {
            $info = getAlbumBySearch($accessToken, $inputUtilisateur, $tableSchemaDictionnary, $conn);
            $artist_id = $info['artist_id'];
            $album_id = $info['album_id'];
            getArtistById($accessToken, $artist_id, $tableSchemaDictionnary, $conn);
            getAlbumSongs($accessToken, $artist_id, $album_id, $tableSchemaDictionnary, $conn);
            echo "<h3> Data From Spotify <h3>";
            $album_rows = selectRecords($type, $inputUtilisateur, $conn);

        }
    } else {
        $song_rows = selectRecords($type, $inputUtilisateur, $conn);
        if ($song_rows == 0) {
            $info = getSongBySearch($accessToken, $inputUtilisateur, $tableSchemaDictionnary, $conn); #--> array("album_id":value, "artist_id":value);
            $artist_id = $info['artist_id'];
            $album_id = $info['album_id'];
            getArtistById($accessToken, $artist_id, $tableSchemaDictionnary, $conn);
            getAlbumById($accessToken, $artist_id, $album_id, $tableSchemaDictionnary, $conn);
            getAlbumSongs($accessToken, $artist_id, $album_id, $tableSchemaDictionnary, $conn);
            echo "<h3> Data From Spotify <h3>";
            $song_rows = selectRecords($type, $inputUtilisateur, $conn);

        }
    }
}
