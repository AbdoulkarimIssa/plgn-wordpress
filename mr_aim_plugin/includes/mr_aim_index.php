<!DOCTYPE html>
<html>
<head>
	<title>Barre de recherche PHP</title>
</head>
<body>
	<h1>Recherche de données</h1>
	<form method="get" action="">
		<label for="search">Recherche :</label>
		<input type="text" id="search" name="search" placeholder="Entrez votre recherche ici">
		<input type="submit" value="Rechercher">
	</form>
	<?php
    ############################################################################################
    # Créer la base de donnée et les tables si jamais elles n'ont pas encore été créées.       #
    require_once 'sqlitedb/mr_aim_create_database.php';                                        #
    ############################################################################################  

    # Reste du script
	if(isset($_GET['search'])) {
		// Récupération de la recherche utilisateur
		$search = $_GET['search'];
		// Traitement de la recherche
		// ...
		// Affichage des résultats
		// ...
	}
    
	?>
</body>
</html>
