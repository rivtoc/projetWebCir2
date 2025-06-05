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
        $request = 'SELECT annee, mois, nb_panneau, surface, puissance_crete, nom_pays, nom_ville   FROM projetWeb WHERE 1=1';
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

function dbRequestPoints($db, $annee = null, $departement = null) {
    try {
        $query = "SELECT latitude, longitude FROM projetWeb WHERE 1=1";
        $params = [];

        if ($annee !== null) {
            $query .= " AND annee = :annee";
            $params[':annee'] = $annee;
        }
        if ($departement !== null) {
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