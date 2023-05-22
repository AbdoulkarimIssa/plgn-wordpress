<?php

    $tableSchemaDictionnary = array(
        "Artist" => "(`idSpotifyArtist`, `name`, `popularity`, `lastUpdated`)",
        "Album" => "(`IdSpotifyAlbum`, `IdSpotifyArtist`, `image`, `name`, `releaseDate`, `tracksNumber`, `lastUpdated`)",
        "Song" => "(`IdSpotifySong`, `IdSpotifyAlbum`, `IdSpotifyArtist`, `name`, `duration`, `popularity`, `tracksNumber`, `songNumber`, `lastUpdated`)"
    );
  

    function insertArtist($idSpotifyArtist,$name,$popularity,$tableSchemaDictionnary,$conn){
        $insertRequest = 'INSERT INTO Artist '.$tableSchemaDictionnary['Artist'].''
        .' VALUES ("'
        .$idSpotifyArtist.'", "'
        .$name.'", "'
        .$popularity.'", DATE());';
        try {
            // echo "<p>".$insertRequest."</p>";
            $nbLignesModif = $conn->exec($insertRequest);
            // if ($nbLignesModif == 0){
            //     echo "<p>Enregistrement pour Id :".$idSpotifyArtist." already exists.</p>";
            // }else{
            //     echo "<p>Nouvel Artiste créé avec succès</p>";
            // }
            
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
            // if ($nbLignesModif == 0){
            //     echo "<p>Enregistrement pour Id :".$IdSpotifyAlbum." already exists.</p>";
            // }else{
            //     echo "<p>Nouvel Album  créé avec succès</p>";
            // }
            
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
            // if ($nbLignesModif == 0){
            //     echo "<p>Enregistrement pour Id :".$IdSpotifySong." already exists.</p>";
            // }else{
            //     echo "<p>Nouvelle Track  créé avec succès</p>";
            // }
            
        } catch(PDOException $e) {
            echo "Erreur: " . $e->getMessage();
        }
    }