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

require_once('sqlitedb/mr_aim_select_records.php');
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
		<div style='display:flex;'>
		<label><input type="radio" name="type" value="Artist" onclick="showSearch()"> Artist</label>
		<label><input type="radio" name="type" value="Album" onclick="showSearch()"> Album</label>
		<label><input type="radio" name="type" value="Song" onclick="showSearch()"> Song</label>
		<br>
		</div>
		<div id="search" style="display:none">
			<label>Recherche : </label>
			<input type="text" name="search">
			<input type="submit" value="Valider">
		</div>
	</form>

        <form method="POST">
        <label for="retention_days">Nombre de jours de rétention :</label>
        <input type="number" name="retention_days" id="retention_days" min="1" max="365" required>
        <br>
        <label for="query_limit">Limite de retour des requêtes :</label>
        <input type="number" name="query_limit" id="query_limit" min="1" max="100" required>
        <br><br>
        <input type="submit" name="submit" value="Envoyer">
    </form>

    <form method="POST">
		<div style='display:flex;'>
		<label><input type="radio" name="t" value="Artist" onclick="showSearchdelete()"> Artist</label>
		<label><input type="radio" name="t" value="Album" onclick="showSearchdelete()"> Album</label>
		<label><input type="radio" name="t" value="Song" onclick="showSearchdelete()"> Song</label>
		<br>
		</div>
		<div id="test" style="display:none">
			<label>Recherche : </label>
			<input type="text" name="D">
			<input type="submit" value="Valider">
		</div>
	</form>

    <script>
		function showSearch(){
			// Affiche la barre de recherche si au moins un bouton est sélectionné
			var radios = document.getElementsByName("type");
			var search = document.getElementById("search");
			for(var i = 0; i < radios.length; i++){
				if(radios[i].checked){
					search.style.display = "block";
					break;
				}
				else{
					search.style.display = "none";
				}
			}
			// Remplit la valeur de l'input hidden avec le bouton sélectionné
			var type = document.querySelector('input[name="type"]:checked').value;
			var inputType = document.getElementById("input-type");
			inputType.value = type;
		}


        function showSearchdelete(){
			// Affiche la barre de recherche si au moins un bouton est sélectionné
			var radios = document.getElementsByName("t");
			var search = document.getElementById("test");
			for(var i = 0; i < radios.length; i++){
				if(radios[i].checked){
					search.style.display = "block";
					break;
				}
				else{
					search.style.display = "none";
				}
			}
			// Remplit la valeur de l'input hidden avec le bouton sélectionné
			var type = document.querySelector('input[name="t"]:checked').value;
			var inputType = document.getElementById("input-type");
			inputType.value = type;
		}
	</script>



    <?php

    // Traitement du formulaire
    if(isset($_POST['submit'])){
        echo "test";
        $retention_days = $_POST['retention_days'];
        $query_limit = $_POST['query_limit'];
        updateTableParams($retention_days,$connAdmin);
        }

    ?>
    <form method="POST">
        <input type="submit" name="delete_database" value="Vider la base de données">
    </form>
    <?php

    if(isset($_POST['delete_database'])){
        echo "test";
        //$delete_database = $_POST['delete_database'];
        viderLabase($connAdmin);
        }
    if(isset($_POST['type']) && isset($_POST['search'])){
            $type = $_POST['type'];
            $search = $_POST['search'];
            echo "Le type sélectionné est : ".$type."<br>";
            echo "La recherche effectuée est : ".$search."<br>";
            dispatchAdmin( $type, $search,$connAdmin);
        }
    if(isset($_POST['t']) && isset($_POST['D'])){
        $type = $_POST['t'];
        $Delete = $_POST['D'];
        echo "Le type sélectionné est : ".$type."<br>";
        echo "La recherche effectuée est : ".$Delete."<br>";
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




