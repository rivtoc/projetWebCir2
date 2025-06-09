<?php
/**
 * \\Author: VG"Imothep"
 * \\Company: ISEN Yncréa Ouest
 */

  require_once('constantes.php');



 
  function dbConnect()
  {
    try
    {
      $db = new PDO('mysql:host='.DB_SERVER.';dbname='.DB_NAME.';charset=utf8;'.
        'port='.DB_PORT, DB_USER, DB_PASSWORD);
      $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    }
    catch (PDOException $exception)
    {
      error_log('Connection error: '.$exception->getMessage());
      return false;
    }
    return $db;
  }

 function dbCompte($db)
{
    try
    {
        $request = 'SELECT COUNT(id) AS total FROM projetWeb';
        $statement = $db->prepare($request);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return (int) ($result['total'] ?? 0);
    }
    catch (PDOException $exception)
    {
        error_log('Request error: '.$exception->getMessage());
        return false;
    }
}

function dbInstallAns($db){
  try
    {
        $request = 'SELECT annee, COUNT(id) AS nbInstallAns FROM projetWeb GROUP BY annee';
        $statement = $db->prepare($request);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    catch (PDOException $exception)
    {
        error_log('Request error: '.$exception->getMessage());
        return false;
    }
}

function dbInstallRegion($db){
  try
    {
        $request = 'SELECT region, COUNT(id) AS nbInstallRegion FROM projetWeb GROUP BY region';
        $statement = $db->prepare($request);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    catch (PDOException $exception)
    {
        error_log('Request error: '.$exception->getMessage());
        return false;
    }
}


function dbInstallAnsRegion($db){
  try
    {
        $request = 'SELECT annee, region, COUNT(id) AS nbInstallAnsRegion FROM projetWeb GROUP BY annee, region';
        $statement = $db->prepare($request);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    catch (PDOException $exception)
    {
        error_log('Request error: '.$exception->getMessage());
        return false;
    }
}


function dbNbInstallateurs($db){
  try
    {
        $request = 'SELECT COUNT(DISTINCT installateur) AS nbInstallateurs FROM projetWeb';
        $statement = $db->prepare($request);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return (int) ($result['nbInstallateurs'] ?? 0);
    }
    catch (PDOException $exception)
    {
        error_log('Request error: '.$exception->getMessage());
        return false;
    }
}


function dbNbOnduleurs($db){
  try
    {
        $request = 'SELECT COUNT(DISTINCT marque_onduleur) AS nbOnduleurs FROM projetWeb';
        $statement = $db->prepare($request);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return (int) ($result['nbOnduleurs'] ?? 0);
    }
    catch (PDOException $exception)
    {
        error_log('Request error: '.$exception->getMessage());
        return false;
    }
}


function dbNbPanneaux($db){
  try
    {
        $request = 'SELECT COUNT(DISTINCT marque_panneau) AS nbPanneaux FROM projetWeb';
        $statement = $db->prepare($request);
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        return (int) ($result['nbPanneaux'] ?? 0);
    }
    catch (PDOException $exception)
    {
        error_log('Request error: '.$exception->getMessage());
        return false;
    }
}

  
function getRandomSubset(array $arr, int $max = 20): array {
      if (count($arr) <= $max) {
          return $arr;
      }
      shuffle($arr); // mélange aléatoire
      return array_slice($arr, 0, $max);
  }

function dbRequestMarqueOnduleur($db)
{

    try
    {
        $request = 'SELECT DISTINCT marque_onduleur FROM projetWeb ORDER BY marque_onduleur ASC LIMIT 20';
        $statement = $db->prepare($request);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
    catch (PDOException $exception)
    {
        error_log('Request error: '.$exception->getMessage());
        return false;
    }
    return $result;
}

function dbRequestMarquePanneau($db)
{
  try
  {
    $request = 'SELECT DISTINCT marque_panneau FROM projetWeb ORDER BY marque_panneau ASC LIMIT 20';
    $statement = $db->prepare($request);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    
  }
  catch (PDOException $exception)
  {
    error_log('Request error: '.$exception->getMessage());
    return false;
  }
  return $result;
}


function dbRequestDepartement($db)
{
  try
  {
    $request = 'SELECT DISTINCT departement FROM projetWeb ORDER BY departement ASC';
    $statement = $db->prepare($request);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    $result = getRandomSubset($result, 20);
  }
  catch (PDOException $exception)
  {
    error_log('Request error: '.$exception->getMessage());
    return false;
  }
  return $result;
}

function dbRequestAnnee($db)
{
  try
  {
    $request = 'SELECT DISTINCT annee FROM projetWeb ORDER BY annee DESC';
    $statement = $db->prepare($request);
    $statement->execute();
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
  }
  catch (PDOException $exception)
  {
    error_log('Request error: '.$exception->getMessage());
    return false;
  }
  return $result;
}





function dbRequestFilteredResults($db, $marqueOnduleur, $marquePanneau, $departement)
{
    try {
        $request = 'SELECT id, annee, mois, departement, code_insee, nom_ville, code_postal, nom_pays, region, 
            marque_onduleur, modele_onduleur, marque_panneau, modele_panneau, installateur, 
            production_pvgis, longitude, latitude, puissance_crete, surface, orientation, 
            orientation_optimum, pente, pente_optimum, nb_onduleur, nb_panneau 
            FROM projetWeb WHERE 1=1';

        $params = [];

        if (!empty($marqueOnduleur)) {
            $request .= ' AND marque_onduleur = :marque_onduleur';
            $params[':marque_onduleur'] = $marqueOnduleur;
        }
        if (!empty($marquePanneau)) {
            $request .= ' AND marque_panneau = :marque_panneau';
            $params[':marque_panneau'] = $marquePanneau;
        }
        if (!empty($departement)) {
            $request .= ' AND departement = :departement';
            $params[':departement'] = $departement;
        }

        $statement = $db->prepare($request);
        $statement->execute($params);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $exception) {
        error_log('Request error: ' . $exception->getMessage());
        return false;
    }
    return $result;
}

function dbRequestPoints($db, $annee, $departement) {
    try {
        $query = "SELECT latitude, longitude, annee, mois, nb_panneau, surface, puissance_crete, nom_pays, nom_ville FROM projetWeb WHERE 1=1";
        $params = [];

        if (!empty($annee)) {
            $query .= " AND annee = :annee";
            $params[':annee'] = $annee;
        }
        if (!empty($departement)) {
            $query .= " AND departement = :departement";
            $params[':departement'] = $departement;
        }

        $statement = $db->prepare($query);
        $statement->execute($params);
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $exception) {
        error_log('Request error: '.$exception->getMessage());
        return false;
    }
    return $result;
}

// Partie back

// Fonction pour obtenir les 100 premières installations
function dbDonnees($db){
  try
    {
        $request = 'SELECT id, annee, mois, departement, code_insee, nom_ville, code_postal, nom_pays, region FROM projetWeb LIMIT 100';
        $statement = $db->prepare($request);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    catch (PDOException $exception)
    {
        error_log('Request error: '.$exception->getMessage());
        return false;
    }
}

// // Fonction pour insérer une installation dans la base de données
function dbAjout($db){
  try
    {
        $request = 'INSERT INTO projetWeb (
                        annee, mois, departement, code_insee, nom_ville, code_postal, 
                        nom_pays, region, marque_onduleur, modele_onduleur, marque_panneau, 
                        modele_panneau, installateur, production_pvgis, longitude, latitude, 
                        puissance_crete, surface, orientation, orientation_optimum, pente, 
                        pente_optimum, nb_onduleur, nb_panneau
                        ) VALUES (
                             :annee, :mois, :departement, :code_insee, :nom_ville, :code_postal, 
                             :nom_pays, :region, :marque_onduleur, :modele_onduleur, :marque_panneau, 
                             :modele_panneau, :installateur, :production_pvgis, :longitude, :latitude, 
                             :puissance_crete, :surface, :orientation, :orientation_optimum, :pente, 
                             :pente_optimum, :nb_onduleur, :nb_panneau
                         )';
        $statement = $db->prepare($request);
        $statement->execute([
                 ':annee' => $_POST['annee'],
                 ':mois' => $_POST['mois'],
                 ':departement' => $_POST['departement'],
                 ':code_insee' => $_POST['code_insee'],
                 ':nom_ville' => $_POST['nom_ville'],
                 ':code_postal' => $_POST['code_postal'],
                 ':nom_pays' => $_POST['nom_pays'],
                 ':region' => $_POST['region'],
                 ':marque_onduleur' => $_POST['marque_onduleur'],
                 ':modele_onduleur' => $_POST['modele_onduleur'],
                 ':marque_panneau' => $_POST['marque_panneau'],
                 ':modele_panneau' => $_POST['modele_panneau'],
                 ':installateur' => $_POST['installateur'],
                 ':production_pvgis' => $_POST['production_pvgis'],
                 ':longitude' => $_POST['longitude'],
                 ':latitude' => $_POST['latitude'],
                 ':puissance_crete' => $_POST['puissance_crete'],
                 ':surface' => $_POST['surface'],
                 ':orientation' => $_POST['orientation'],
                 ':orientation_optimum' => $_POST['orientation_optimum'],
                 ':pente' => $_POST['pente'],
                 ':pente_optimum' => $_POST['pente_optimum'],
                 ':nb_onduleur' => $_POST['nb_onduleur'],
                 ':nb_panneau' => $_POST['nb_panneau'],
        ]);
        echo "Installation ajoutée avec succès.";
    }
    catch (PDOException $exception) {
      error_log('Request error: ' . $exception->getMessage());
      echo "Erreur lors de l'ajout : " . $exception->getMessage();
      return false;
    }
    return true;
}
