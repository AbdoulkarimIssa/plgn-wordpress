<?php
    function selectRecords($type, $search, $conn){
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
        $selectQuery = 'SELECT * FROM '.$type.' WHERE name LIKE "%'.$search.'%";';
        $rows = $conn->query($selectQuery)->fetchAll(PDO::FETCH_ASSOC);
        $rowCount =  count($rows);

        // Vérification du nombre de lignes retournées
        if ($rowCount > 0) {
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
            echo "Aucun enregistrement trouvé dans la table \"" . $table . "\".";
        }
    }

    // selectRecords($type, $search, $conn);