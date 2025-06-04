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


  function dbRequestMarqueOnduleur($db)
  {
    try
    {
      $request = 'SELECT DISTINCT marque_onduleur FROM projetWeb ORDER BY marque_onduleur ASC';
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
      $request = 'SELECT DISTINCT marque_panneau FROM projetWeb ORDER BY marque_panneau ASC';
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
        $request = 'SELECT annee, mois, nb_panneau, surface, puissance_crete, nom_pays, nom_ville, longitude, latitude  FROM projetWeb WHERE 1=1';
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