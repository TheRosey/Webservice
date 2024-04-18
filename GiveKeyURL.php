<?php

// Code to get the cleapi so you can access the fichefrais
// PDO USED IN ACTIVE CODE, WORKS THE SAME AS THE MYSQLI COMMENTED CODE
// Database settings
$db = "frais_gsb";
$dbhost = "localhost";
$dbport = 3306;
$dbuser = "root";
$dbpasswd = "root";

// PDO Connect
$pdo = new PDO('mysql:host=' . $dbhost . ';port=' . $dbport . ';dbname=' . $db . '', $dbuser, $dbpasswd);
$pdo->exec("SET CHARACTER SET utf8");

// GET URL settings for login
$login = isset($_GET['login']) ? $_GET['login'] : '';
$mdpapi = isset($_GET['mdpapi']) ? $_GET['mdpapi'] : '';

// Check Auth.
$stmt = $pdo->prepare("SELECT * FROM users2 WHERE login = :login AND mdpapi = :mdpapi");
$stmt->bindParam(':login', $login);
$stmt->bindParam(':mdpapi', $mdpapi);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if($user) {
    // Random key (20characters (cleapi))
    $cleapi = substr(md5(uniqid()), 0, 20);

    // Update cleapi in DB
    $updateStmt = $pdo->prepare("UPDATE users2 SET cleapi = :cleapi WHERE id = :id");
    $updateStmt = $pdo=>prepare("UPDATE users2 SET heureapi = CURRENT_TIMESTAMP() WHERE id=:id");
    $updateStmt->bindParam(':cleapi', $cleapi);
    $updateStmt->bindParam(':id', $user['id']);
    $updateStmt->execute();

    // Return as a table
    $response = [
        'nom' => $user['nom'],
        'prenom' => $user['prenom'],
        'login' => $user['login'],
        'cleapi' => $cleapi
    ];

    // Display resulting JSON
    echo json_encode($response);
} else {
    // Failed Auth.
    echo json_encode(['error' => 'Authentification échouée']);
}

//close connect
$pdo = null;
//Epitome URL to get the key
//http://localhost/MissionWebService/WebService/GiveKeyURL.php?login=Rosey&mdpapi=rootapi

/*

//MYSQLI CODE

// Database settings
$db = "frais_gsb";
$dbhost = "localhost";
$dbport = 3306;
$dbuser = "root";
$dbpasswd = "root";

//MYSQLI Connect
$mysqli = new mysqli($dbhost, $dbuser, $dbpasswd, $db, $dbport);

// Check Auth if error with mysqli
if ($mysqli->connect_error) {
    die("Erreur de connexion à la base de données : " . $mysqli->connect_error);
}

// GET URL settings for login
$login = isset($_GET['login']) ? $_GET['login'] : '';
$mdpapi = isset($_GET['mdpapi']) ? $_GET['mdpapi'] : '';

// Random key (20characters (cleapi))
$stmt = $mysqli->prepare("SELECT * FROM users2 WHERE login = ? AND mdpapi = ?");
$stmt->bind_param('ss', $login, $mdpapi);
$stmt->execute();
$result = $stmt->get_result();

$user = $result->fetch_assoc();

if($user) {
    // Random key (20characters (cleapi))
    $cleapi = substr(md5(uniqid()), 0, 20);

    // Update cleapi in DB
    $updateStmt = $mysqli->prepare("UPDATE users2 SET cleapi = ? WHERE id = ?");
    $updateStmt->bind_param('si', $cleapi, $user['id']);
    $updateStmt->execute();

    // Return as a table
    $response = [
        'nom' => $user['nom'],
        'prenom' => $user['prenom'],
        'login' => $user['login'],
        'cleapi' => $cleapi
    ];

    // Display resulting JSON
    echo json_encode($response);
} else {
    // Failed Auth.
    echo json_encode(['error' => 'Authentification échouée']);
}

// Close connect
$mysqli->close();

//Epitome URL
//http://localhost/MissionWebService/WebService/GiveKeyURL.php?login=Rosey&mdpapi=rootapi

*/