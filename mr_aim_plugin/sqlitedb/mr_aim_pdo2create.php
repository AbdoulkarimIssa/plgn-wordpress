<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>PDO 2 : CREATE et INSERT</title>


    </head>
    <body>
        <h1>Création d'une table et ajout de données dans la BDD ECommerce</h1>
        <?php
        /*Inclusion d'un fichier de configuration pour centraliser les informations de connexion*/
        require_once 'mr_aim_pdo0dbconfig.php';

        /*Essai de connexion en créant on objet connexion avec les informations de la BDD*/
        try {
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
        echo "<br>Réalisation du reste du script php.";

        /*
        Objectif : créer :

        CREATE TABLE IF NOT EXISTS tasks (
            task_id     INT AUTO_INCREMENT PRIMARY KEY,
            subject     VARCHAR (255)        DEFAULT NULL,
            start_date  DATE                 DEFAULT NULL,
            end_date    DATE                 DEFAULT NULL,
            description VARCHAR (400)        DEFAULT NULL
        );

        !!! Adapter le nom de la table pour faire de multiples essais !!!
        */

        //Préparation de la requête
        $req1 = "
                CREATE TABLE IF NOT EXISTS tasks1 (
                    task_id     INTEGER  PRIMARY KEY AUTOINCREMENT,
                    subject     VARCHAR (255)        DEFAULT NULL,
                    start_date  DATE                 DEFAULT NULL,
                    end_date    DATE                 DEFAULT NULL,
                    description VARCHAR (400)        DEFAULT NULL
                    );
                ";

        $req2 = '
                INSERT INTO tasks1 (subject,start_date,end_date,description)'
                .'VALUES ("Sujet", DATE(), DATE(), "Description");
                ';

        //PDO::exec() retourne le nombre de lignes qui ont été modifiées ou effacées
        //par la requête SQL exécutée.
        //Si aucune ligne n'est affectée, la fonction PDO::exec() retournera 0.
        $nbLignesModif = $conn->exec($req1);
        echo "<br>Lignes modifiées = $nbLignesModif";


        $nbLignesModif = $conn->exec($req2);
        echo "<br>Lignes modifiées = $nbLignesModif";

        //Purger la requete SQL
        $req1 = null;
        $req2 = null;
        //Fermer la connexion SQL (si absent, automatique à la fin du script)
        $conn = null;

        ?>
    </body>
</html>
