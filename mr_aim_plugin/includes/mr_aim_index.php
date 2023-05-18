<!DOCTYPE html>
<html>
<head>
	<title>Barre de recherche PHP</title>
</head>
<body>
<!DOCTYPE html>
<html>
  <head>
    <title>Exemple de barre de recherche débloquée par des boutons</title>
  </head>
  <body>
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
	</script>

	<input type="hidden" id="input-type" name="type" value="">
	<?php
	require_once 'sqlitedb/mr_aim_select_records.php';
	require_once 'sqlitedb/mr_aim_insert_records.php';
	require_once 'sqlitedb/mr_aim_pdo1connect.php';
	require_once 'sqlitedb/mr_aim_create_database.php';
	//require_once 'adminMenu.php';
	require_once 'search.php';

	if ($conn) {
		echo "<p>Objet de connexion valide.</p>";
	} else {
		echo "Objet de connexion invalide.";
	}

	// Traitement du formulaire
	if(isset($_POST['type']) && isset($_POST['search'])){
		$type = $_POST['type'];
		$search = $_POST['search'];
		echo "Le type sélectionné est : ".$type."<br>";
		echo "La recherche effectuée est : ".$search."<br>";
		// ici vous pouvez utiliser les variables $type et $search dans votre code PHP
		dispatch($accessToken, $type, $search, $tableSchemaDictionnary, $conn);

		//$artist = getArtistInfo($accessToken,$artistSearch);
		//foreach ($artist as $cle => $valeur) {
		//	echo $cle . " ==> " . $valeur;
		//}
		//insertArtist($artist['id'],$artist['name'],$artist['popularity'],$tableSchemaDictionnary,$conn);

		//selectRecords($type, $search, $conn);
	}

    ############################################################################################
    # Créer la base de donnée et les tables si jamais elles n'ont pas encore été créées.       #
	#require_once 'sqlitedb/mr_aim_create_database.php';                                        #
    #require_once 'sqlitedb/mr_aim_insert_records.php';                                        #
    ############################################################################################  
	?>
</body>
</html>




