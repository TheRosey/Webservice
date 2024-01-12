<?php
// Database settings
$db = "frais_gsb";
$dbhost = "localhost";
$dbport = 3306;
$dbuser = "root";
$dbpasswd = "root";

$pdo = new PDO('mysql:host=' . $dbhost . ';port=' . $dbport . ';dbname=' . $db . '', $dbuser, $dbpasswd);
$pdo->exec("SET CHARACTER SET utf8");

// Récupérer les paramètres de l'URL
$login = isset($_GET['login']) ? $_GET['login'] : '';
$mdpapi = isset($_GET['mdpapi']) ? $_GET['mdpapi'] : '';

// Vérifier l'authentification
$stmt = $pdo->prepare("SELECT * FROM users2 WHERE login = :login AND mdpapi = :mdpapi");
$stmt->bindParam(':login', $login);
$stmt->bindParam(':mdpapi', $mdpapi);
$stmt->execute();

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if($user) {
    // Générer une clé API aléatoire (20 caractères)
    $cleapi = substr(md5(uniqid()), 0, 20);

    // Mettre à jour la clé API dans la base de données (pour qu'elle ne change jamais)
    $updateStmt = $pdo->prepare("UPDATE users2 SET cleapi = :cleapi WHERE id = :id");
    $updateStmt->bindParam(':cleapi', $cleapi);
    $updateStmt->bindParam(':id', $user['id']);
    $updateStmt->execute();

    // Construire le tableau avec les informations à retourner
    $response = [
        'nom' => $user['nom'],
        'prenom' => $user['prenom'],
        'login' => $user['login'],
        'cleapi' => $cleapi
    ];

    // Afficher le JSON résultant
    echo json_encode($response);
} else {
    // Authentification échouée
    echo json_encode(['error' => 'Authentification échouée']);
}

$pdo = null;
//http://localhost/MissionWebService/GiveKeyURL.php?login=Rosey&mdpapi=rootapi
?>
