<?php
// Fonction pour créer le formulaire dans le menu d'administration
function create_custom_admin_menu() {
    add_menu_page(
        'Options de rétention', // Titre de la page
        'Options de rétention', // Titre du menu
        'manage_options', // Capacité requise pour accéder à la page
        'custom-retention-options', // Slug de la page
        'render_retention_options_page', // Callback pour afficher la page
        'dashicons-admin-generic', // Icône du menu (optionnel)
        20 // Position du menu dans la barre latérale (optionnel)
    );
}
add_action('admin_menu', 'create_custom_admin_menu');

    
require_once('af-admin-page.php');
function render_retention_options_page() {
    function makeConnectionFromAdmin(){
        echo "test conn1";
        $adminSideDbName = '../wp-content/plugins/mr_aim_plugin/includes/sqlitedb/spotify.sqlite';

        /*Essai de connexion en créant on objet connexion avec les informations de la BDD*/
        try {
            //echo "sqlite:$dbname";
            echo "test conn2";
            $conn = new PDO("sqlite:$adminSideDbName");
            echo "test conn3";
            //echo "<br>Connexion OK sur $dbname chez $host.";
        }

        /*Si erreur ou exception, interception du message*/
        catch (PDOException $pe) {
            echo '<br>Arrêt du script.';
            //Fonction DIE() identique à EXIT()
            die("<br>Erreur de connexion sur $adminSideDbName :" . $pe->getMessage());
        }
        return $conn;
    }
    echo "test conn5";
    $connAdmin = makeConnectionFromAdmin();
    echo "test conn6";
    //$countLine = 0;
    $countLine = countExpiredRecords($connAdmin);
    if ($countLine > 0){
        echo "<br>"."Mise à jour des données !!!!";
        echo $countLine;
        // Ajoutez un avertissement HTML avec un bouton pour déclencher la suppression.
        echo "<div class='alert alert-warning' role='alert'>";
        echo "Il y a $countLine enregistrement(s) expiré(s) à supprimer.";
        echo "<form method='POST'>";
        echo "<button type='submit' name='delete' class='btn btn-danger'>Supprimer les enregistrements expirés</button>";
        echo "</form>";
        echo "</div>";
        if (isset($_POST['delete'])) {
            echo "test delete";
            deleteRecords($connAdmin);
            echo "Les enregistrements expirés ont été supprimés.";
        }
    } else {
        echo "Données à jour";
    }
    
    ?>
        <form method="POST">
        <label for="retention_days">Nombre de jours de rétention :</label>
        <input type="number" name="retention_days" id="retention_days" min="1" max="365" required>
        <br>
        <label for="query_limit">Limite de retour des requêtes :</label>
        <input type="number" name="query_limit" id="query_limit" min="1" max="100" required>
        <br><br>
        <input type="submit" name="submit" value="Envoyer">
    </form>

    <?php

    // Traitement du formulaire
    if(isset($_POST['submit'])){
        echo "test";
        $retention_days = $_POST['retention_days'];
        $query_limit = $_POST['query_limit'];
        updateTableParams($retention_days,$connAdmin);
        }
    }