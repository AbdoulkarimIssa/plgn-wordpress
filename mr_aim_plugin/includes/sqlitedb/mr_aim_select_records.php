<?php
    function selectRecords($type, $search, $conn, $column="name"){
        // Récupération des données de la table demandée
        $table = $type;
        if ($table === 'Artist') {
            $columns = array('idSpotifyArtist', 'name', 'popularity', 'lastUpdated');
        } else if ($table === 'Album') {
            $columns = array('IdSpotifyAlbum', 'IdSpotifyArtist', 'image', 'name', 'releaseDate', 'tracksNumber', 'lastUpdated');
        } else if ($table === 'Song') {
            $columns = array('IdSpotifySong', 'IdSpotifyAlbum', 'IdSpotifyArtist', 'name', 'duration', 'popularity', 'tracksNumber', 'songNumber', 'lastUpdated');
        } else {
            die("Table not found.");
        }
        //$countQuery = 'SELECT COUNT(*) FROM '.$type.' WHERE name LIKE "%'.$search.'%";';
        $selectQuery = 'SELECT * FROM '.$type.' WHERE '.$column.' LIKE "%'.$search.'%";';
        $rows = $conn->query($selectQuery)->fetchAll(PDO::FETCH_ASSOC);
        $rowCount =  count($rows);

        // Vérification du nombre de lignes retournées
        if ($rowCount > 0) {
            echo "Voilà les résultats pour la table : ".$table.".";
            // Affichage des résultats dans un tableau
            echo "<table>";
            echo "<tr>";
            foreach ($columns as $column) {
                echo "<th>" . $column . "</th>";
            }
            echo "</tr>";
            foreach ($rows as $row) {
                echo "<tr>";
                foreach ($columns as $column) {
                    echo "<td>" . $row[$column] . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            // Affichage d'un message d'avertissement
            echo "Nous n'avons rien trouvé dans notre base de données. Nous allons récupérer les informations sur Spotify ...";
        }
        return $rowCount;
    }

    // selectRecords($type, $search, $conn);