<?php
// Fonction pour créer le formulaire dans le menu d'administration
function create_custom_admin_menu() {
    add_menu_page(
        'Menu Admin du Plugin Spotify', // Titre de la page
        'Menu Admin du Plugin Spotify', // Titre du menu
        'manage_options', // Capacité requise pour accéder à la page
        'custom-retention-options', // Slug de la page
        'render_retention_options_page', // Callback pour afficher la page
        'dashicons-admin-generic'  // Position du menu dans la barre latérale (optionnel)
    );
}
add_action('admin_menu', 'create_custom_admin_menu');

require_once('sqlitedb/mr_aim_select_records.php');
require_once('mr_aim_admin_page.php');
function render_retention_options_page() {
    function makeConnectionFromAdmin(){
        $adminSideDbName = '../wp-content/plugins/mr_aim_plugin/includes/sqlitedb/spotify.sqlite';

        /*Essai de connexion en créant on objet connexion avec les informations de la BDD*/
        try {
            //echo "sqlite:$dbname";
            $conn = new PDO("sqlite:$adminSideDbName");
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
    $connAdmin = makeConnectionFromAdmin();
    $countLine = countExpiredRecords($connAdmin);
    if ($countLine > 0){
        echo '<div style="display: inline-flex; justify-content: center; align-items: center; background-color: red; border-radius: 10px; padding: 10px; margin-top: 10px;">';
        echo '<h3 style="color: white;">Mise à jour des données nécessaire !!!!</h3>';
        echo '</div>';

        echo '<style>
        .alert {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 10px;
            padding: 10px;
            border-radius: 5px;
            background-color: #ffc107;
            color: #fff;
        }
        
        .alert-message {
            flex-grow: 1;
            margin-right: 10px;
        }
        
        .alert-button {
            background-color: #dc3545;
            color: #fff;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
        }
        
        .alert-button:hover {
            background-color: #c82333;
        }
    </style>';

    echo '<div class="alert alert-warning" role="alert">';
    echo '<div class="alert-message">';
    echo "Il y a $countLine enregistrement(s) expiré(s) à supprimer.";
    echo '</div>';
    echo '<form method="POST" style="margin-top: 10px;">';
    echo '<button type="submit" name="delete" class="alert-button">Supprimer les enregistrements expirés</button>';
    echo '</form>';
    echo '</div>';


        if (isset($_POST['delete'])) {
            deleteRecords($connAdmin);
            echo '<div style="background-color: #4CAF50; color: white; padding: 10px; border-radius: 5px; margin: 10px; text-align: center;">';
            echo 'Les enregistrements expirés ont été supprimés.';
            echo '</div>';

        }
    } else {
        echo '<div style="display: inline-flex; justify-content: center; align-items: center; background-color: green; border-radius: 10px; padding: 10px;">';
        echo '<h3 style="color: white;"> Les données de la base sqlite sont fraîches et prêtes à être requêtées !!  <h3>';
        echo '</div>';               
    }
    
    echo '<style>
        .form-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .form-container label {
            margin-bottom: 10px;
        }
        
        .form-container input[type="radio"] {
            margin-right: 5px;
        }
        
        .form-container input[type="text"],
        .form-container input[type="number"] {
            padding: 5px;
            margin-bottom: 10px;
            width: 200px;
        }
        
        .form-container input[type="submit"] {
            padding: 8px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        
        .form-container input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>';

    echo '<div style="display: flex; justify-content: space-around; margin: 20px;">';
    echo '<div>';
    echo '<h2> Recherche de données dans la base sqlite </h2>';
    echo '<form method="POST" class="form-container">';
    echo '<div>';
    echo '<label><input type="radio" name="type" value="Artist"> Artist</label>';
    echo '<label><input type="radio" name="type" value="Album"> Album</label>';
    echo '<label><input type="radio" name="type" value="Song"> Song</label>';
    echo '</div>';
    echo '<br>';
    echo '<div>';
    // echo '<label>Recherche :</label>';
    echo '<input type="text" name="search">';
    echo '<input type="submit" value="Valider">';
    echo '</div>';
    echo '</form>';
    echo '</div>';
    
    echo '<div>';
    echo '<h2> Mise à jour du paramètre de rétention </h2>';
    echo '<form method="POST" class="form-container">';
    echo '<label for="retention_days">Nombre de jours de rétention :</label>';
    echo '<input type="number" name="retention_days" id="retention_days" min="0" max="365" required>';
    echo '<br>';
    echo '<label for="query_limit">Limite de retour des requêtes :</label>';
    echo '<input type="number" name="query_limit" id="query_limit" min="1" max="100" required>';
    echo '<br><br>';
    echo '<input type="submit" name="retention_days" value="Envoyer">';
    echo '</form>';
    echo '</div>';
    
    echo '<div>';
    echo '<h2> Suppression de données de la base sqlite par Id</h2>';
    echo '<form method="POST" class="form-container">';
    echo '<div>';
    echo '<label><input type="radio" name="t" value="Artist"> Artist</label>';
    echo '<label><input type="radio" name="t" value="Album"> Album</label>';
    echo '<label><input type="radio" name="t" value="Song"> Song</label>';
    echo '</div>';
    echo '<br>';
    echo '<div>';
    // echo '<label>Recherche :</label>';
    echo '<input type="text" name="D">';
    echo '<input type="submit" value="Valider">';
    echo '</div>';
    echo '</form>';
    echo '</div>';
    echo '</div>';
    

    echo '<style>
    .delete-form-container {
        position: fixed;
        top: 30px;
        right: 1%;
        transform: translateX(-50%);
        display: flex;
        flex-direction: column;
        align-items: center;
        margin-top: 25px;
    }
    
    .delete-button {
        background-color: red;
        color: white;
        border: none;
        padding: 10px;
        font-size: 16px;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .delete-button i {
        margin-right: 5px;
    }
</style>';

echo '<form method="POST" class="delete-form-container">';
echo '<button type="submit" name="delete_database" class="delete-button">';
echo 'Vider la base de données';
echo '</button>';
echo '</form>';


    if(isset($_POST['retention_days'])){
        $retention_days = $_POST['retention_days'];
        $query_limit = $_POST['query_limit'];
        updateTableParams($retention_days,$connAdmin);
        }
    if(isset($_POST['delete_database'])){
        // echo "test";
        //$delete_database = $_POST['delete_database'];
        viderLabase($connAdmin);
        }
    if(isset($_POST['type']) && isset($_POST['search'])){
            $type = $_POST['type'];
            $search = $_POST['search'];
            // echo "Le type sélectionné est : ".$type."<br>";
            // echo "La recherche effectuée est : ".$search."<br>";
            dispatchAdmin( $type, $search,$connAdmin);
        }
    if(isset($_POST['t']) && isset($_POST['D'])){
        $type = $_POST['t'];
        $Delete = $_POST['D'];
        // echo "Le type sélectionné est : ".$type."<br>";
        // echo "La recherche effectuée est : ".$Delete."<br>";
        //dispatchAdmin( $type, $search,$connAdmin);
        if ($type == "Artist") {
            suprimerArtiste($connAdmin,$Delete);
        }if ($type == "Album") {
            suprimerAlbum($connAdmin,$Delete);
        } else {
            supprimerSong($connAdmin,$Delete);
        }
        
    }
}
?>