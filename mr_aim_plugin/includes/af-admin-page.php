<?php

function updateTableParams($value,$conn){
        $duree = $value; //jours
        $insertRequest = "UPDATE Params SET duree = ".$duree." WHERE id = 1;";
        $nbLignesModif = $conn->exec($insertRequest);
        }

function deleteRecords($conn) {
        echo "test 2";
    $fieldName ="duree";
    $sql = "SELECT ".$fieldName." FROM Params WHERE id = 1";
    $result = $conn->query($sql);
        echo "test2";
    $row = $result->fetch(PDO::FETCH_ASSOC);
        echo "test3";
        echo $row;
    $value = $row[$fieldName];
    // $value = 10;
    // Calcul de la date limite
    $limitDate = date('Y-m-d', strtotime("-$value days"));
    echo $limitDate;
     //Suppression des enregistrements dans la table 'Artist'
    echo "test4";
     $sql = "DELETE FROM Artist WHERE lastUpdated < '".$limitDate."'";
     $conn->exec($sql);
     echo "test5";
    // // Suppression des enregistrements dans la table 'Album'
     $sql = "DELETE FROM Album WHERE lastUpdated < '".$limitDate."'";
     $conn->exec($sql);

    // // Suppression des enregistrements dans la table 'Song'
     $sql = "DELETE FROM Song WHERE lastUpdated < '".$limitDate."'";
  $conn->exec($sql);

    echo "deleted";
}

function countExpiredRecords($conn) {
    echo "countExpiredRecords start";
    $fieldName ="duree";
    $sql = "SELECT ".$fieldName." FROM Params WHERE id = 1";
    $result = $conn->query($sql);
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $value = $row[$fieldName];
    // $value = 10;
    // Calcul de la date limite
    $limitDate = date('Y-m-d', strtotime("-$value days"));

    // Compteur total
    $totalCount = 0;

    // Compteur pour la table 'Artist'
    $sql = "SELECT COUNT(*) AS count FROM Artist WHERE lastUpdated < '$limitDate'";
    $result = $conn->query($sql);
    $count = $result->fetch(PDO::FETCH_ASSOC)['count'];
    $totalCount += $count;

    // Compteur pour la table 'Album'
    
    $sql = "SELECT COUNT(*) AS count FROM Album WHERE lastUpdated < '$limitDate'";
    $result = $conn->query($sql);
    $count = $result->fetch(PDO::FETCH_ASSOC)['count'];
    $totalCount += $count;

    // Compteur pour la table 'Song'
    $sql = "SELECT COUNT(*) AS count FROM Song WHERE lastUpdated < '$limitDate'";
    $result = $conn->query($sql);
    $count = $result->fetch(PDO::FETCH_ASSOC)['count'];
    $totalCount += $count;

    // Retourner la somme du compte
    //echo "countExpiredRecords end";
    return $totalCount;
}

function viderLabase($conn){

    try {
            // Requêtes de suppression des tables
        $tables = array('Artist', 'Album', 'Song');
    
        foreach ($tables as $table) {
            $sql = "DELETE FROM $table";
            $conn->exec($sql);
            echo "Les données de la table $table ont été supprimées avec succès.<br>";
        }
        
    } catch (PDOException $e) {
        echo 'Une erreur s\'est produite : ' . $e->getMessage();
        exit;
    }



}

function suprimerArtiste($conn,$id){

$query = "DELETE FROM Artist WHERE idSpotifyArtist = $id";
$conn->exec($query);

$query = "DELETE FROM Song WHERE idSpotifyArtist = $id";
$conn->exec($query);

$query = "DELETE FROM Album WHERE idSpotifyArtist = $id";
$conn->exec($query);

}

function suprimerAlbum($conn,$id){

    $query = "DELETE FROM Album WHERE IdSpotifyAlbum = $id";
    $conn->exec($query);


    $query = "DELETE FROM Song WHERE IdSpotifyAlbum = $id";
    $conn->exec($query);
}

function supprimerSong($conn,$id){

    $query = "DELETE FROM Song WHERE IdSpotifySong = $id";
    $conn->exec($query);
}



function dispatchAdmin($type, $inputUtilisateur,$conn){
    if ($type == 'Artist'){
        $artist_rows = selectRecords($type, $inputUtilisateur, $conn);

    }
    elseif($type == 'Album'){
        $album_rows = selectRecords($type, $inputUtilisateur, $conn);

    }
    else{
        $song_rows = selectRecords($type, $inputUtilisateur, $conn);
    }
}
        