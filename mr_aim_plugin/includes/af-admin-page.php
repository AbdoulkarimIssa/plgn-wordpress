
<?php

function updateTableParams($value,$conn){
                // Valeurs à mettre à jour
        //$derniere_mise_a_jour = date("Y-m-d"); // Date de mise à jour actuelle
        $duree = $value; //jours

        //$sql = "UPDATE Params SET derniere_mise_a_jour = :derniere_mise_a_jour, duree = :duree WHERE id = :id";
        $insertRequest = "UPDATE Params SET duree =".$duree.";";
            try {
            // echo "<p>".$insertRequest."</p>";
            $nbLignesModif = $conn->exec($insertRequest);
            // if ($nbLignesModif == 0){
            //     echo "<p>Enregistrement pour Id :".$idSpotifyArtist." already exists.</p>";
            // }else{
            //     echo "<p>Nouvel Artiste créé avec succès</p>";
            // }
            
        } catch(PDOException $e) {
            echo "Erreur: " . $e->getMessage();
        }

    }