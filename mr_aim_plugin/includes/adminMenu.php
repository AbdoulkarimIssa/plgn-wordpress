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


function render_retention_options_page() {
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
}