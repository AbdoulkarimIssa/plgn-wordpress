<?php
    // if ($conn) {
    //     echo "Objet de connexion valide.";
    // } else {
    //     echo "Objet de connexion invalide.";
    // }

    $tableSchemaDictionnary = array(
        "Artist" => "(`idSpotifyArtist`, `name`, `popularity`, `lastUpdated`)",
        "Album" => "(`IdSpotifyAlbum`, `IdSpotifyArtist`, `image`, `name`, `releaseDate`, `tracksNumber`, `lastUpdated`)",
        "Song" => "(`IdSpotifySong`, `IdSpotifyAlbum`, `IdSpotifyArtist`, `name`, `duration`, `popularity`, `tracksNumber`, `songNumber`, `lastUpdated`)"
    );

    // Insertion de données dans la table Artist
    //$tableName = "Artist";
    // $idSpotifyArtist = "spotify:artist:7v4imS0moSyGdXyLgVTIV7";
    // $name = "Lana Del Rey";
    // $popularity = 80;

    // $tableName = "Song";
    // $IdSpotifySong = 'song_id_1';
    // $IdSpotifyAlbum = 'album_id_1';
    // $IdSpotifyArtist = 'artist_id_1';
    // $name = 'Song 1';
    // $duration = 300;
    // $popularity = 80;
    // $tracksNumber = 12;
    // $songNumber = 1;

    // $tableName = "Album";
    // $IdSpotifyAlbum = 'album_id_1';
    // $IdSpotifyArtist = 'artist_id_1';
    // $image = 'image_url';
    // $name = 'Song 1';
    // $releaseDate = "2023-04-01 20:18:54";
    // $tracksNumber = 12;    

    function insertArtist($idSpotifyArtist,$name,$popularity,$tableSchemaDictionnary,$conn){
        $insertRequest = 'INSERT INTO Artist '.$tableSchemaDictionnary['Artist'].''
        .' VALUES ("'
        .$idSpotifyArtist.'", "'
        .$name.'", "'
        .$popularity.'", DATE());';
        try {
            // echo "<p>".$insertRequest."</p>";
            $nbLignesModif = $conn->exec($insertRequest);
            if ($nbLignesModif == 0){
                echo "<p>Enregistrement pour Id :".$idSpotifyArtist." already exists.</p>";
            }else{
                echo "<p>Nouvel Artiste créé avec succès</p>";
            }
            
        } catch(PDOException $e) {
            echo "Erreur: " . $e->getMessage();
        }
    }

    function insertAlbum($IdSpotifyAlbum, $IdSpotifyArtist, $image, $name, $releaseDate, $tracksNumber, $tableSchemaDictionnary, $conn){
        $insertRequest = 'INSERT INTO Album '.$tableSchemaDictionnary['Album'].''
        .' VALUES ("'
        .$IdSpotifyAlbum.'", "'
        .$IdSpotifyArtist.'", "'
        .$image.'", "'
        .$name.'", "'
        .$releaseDate.'", "'
        .$tracksNumber.'", DATE());';
        try {
            // echo "<p>".$insertRequest."</p>";
            $nbLignesModif = $conn->exec($insertRequest);
            if ($nbLignesModif == 0){
                echo "<p>Enregistrement pour Id :".$IdSpotifyAlbum." already exists.</p>";
            }else{
                echo "<p>Nouvel Album  créé avec succès</p>";
            }
            
        } catch(PDOException $e) {
            echo "Erreur: " . $e->getMessage();
        }
    }

    function insertSong($IdSpotifySong, $IdSpotifyAlbum, $IdSpotifyArtist, $name, $duration, $popularity, $tracksNumber, $songNumber, $tableSchemaDictionnary, $conn){
        $insertRequest = 'INSERT INTO Song '.$tableSchemaDictionnary['Song'].''
        .' VALUES ("'
        .$IdSpotifySong.'", "'
        .$IdSpotifyAlbum.'", "'
        .$IdSpotifyArtist.'", "'
        .$name.'", "'
        .$duration.'", "'
        .$popularity.'", "'
        .$tracksNumber.'", "'
        .$songNumber.'", DATE());';
        try {
            // echo "<p>".$insertRequest."</p>";
            $nbLignesModif = $conn->exec($insertRequest);
            if ($nbLignesModif == 0){
                echo "<p>Enregistrement pour Id :".$IdSpotifySong." already exists.</p>";
            }else{
                echo "<p>Nouvelle Track  créé avec succès</p>";
            }
            
        } catch(PDOException $e) {
            echo "Erreur: " . $e->getMessage();
        }
    }




    // if ($tableName == "Artist"){
    //     $insertRequest = 'INSERT INTO '.$tableName.' '.$tableSchemaDictionnary[$tableName].''
    //     .' VALUES ("'
    //     .$idSpotifyArtist.'", "'
    //     .$name.'", "'
    //     .$popularity.'", DATE());';
    // }
    // elseif($tableName == "Album"){
    //     $insertRequest = 'INSERT INTO '.$tableName.' '.$tableSchemaDictionnary[$tableName].''
    //     .' VALUES ("'
    //     .$IdSpotifyAlbum.'", "'
    //     .$IdSpotifyArtist.'", "'
    //     .$image.'", "'
    //     .$name.'", "'
    //     .$releaseDate.'", "'
    //     .$tracksNumber.'", DATE());';
    // }
    // elseif($tableName == "Song"){
    //     $insertRequest = 'INSERT INTO '.$tableName.' '.$tableSchemaDictionnary[$tableName].''
    //     .' VALUES ("'
    //     .$IdSpotifySong.'", "'
    //     .$IdSpotifyAlbum.'", "'
    //     .$IdSpotifyArtist.'", "'
    //     .$name.'", "'
    //     .$duration.'", "'
    //     .$popularity.'", "'
    //     .$tracksNumber.'", "'
    //     .$songNumber.'", DATE());';
    // }
    
    // try {
    //     // echo "<p>".$insertRequest."</p>";
    //     $nbLignesModif = $conn->exec($insertRequest);
    //     if ($nbLignesModif == 0){
    //         echo "<p>Enregistrement pour Id :".$idSpotifyArtist." already exists.</p>";
    //     }else{
    //         echo "<p>Nouvel enregistrement créé avec succès</p>";
    //     }
        
    // } catch(PDOException $e) {
    //     echo "Erreur: " . $e->getMessage();
    // }