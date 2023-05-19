<?php
    // function selectRecords($type, $search, $conn, $column="name"){
    //     // Récupération des données de la table demandée
    //     $table = $type;
    //     if ($table === 'Artist') {
    //         $columns = array('idSpotifyArtist', 'name', 'popularity', 'lastUpdated');
    //     } else if ($table === 'Album') {
    //         $columns = array('IdSpotifyAlbum', 'IdSpotifyArtist', 'image', 'name', 'releaseDate', 'tracksNumber', 'lastUpdated');
    //     } else if ($table === 'Song') {
    //         $columns = array('IdSpotifySong', 'IdSpotifyAlbum', 'IdSpotifyArtist', 'name', 'duration', 'popularity', 'tracksNumber', 'songNumber', 'lastUpdated');
    //     } else {
    //         die("Table not found.");
    //     }
    //     //$countQuery = 'SELECT COUNT(*) FROM '.$type.' WHERE name LIKE "%'.$search.'%";';
    //     $selectQuery = 'SELECT * FROM '.$type.' WHERE '.$column.' LIKE "%'.$search.'%";';
    //     $rows = $conn->query($selectQuery)->fetchAll(PDO::FETCH_ASSOC);
    //     $rowCount =  count($rows);

    //     // Vérification du nombre de lignes retournées
    //     if ($rowCount > 0) {
    //         echo "Voilà les résultats pour la table : ".$table.".";
    //         // Affichage des résultats dans un tableau
    //         echo "<table>";
    //         echo "<tr>";
    //         foreach ($columns as $column) {
    //             echo "<th>" . $column . "</th>";
    //         }
    //         echo "</tr>";
    //         foreach ($rows as $row) {
    //             echo "<tr>";
    //             foreach ($columns as $column) {
    //                 echo "<td>" . $row[$column] . "</td>";
    //             }
    //             echo "</tr>";
    //         }
    //         echo "</table>";
    //     } else {
    //         // Affichage d'un message d'avertissement
    //         echo "Nous n'avons rien trouvé dans notre base de données. Nous allons récupérer les informations sur Spotify ...";
    //     }
    //     return $rowCount;
    // }

    function selectRecords($type, $search, $conn, $column="name"){
        $table = $type;
        $columns = array();
        $related_queries = array();

        switch($table) {
            case 'Artist':
                $columns = array('idSpotifyArtist', 'name', 'popularity', 'lastUpdated');
                $related_queries = array(
                    "Album" => 'SELECT * FROM Album WHERE IdSpotifyArtist = (SELECT IdSpotifyArtist FROM Artist WHERE '.$column.' LIKE "%'.$search.'%");',
                    "Song" => 'SELECT * FROM Song WHERE IdSpotifyArtist = (SELECT IdSpotifyArtist FROM Artist WHERE '.$column.' LIKE "%'.$search.'%");'
                );
                break;
            case 'Album':
                $columns = array('IdSpotifyAlbum', 'IdSpotifyArtist', 'image', 'name', 'releaseDate', 'tracksNumber', 'lastUpdated');
                $related_queries = array(
                    "Artist" => 'SELECT * FROM Artist WHERE idSpotifyArtist = (SELECT IdSpotifyArtist FROM Album WHERE '.$column.' LIKE "%'.$search.'%");',
                    "Song" => 'SELECT * FROM Song WHERE IdSpotifyAlbum = (SELECT IdSpotifyAlbum FROM Album WHERE '.$column.' LIKE "%'.$search.'%");'
                );
                break;
            case 'Song':
                $columns = array('IdSpotifySong', 'IdSpotifyAlbum', 'IdSpotifyArtist', 'name', 'duration', 'popularity', 'tracksNumber', 'songNumber', 'lastUpdated');
                $related_queries = array(
                    "Album" => 'SELECT * FROM Album WHERE IdSpotifyAlbum = (SELECT IdSpotifyAlbum FROM Song WHERE '.$column.' LIKE "%'.$search.'%");',
                    "Artist" => 'SELECT * FROM Artist WHERE idSpotifyArtist = (SELECT IdSpotifyArtist FROM Song WHERE '.$column.' LIKE "%'.$search.'%");'
                );
                break;
            default:
                die("Table not found.");
        }

        $selectQuery = 'SELECT * FROM '.$type.' WHERE '.$column.' LIKE "%'.$search.'%";';
        //echo "query => ".$selectQuery;
        $rows = $conn->query($selectQuery)->fetchAll(PDO::FETCH_ASSOC);
        $rowCount =  count($rows);
        //echo "rowCount => ".$rowCount;

        if ($rowCount > 0) {
            $html = buildHTMLTable($columns, $rows, $table);

            // If there are related queries, execute them and build their tables
            foreach ($related_queries as $related_table => $query) {
                //echo $query."\n";
                $related_rows = $conn->query($query)->fetchAll(PDO::FETCH_ASSOC);
                //echo "rowCount => ".count($related_rows);
                $related_columns = array_keys($related_rows[0]);
                //echo "related_columns => ".$related_columns;
                $html .= buildHTMLTable($related_columns, $related_rows, $related_table);
            }
            
            echo $html;
        }
        return $rowCount;
    }

    function buildHTMLTable($columns, $rows, $table) {
        $html = "<h2>" . $table . "</h2>";
        if (count($rows) > 0) {
            $html .= "<style>
            table {
                border-collapse: collapse;
                width: 100%;
                margin-top: 20px;
            }
            th, td {
                border: 1px solid #ddd;
                padding: 8px;
            }
            tr:nth-child(even){background-color: #f2f2f2;}
            th {
                padding-top: 12px;
                padding-bottom: 12px;
                text-align: left;
                background-color: #000;
                color: white;
            }
            </style>";

            $html .= "<table>";
            $html .= "<tr>";
            foreach ($columns as $column) {
                $html .= "<th>" . $column . "</th>";
            }
            $html .= "</tr>";
            foreach ($rows as $row) {
                $html .= "<tr>";
                foreach ($columns as $column) {
                    if ($column == 'image' && $table == 'Album') {
                        $html .= "<td><img src='" . $row[$column] . "' alt='Album Image' style='width:100px;height:100px;'/></td>";
                    } else {
                        $html .= "<td>" . $row[$column] . "</td>";
                    }
                }
                $html .= "</tr>";
            }
            $html .= "</table>";
        } else {
            $html .= "No results found for " . $table;
        }
        return $html;
    }
