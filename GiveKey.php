<?php

//Gives the key

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

// Auth check for login and pass
if(isset($_POST['login']) && isset($_POST['mdpapi'])) {
    $login = $_POST['login'];
    $mdpapi = $_POST['mdpapi'];

    // Auth check
    $stmt = $pdo->prepare("SELECT * FROM users2 WHERE login = :login AND mdpapi = :mdpapi");
    $stmt->bindParam(':login', $login);
    $stmt->bindParam(':mdpapi', $mdpapi);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user) {
        // cleapi random gen
        $cleapi = substr(md5(uniqid()), 0, 20);

        // update cleapi in DB
        $updateStmt = $pdo->prepare("UPDATE users2 SET cleapi = :cleapi WHERE id = :id");
        $updateStmt->bindParam(':cleapi', $cleapi);
        $updateStmt->bindParam(':id', $user['id']);
        $updateStmt->execute();

        // Return as table
        $response = [
            'nom' => $user['nom'],
            'prenom' => $user['prenom'],
            'login' => $user['login'],
            'cleapi' => $cleapi
        ];

        // Resulting JSON
        echo json_encode($response);
    } else {
        // Auth failed
        echo json_encode(['error' => 'Authentification échouée']);
    }
} else {
    // Missing params
    echo json_encode(['error' => 'Paramètres manquants']);
}

$pdo = null;
?>
