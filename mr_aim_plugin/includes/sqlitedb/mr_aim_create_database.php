<?php
    //Préparation de la requête
    $createArtistRequest = "
            CREATE TABLE IF NOT EXISTS Artist (
                idSpotifyArtist  VARCHAR (255)   PRIMARY KEY,
                name        VARCHAR (255)        DEFAULT NULL,
                popularity  INTEGER              DEFAULT NULL,
                lastUpdated VARCHAR (255)        DEFAULT NULL
                );
            ";

    //Préparation de la requête
    $createAlbumRequest = "
            CREATE TABLE IF NOT EXISTS Album (
                IdSpotifyAlbum   VARCHAR (255)   PRIMARY KEY,
                IdSpotifyArtist  VARCHAR (255)   DEFAULT NULL,
                image            VARCHAR (255)   DEFAULT NULL,
                name             VARCHAR (255)   DEFAULT NULL,
                releaseDate      VARCHAR (255)   DEFAULT NULL,
                tracksNumber     INTEGER         DEFAULT NULL,
                lastUpdated      VARCHAR (255)   DEFAULT NULL
                );
            ";

    //Préparation de la requête
    $createSongRequest = "
            CREATE TABLE IF NOT EXISTS Song (
                IdSpotifySong    VARCHAR (255)   PRIMARY KEY,                   
                IdSpotifyAlbum   VARCHAR (255)   DEFAULT NULL,
                IdSpotifyArtist  VARCHAR (255)   DEFAULT NULL,
                name             VARCHAR (255)   DEFAULT NULL,
                duration         INTEGER         DEFAULT NULL,
                popularity       INTEGER         DEFAULT NULL,
                tracksNumber     INTEGER         DEFAULT NULL,
                songNumber       INTEGER         DEFAULT NULL,
                lastUpdated      VARCHAR (255)   DEFAULT NULL
                );
            ";



    //PDO::exec() retourne le nombre de lignes qui ont été modifiées ou effacées
    //par la requête SQL exécutée.
    //Si aucune ligne n'est affectée, la fonction PDO::exec() retournera 0.
    $nbLignesModif = $conn->exec($createArtistRequest);
    //echo "Table Artist created";

    $nbLignesModif = $conn->exec($createAlbumRequest);
    //echo "Table Album created";

    $nbLignesModif = $conn->exec($createSongRequest);
    //echo "Table Song created";

    //echo('Database and Tables Created');
    // //Purger la requete SQL
    $createArtistRequest = null;
    $createAlbumRequest = null;
    $createSongRequest = null;