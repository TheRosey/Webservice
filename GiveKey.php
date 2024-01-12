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

// Vérifier si les paramètres de login et mdpapi sont fournis
if(isset($_POST['login']) && isset($_POST['mdpapi'])) {
    $login = $_POST['login'];
    $mdpapi = $_POST['mdpapi'];

    // Vérifier l'authentification
    $stmt = $pdo->prepare("SELECT * FROM users2 WHERE login = :login AND mdpapi = :mdpapi");
    $stmt->bindParam(':login', $login);
    $stmt->bindParam(':mdpapi', $mdpapi);
    $stmt->execute();

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if($user) {
        // Générer une clé API aléatoire (vous pouvez personnaliser la génération selon vos besoins)
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
} else {
    // Paramètres manquants
    echo json_encode(['error' => 'Paramètres manquants']);
}

$pdo = null;
?>
