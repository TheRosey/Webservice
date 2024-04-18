<?php

//Display fichefrais once cleapi is obtained from GiveKeyURL

// Database settings
$db = "frais_gsb";
$dbhost = "localhost";
$dbport = 3306;
$dbuser = "root";
$dbpasswd = "root";

// Debug
error_reporting(E_ALL);
ini_set('display_errors', 'On');

// PDO Connect
$pdo = new PDO('mysql:host=' . $dbhost . ';port=' . $dbport . ';dbname=' . $db . '', $dbuser, $dbpasswd);
$pdo->exec("SET CHARACTER SET utf8");

// GET URL settings for login
$id_fichefrais = isset($_GET['id_fichefrais']) ? $_GET['id_fichefrais'] : '';
$cleapi = isset($_GET['cleapi']) ? $_GET['cleapi'] : '';

// Check Auth.
$stmtAuth = $pdo->prepare("SELECT * FROM users2 WHERE cleapi = :cleapi AND DATE_ADD(heureapi, INTERVAL 1 MINUTE) > now()");
$stmtAuth->bindParam(':cleapi', $cleapi);
$stmtAuth->execute();

$user = $stmtAuth->fetch(PDO::FETCH_ASSOC);

if($user) {
    // Auth ok, get infos from fichefrais
    $stmtFiche = $pdo->prepare("SELECT * FROM fichefrais WHERE id = :id_fichefrais AND user_id = :user_id");
    $stmtFiche->bindParam(':id_fichefrais', $id_fichefrais);
    $stmtFiche->bindParam(':user_id', $user['id']);  // Use user_id for column name
    $stmtFiche->execute();

    $fiche = $stmtFiche->fetch(PDO::FETCH_ASSOC);

    if($fiche) {
        // Return as table
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

        // Return JSON
        echo json_encode($response);
    } else {
        // Error if no fichefrais found
        echo json_encode(['error' => 'Fiche de frais non trouvée']);
    }
} else {
    // Auth failed
    echo json_encode(['error' => 'Authentification échouée']);
}

$pdo = null;

//Epitome URL
//http://localhost/MissionWebService/WebService/DisplayFiche.php?id_fichefrais=1&cleapi=6cf5c95d4698af8bbf0f
?>