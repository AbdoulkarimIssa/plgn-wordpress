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
