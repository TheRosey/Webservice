# Webservice

Dossier mission WEB Service
MENAGE Tom
BTS SIO 2 / Baudimont ARRAS

----- Fichiers fournis -----
DisplayFiche.php Utilisé pour afficher les fiches après obtention de la clé API
GiveKeyURL.php Utilisé pour obtenir la clé API
GiveKey.php Utilisé pour débug
Base de données "frais_gsb"

----- Environnements utilisés -----
VSCode
UniserverZ / phpmyadmin

----- How to use -----
Lancer UniserverZ avec la base de donnée fournie
Voici une URL Type pour générer une cléAPI via GiveKeyURL
---http://localhost/MissionWebService/WebService/GiveKeyURL.php?login=Rosey&mdpapi=rootapi
Voici une URL Type pour générer la vue des fiches sous forme de tableau une fois la cléAPI obtenu via DisplayFiche
---http://localhost/MissionWebService/WebService/DisplayFiche.php?id_fichefrais=1&cleapi=6cf5c95d4698af8bbf0f
Il faudra bien sur remplacer les valeures avec les valeures de login ou clés correspondantes

----- Note -----
Les codes sont commentés en anglais, et GiveKeyURL a un code en commentaire, il s'agit de l'exact meme mais utilise mysqli au lieu de PDO