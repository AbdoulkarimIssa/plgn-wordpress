<?php
    /*Inclusion d'un fichier de configuration pour centraliser les informations de connexion*/
    require_once 'mr_aim_pdo0dbconfig.php';

    /*Essai de connexion en créant on objet connexion avec les informations de la BDD*/
    try {
        echo "sqlite:$dbname";
        $conn = new PDO("sqlite:$dbname");
        echo "<br>Connexion OK sur $dbname chez $host.";
    }

    /*Si erreur ou exception, interception du message*/
    catch (PDOException $pe) {
        echo '<br>Arrêt du script.';
        //Fonction DIE() identique à EXIT()
        die("<br>Erreur de connexion sur $dbname chez $host :" . $pe->getMessage());
    }

    //Si le bloc TRY-CATCH est OK , le reste du script est réalisé
    //Si erreur dans bloc TRY-CATCH, arrêt du script car fonction DIE
    echo '<br>Réalisation du reste du script php.';

    //Fermer la connexion SQL (si absent, automatique à la fin du script)
    $conn = null;
?>
