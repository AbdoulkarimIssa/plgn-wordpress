<?php

function deleteRecords($conn, $days) {
    // Calcul de la date limite
    $limitDate = date('Y-m-d', strtotime("-$days days"));

    // Suppression des enregistrements dans la table 'Artist'
    $sql = "DELETE FROM Artist WHERE lastUpdated > '".$limitDate."'";
    $conn->exec($sql);

    // Suppression des enregistrements dans la table 'Album'
    $sql = "DELETE FROM Album WHERE lastUpdated > '".$limitDate."'";
    $conn->exec($sql);

    // Suppression des enregistrements dans la table 'Song'
    $sql = "DELETE FROM Song WHERE lastUpdated > '".$limitDate."'";
    $conn->exec($sql);

    echo "deleted";
}

function countExpiredRecords($conn, $days) {
    // Calcul de la date limite
    $limitDate = date('Y-m-d', strtotime("-$days days"));

    // Compteur total
    $totalCount = 0;

    // Compteur pour la table 'Artist'
    $sql = "SELECT COUNT(*) AS count FROM Artist WHERE lastUpdated > '$limitDate'";
    $result = $conn->query($sql);
    $count = $result->fetch(PDO::FETCH_ASSOC)['count'];
    $totalCount += $count;

    // Compteur pour la table 'Album'
    $sql = "SELECT COUNT(*) AS count FROM Album WHERE lastUpdated > '$limitDate'";
    $result = $conn->query($sql);
    $count = $result->fetch(PDO::FETCH_ASSOC)['count'];
    $totalCount += $count;

    // Compteur pour la table 'Song'
    $sql = "SELECT COUNT(*) AS count FROM Song WHERE lastUpdated > '$limitDate'";
    $result = $conn->query($sql);
    $count = $result->fetch(PDO::FETCH_ASSOC)['count'];
    $totalCount += $count;

    // Retourner la somme du compte
    return $totalCount;
}
