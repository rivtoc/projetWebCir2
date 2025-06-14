<?php
require_once('constantes.php');
require_once('database.php');

$requestMethod = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'] ?? '';
$path = ltrim($path, '/');
$explo = explode('/', $path);

$db = dbConnect();

$action = $_GET['action'] ?? '';


if ($action === 'stats'){
    $total = dbCompte($db);
    $installAns = dbInstallAns($db);
    $installRegion = dbInstallRegion($db);
    $installAnsRegion = dbInstallAnsRegion($db);
    $nbInstallateurs = dbNbInstallateurs($db);
    $nbOnduleurs = dbNbOnduleurs($db);
    $nbPanneaux = dbNbPanneaux($db);

    header('Content-Type: application/json');
    echo json_encode([
        'total' => $total,
        'installAns' => $installAns,
        'installRegion' => $installRegion,
        'installAnsRegion' => $installAnsRegion,
        'nbInstallateurs' => $nbInstallateurs,
        'nbOnduleurs' => $nbOnduleurs,
        'nbPanneaux' => $nbPanneaux
    ]);
    exit;
}


if ($action === 'search') {
    // Récupérer toutes les options pour menus déroulants
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
if ($action === 'filteredResults') {
    $marqueOnduleur = $_GET['marqueOnduleur'] ?? '';
    $marquePanneau = $_GET['marquePanneau'] ?? '';
    $departement = $_GET['departement'] ?? '';

    $resultats = dbRequestFilteredResults($db, $marqueOnduleur, $marquePanneau, $departement);

    header('Content-Type: application/json');
    if ($resultats === false) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de la requête en base']);
    } else {
        echo json_encode($resultats);
    }
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
if ($action === 'filteredResultsPoint') {
    $annee = $_GET['annee'] ?? '';
    $departement = $_GET['departement'] ?? '';

    $resultats = dbRequestPoints($db, $annee, $departement);

    header('Content-Type: application/json');
    if ($resultats === false) {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de la requête en base']);
    } else {
        echo json_encode($resultats);
    }
    exit;
}

// Pour afficher les 100 premières installations
if ($action === 'donnees'){
    $donnees = dbDonnees($db);

    header('Content-Type: application/json');
    echo json_encode([
        'donnees' => $donnees,
    ]);
    exit;
}

// Pour ajouter une installation dans la base de données
// if($requestMethod == 'POST'){ // Méthode POST
//     dbAjout($db);
// }

if ($action == 'ajout') {
    header('Content-Type: application/json');
    $result = dbAjout($db);
    if ($result) {
        echo json_encode(['success' => 'Installation ajoutée avec succès.']);
    } else {
        http_response_code(500);
        echo json_encode(['error' => 'Erreur lors de l\'ajout']);
    }
    exit;
}

/*if ($action === 'allResults') {
    $stmt = $db->prepare("SELECT * FROM projetWeb");
    $stmt->execute();

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($results);
    exit;
}*/

if ($action === 'oneResult') {
    $id = $_GET['id'] ?? null;

    if (!$id || !is_numeric($id)) {
        http_response_code(400);
        echo json_encode(['error' => 'ID invalide']);
        exit;
    }

    $stmt = $db->prepare("SELECT * FROM projetWeb WHERE id = ?");
    $stmt->execute([$id]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {
        header('Content-Type: application/json');
        echo json_encode($result);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Installation non trouvée']);
    }
    exit;
}




// Si on arrive ici, c’est que la route n’a pas été trouvée ou mauvaise méthode
header('HTTP/1.1 404 Not Found');
echo json_encode(['error' => 'Route non trouvée ou méthode non autorisée']);
exit;
