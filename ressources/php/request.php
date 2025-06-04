<?php
require_once('constantes.php');
require_once('database.php');

$requestMethod = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'] ?? '';
$path = ltrim($path, '/');
$explo = explode('/', $path);

$db = dbConnect();

$action = $_GET['action'] ?? '';

if ($action === 'search') {
    // Requêtes pour remplir les menus déroulants
    $marquesOnduleur = dbRequestMarqueOnduleur($db);
    $marquesPanneau = dbRequestMarquePanneau($db);
    $departements = dbRequestDepartement($db);

    header('Content-Type: application/json');
    echo json_encode([
        'marquesOnduleur' => $marquesOnduleur,
        'marquesPanneau' => $marquesPanneau,
        'departements' => $departements
    ]);
    exit;
}
if ($action === 'carte') {
    // Requêtes pour remplir les menus déroulants
    $annee= dbRequestAnnee($db);
    $departements = dbRequestDepartement($db);

    header('Content-Type: application/json');
    echo json_encode([
        'annee' => $annee,
        'departements' => $departements
    ]);
    exit;
}

/*if ($action === 'getCaracteristiques') {
    $marqueOnduleur = $_GET['marqueOnduleur'] ?? '';
    $marquePanneau = $_GET['marquePanneau'] ?? '';
    $departement = $_GET['departement'] ?? '';

    $result = dbRequestFilteredResults($db, $marqueOnduleur, $marquePanneau, $departement);

    header('Content-Type: application/json');
    echo json_encode($result);
    exit;
}*/

// Si on arrive ici, c’est que la route n’a pas été trouvée ou mauvaise méthode
header('HTTP/1.1 404 Not Found');
echo json_encode(['error' => 'Route non trouvée ou méthode non autorisée']);
exit;
