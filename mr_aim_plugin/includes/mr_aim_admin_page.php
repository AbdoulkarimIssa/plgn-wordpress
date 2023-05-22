<?php

/**
 * Updates the 'duree' parameter in the Params table.
 *
 * @param int $value The new value for 'duree' in days.
 * @param PDO $conn The PDO database connection object.
 * @return void
 */
function updateTableParams($value, $conn)
{
    $duree = $value; //jours
    $insertRequest = "UPDATE Params SET duree = " . $duree . " WHERE id = 1;";
    $nbLignesModif = $conn->exec($insertRequest);
}

/**
 * Deletes records from the database based on expiration criteria.
 *
 * @param PDO $conn The PDO database connection object.
 * @return void
 */
function deleteRecords($conn)
{
    $fieldName = "duree";
    $sql = "SELECT " . $fieldName . " FROM Params WHERE id = 1";
    $result = $conn->query($sql);
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $value = $row[$fieldName];
    $limitDate = date('Y-m-d', strtotime("-$value days"));

    // Suppression des enregistrements dans la table 'Artist'
    $sql = "DELETE FROM Artist WHERE lastUpdated <= '" . $limitDate . "'";
    $conn->exec($sql);

    // Suppression des enregistrements dans la table 'Album'
    $sql = "DELETE FROM Album WHERE lastUpdated <='" . $limitDate . "'";
    $conn->exec($sql);

    // Suppression des enregistrements dans la table 'Song'
    $sql = "DELETE FROM Song WHERE lastUpdated <= '" . $limitDate . "'";
    $conn->exec($sql);

    echo "deleted";
}

/**
 * Counts the number of expired records in the database.
 *
 * @param PDO $conn The PDO database connection object.
 * @return int The total count of expired records.
 */
function countExpiredRecords($conn)
{
    $fieldName = "duree";
    $sql = "SELECT " . $fieldName . " FROM Params WHERE id = 1";
    $result = $conn->query($sql);
    $row = $result->fetch(PDO::FETCH_ASSOC);
    $value = $row[$fieldName];
    $limitDate = date('Y-m-d', strtotime("-$value days"));
    $totalCount = 0;

    // Compteur pour la table 'Artist'
    $sql = "SELECT COUNT(*) AS count FROM Artist WHERE lastUpdated <= '$limitDate'";
    $result = $conn->query($sql);
    $count = $result->fetch(PDO::FETCH_ASSOC)['count'];
    $totalCount += $count;

    // Compteur pour la table 'Album'
    $sql = "SELECT COUNT(*) AS count FROM Album WHERE lastUpdated <= '$limitDate'";
    $result = $conn->query($sql);
    $count = $result->fetch(PDO::FETCH_ASSOC)['count'];
    $totalCount += $count;

    // Compteur pour la table 'Song'
    $sql = "SELECT COUNT(*) AS count FROM Song WHERE lastUpdated <= '$limitDate'";
    $result = $conn->query($sql);
    $count = $result->fetch(PDO::FETCH_ASSOC)['count'];
    $totalCount += $count;

    return $totalCount;
}

/**
 * Clears all data from the database.
 *
 * @param PDO $conn The PDO database connection object.
 * @return void
 */
function viderLabase($conn)
{
    try {
        $tables = array('Artist', 'Album', 'Song');

        foreach ($tables as $table) {
            $sql = "DELETE FROM $table";
            $conn->exec($sql);
            // echo "Les données de la table $table ont été supprimées avec succès.<br>";
        }
    } catch (PDOException $e) {
        echo 'Une erreur s\'est produite : ' . $e->getMessage();
        exit;
    }
}

/**
 * Deletes an artist and their associated records from the database.
 *
 * @param PDO $conn The PDO database connection object.
 * @param string $id The artist ID to delete.
 * @return void
 */
function suprimerArtiste($conn, $id)
{
    $query = "DELETE FROM Artist WHERE idSpotifyArtist = '" . $id . "';";
    $conn->exec($query);

    $query = "DELETE FROM Song WHERE idSpotifyArtist = '" . $id . "';";
    $conn->exec($query);

    $query = "DELETE FROM Album WHERE idSpotifyArtist = '" . $id . "';";
    $conn->exec($query);
}

/**
 * Deletes an album and its associated songs from the database.
 *
 * @param PDO $conn The PDO database connection object.
 * @param string $id The album ID to delete.
 * @return void
 */
function suprimerAlbum($conn, $id)
{
    $query = "DELETE FROM Album WHERE IdSpotifyAlbum = '" . $id . "';";
    $conn->exec($query);

    $query = "DELETE FROM Song WHERE IdSpotifyAlbum = '" . $id . "';";
    $conn->exec($query);
}

/**
 * Deletes a song from the database.
 *
 * @param PDO $conn The PDO database connection object.
 * @param string $id The song ID to delete.
 * @return void
 */
function supprimerSong($conn, $id)
{
    $query = "DELETE FROM Song WHERE IdSpotifySong = '" . $id . "';";
    $conn->exec($query);
}

/**
 * Dispatches the admin actions based on the type of operation.
 *
 * @param string $type The type of operation (Artist, Album, Song).
 * @param string $inputUtilisateur The user input.
 * @param PDO $conn The PDO database connection object.
 * @return void
 */
function dispatchAdmin($type, $inputUtilisateur, $conn)
{
    if ($type == 'Artist') {
        $artist_rows = selectRecords($type, $inputUtilisateur, $conn);
        if ($artist_rows == 0) {
            echo '<style>
                    .no-results {
                        margin: 10px;
                        padding: 10px;
                        border-radius: 5px;
                        background-color: #f8d7da;
                        color: #721c24;
                        text-align: center;
                    }
                </style>';

            echo '<div class="no-results">';
            echo '<p>Aucun artiste nommé : '.$inputUtilisateur.' trouvé dans la base sqlite.</p>';
            echo '</div>';

        }
    } elseif ($type == 'Album') {
        $album_rows = selectRecords($type, $inputUtilisateur, $conn);
        if ($album_rows == 0) {
            echo '<style>
                    .no-results {
                        margin: 10px;
                        padding: 10px;
                        border-radius: 5px;
                        background-color: #f8d7da;
                        color: #721c24;
                        text-align: center;
                    }
                </style>';

            echo '<div class="no-results">';
            echo '<p>Aucun album nommé : '.$inputUtilisateur.' trouvé dans la base sqlite.</p>';
            echo '</div>';

        }
    } else {
        $song_rows = selectRecords($type, $inputUtilisateur, $conn);
        if ($song_rows == 0) {
            echo '<style>
                    .no-results {
                        margin: 10px;
                        padding: 10px;
                        border-radius: 5px;
                        background-color: #f8d7da;
                        color: #721c24;
                        text-align: center;
                    }
                </style>';

            echo '<div class="no-results">';
            echo '<p>Aucune chanson nommée : '.$inputUtilisateur.' trouvée dans la base sqlite.</p>';
            echo '</div>';

        }
    }
}
