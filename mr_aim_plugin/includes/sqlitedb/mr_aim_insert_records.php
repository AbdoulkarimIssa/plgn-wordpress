<?php
    if ($conn) {
        echo "Objet de connexion valide.";
    } else {
        echo "Objet de connexion invalide.";
    }

    // Insertion de données dans la table Artist
    $idSpotifyArtist = "spotify:artist:7v4imS0moSyGdXyLgVTIV7";
    $name = "Lana Del Rey";
    $popularity = 80;

    $test = 'INSERT INTO Artist (`idSpotifyArtist`, `name`, `popularity`, `lastUpdated`)'
    .' VALUES ("'.$idSpotifyArtist.'", "'.$name.'", "'.$popularity.'", DATE());';

    try {
        echo "<p>".$test."</p>";
        $nbLignesModif = $conn->exec($test);
        if ($nbLignesModif == 0){
            echo "Enregistrement pour Id :".$idSpotifyArtist." already exists.";
        }else{
            echo "Nouvel enregistrement créé avec succès";
        }
        
    } catch(PDOException $e) {
        echo "Erreur: " . $e->getMessage();
    }