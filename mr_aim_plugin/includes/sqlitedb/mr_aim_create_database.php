<?php
    /*Inclusion d'un fichier de configuration pour centraliser les informations de connexion*/
    require_once 'mr_aim_pdo0dbconfig.php';

    /*Essai de connexion en créant on objet connexion avec les informations de la BDD*/
    try {
        $conn = new PDO("sqlite:$dbname");
        echo "<br>Connexion OK sur $dbname chez $host.";
    }

    /*Si erreur ou exception, interception du message*/
    catch (PDOException $pe) {
        echo '<br>Arrêt du script.';
        //Fonction DIE() identique à EXIT()
        die("<br>Erreur de connexion sur $dbname chez $host :" . $pe->getMessage());
    }

    //Si le bloc TRY-CATCH est OK , le reste du script est réalisé
    //Si erreur dans bloc TRY-CATCH, arrêt du script car fonction DIE
    echo "<br>Réalisation du reste du script php.";

    /*
    Objectif : créer :

    CREATE TABLE IF NOT EXISTS tasks (
        task_id     INT AUTO_INCREMENT PRIMARY KEY,
        subject     VARCHAR (255)        DEFAULT NULL,
        start_date  DATE                 DEFAULT NULL,
        end_date    DATE                 DEFAULT NULL,
        description VARCHAR (400)        DEFAULT NULL
    );

    !!! Adapter le nom de la table pour faire de multiples essais !!!
    */

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
    echo "Table Artist created";

    $nbLignesModif = $conn->exec($createAlbumRequest);
    echo "Table Album created";

    $nbLignesModif = $conn->exec($createSongRequest);
    echo "Table Song created";

    echo('Database and Tables Created');
    // //Purger la requete SQL
    $createArtistRequest = null;
    $createAlbumRequest = null;
    $createSongRequest = null;
    //Fermer la connexion SQL (si absent, automatique à la fin du script)
    $conn = null;
?>