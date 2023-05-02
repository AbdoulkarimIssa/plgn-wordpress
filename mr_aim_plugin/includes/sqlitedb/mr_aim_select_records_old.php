<?php
    $tableConditionalQuery = array(
        "Artist" => ''.$type.'WHERE name LIKE "'.$search.'";',
        "Album" => ''.$type.'WHERE name LIKE "'.$search.'";',
        "Song" => ''.$type.'WHERE name LIKE "'.$search.'";'
    );

    $selectQuery = 'SELECT * FROM '.$type.' WHERE name LIKE "%'.$search.'%";';
    echo $selectQuery;
    $resultat = $conn->query($selectQuery); 
    $resultat->setFetchMode(PDO::FETCH_ASSOC);
    ?>
    <div id="container">
        <h4><?php echo $type ?></h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Popularity</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    //fetch — Récupère la ligne suivante d'un jeu de résultats PDO
                    while ($row = $resultat->fetch()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['idSpotifyArtist']); ?></td>
                        <td><?php echo htmlspecialchars($row['name']) ?></td>
                        <td><?php echo htmlspecialchars($row['popularity']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
 
