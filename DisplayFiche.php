<?php
// Database settings
$db = "frais_gsb";
$dbhost = "localhost";
$dbport = 3306;
$dbuser = "root";
$dbpasswd = "root";

error_reporting(E_ALL);
ini_set('display_errors', 'On');

$pdo = new PDO('mysql:host=' . $dbhost . ';port=' . $dbport . ';dbname=' . $db . '', $dbuser, $dbpasswd);
$pdo->exec("SET CHARACTER SET utf8");

// Récupérer les paramètres de l'URL
$id_fichefrais = isset($_GET['id_fichefrais']) ? $_GET['id_fichefrais'] : '';
$cleapi = isset($_GET['cleapi']) ? $_GET['cleapi'] : '';

// Vérifier l'authentification de l'utilisateur
$stmtAuth = $pdo->prepare("SELECT * FROM users2 WHERE cleapi = :cleapi");
$stmtAuth->bindParam(':cleapi', $cleapi);
$stmtAuth->execute();

$user = $stmtAuth->fetch(PDO::FETCH_ASSOC);

if($user) {
    // Authentification réussie, maintenant récupérer les informations de la fiche de frais
    $stmtFiche = $pdo->prepare("SELECT * FROM fichefrais WHERE id = :id_fichefrais AND user_id = :user_id");
    $stmtFiche->bindParam(':id_fichefrais', $id_fichefrais);
    $stmtFiche->bindParam(':user_id', $user['id']);  // Utiliser 'user_id' comme nom de colonne
    $stmtFiche->execute();

    $fiche = $stmtFiche->fetch(PDO::FETCH_ASSOC);

    if($fiche) {
        // Construire le tableau avec les informations à retourner
        $response = [
            'user' => [
                'nom' => $user['nom'],
                'prenom' => $user['prenom'],
                'login' => $user['login'],
                'cleapi' => $user['cleapi']
            ],
            'fiche' => [
                'id' => $fiche['id'],
                'user_id' => $fiche['user_id'],
                'etat_id' => $fiche['etat_id'],
                'nbjustificatifs' => $fiche['nbjustificatifs'],
                'montantvalide' => $fiche['montantvalide'],
                'datemodif' => $fiche['datemodif'],
                'mois' => $fiche['mois'],
                'annee' => $fiche['annee']
            ]
        ];
                // Ajoutez d'autres paramètres de la fiche de frais selon vos besoins

        // Afficher le JSON résultant
        echo json_encode($response);
    } else {
        // Fiche de frais non trouvée
        echo json_encode(['error' => 'Fiche de frais non trouvée']);
    }
} else {
    // Authentification échouée
    echo json_encode(['error' => 'Authentification échouée']);
}

$pdo = null;

//URL Type pour obtenir la fiche après avoir obtenu la clé :
//http://localhost/MissionWebService/WebService/DisplayFiche.php?id_fichefrais=1&cleapi=6cf5c95d4698af8bbf0f