


# Introduction

Nous devons créer une nouvelle extension pour le CMS WordPress permettant d'utiliser la base de données du site web d'écoute musicale Spotify.

WordPress pourra être installé : 

- en local via Wamp/Mamp/Xamp

- chez un hébergeur web

Cette extension offrira une accessibilité optimale à ses utilisateurs. Du côté de l'interface d'administration de WordPress, ils pourront aisément consulter, réaliser et enregistrer les réglages de l'extension.

Quant au site web vitrine, il permettra un accès fluide au moteur de recherche, qui utilisera la base de données Spotify. Grâce à cette fonctionnalité, les utilisateurs pourront effectuer des recherches ciblées pour trouver des informations sur des groupes, des albums ou des titres dans la vaste base de données de Spotify. 

En outre, les résultats de recherche seront affichés de manière claire et structurée, offrant aux visiteurs des fiches détaillées sur les artistes, les albums et d'autres informations pertinentes.

# Méthodologie 
## Répartition du travail

D'abord, nous avons discuté de l'approche du projet, puis nous avons travaillé ensemble sur le schéma de la base de données et des tables. 

Ensuite, nous avons divisé le travail en deux parties. Maxime s'est chargé de la gestion des données locales, tandis qu'Issa Mohamed s'est occupé de la gestion de l'API Spotify.


## Gestion du code

Pour la gestion du code, nous avons opté pour l'utilisation de GitHub comme gestionnaire de versions. Le dépôt comprend une branche principale ("master") qui contient le code validé et fonctionnel, approuvé par nous deux. De plus, nous avons également créé une deuxième branche appelée "dev" qui sert de zone de travail pour les modifications en cours de développement. Cela nous permet de collaborer efficacement tout en préservant la stabilité de la branche principale.

# Problème rencontrés
## Problème d'installation

Issa, qui développe sur un PC Linux, a rencontré des difficultés lors de l'installation sur sa machine physique. Par conséquent, il a configuré une machine virtuelle Linux et y a installé WAMPP. Afin de tester le code, Issa transférait celui-ci de la machine hôte vers la machine virtuelle. Cette méthode s'est révélée efficace, cependant, il a rencontré quelques problèmes de droits qu'il a dû corriger manuellement. 
