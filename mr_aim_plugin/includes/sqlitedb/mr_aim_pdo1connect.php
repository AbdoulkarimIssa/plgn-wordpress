<?php
	require_once 'sqlitedb/mr_aim_pdo0dbconfig.php';                                        #

    /*Essai de connexion en créant on objet connexion avec les informations de la BDD*/
    try {
        echo "sqlite:$dbname";
        $conn = new PDO("sqlite:$dbname");
        //echo "<br>Connexion OK sur $dbname chez $host.";
    }

    /*Si erreur ou exception, interception du message*/
    catch (PDOException $pe) {
        echo '<br>Arrêt du script.';
        //Fonction DIE() identique à EXIT()
        die("<br>Erreur de connexion sur $dbname chez $host :" . $pe->getMessage());
    }
