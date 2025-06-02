USE panneau_projet;

-- DROP TABLES (ordre inverse de création pour éviter problèmes)
DROP TABLE IF EXISTS pvgis;
DROP TABLE IF EXISTS lieu;
DROP TABLE IF EXISTS departement;
DROP TABLE IF EXISTS installation;
DROP TABLE IF EXISTS caracteristiques_panneaux;
DROP TABLE IF EXISTS caracteristiques_onduleur;
DROP TABLE IF EXISTS installateur;


-- Création des tables dans l'ordre correct
CREATE TABLE departement
(
  departement VARCHAR(50) NOT NULL,
  PRIMARY KEY(departement)
)
engine = innodb;

CREATE TABLE installation
(
  id INT NOT NULL auto_increment,
  annee YEAR NOT NULL,
  mois INT NOT NULL,
  PRIMARY KEY(id)
)
engine = innodb;

CREATE TABLE lieu
(
  code_insee INT NOT NULL,
  nom_ville VARCHAR(50) NOT NULL,
  code_postal VARCHAR(50) NOT NULL,
  nom_pays VARCHAR(50) NOT NULL,
  departement_bis VARCHAR(50) NOT NULL,
  PRIMARY KEY(code_insee),
  foreign key(departement_bis) REFERENCES departement(departement)
)
engine = innodb;

CREATE TABLE caracteristiques_onduleur
(
  marque_onduleur VARCHAR(40) NOT NULL,
  modele_onduleur VARCHAR(50) NOT NULL,
  PRIMARY KEY(marque_onduleur)
)
engine = innodb;

CREATE TABLE caracteristiques_panneaux
(
  marque_panneau VARCHAR(40) NOT NULL,
  modele_panneau VARCHAR(50) NOT NULL,
  PRIMARY KEY(marque_panneau)
)
engine = innodb;

CREATE TABLE installateur
(
  id INT NOT NULL auto_increment,
  installateur VARCHAR(50) NOT NULL,
  PRIMARY KEY(id)
)
engine = innodb;

CREATE TABLE pvgis
(
  id INT NOT NULL auto_increment,
  production_pvgis VARCHAR(40) NOT NULL,
  longitude float NOT NULL,
  latitude float NOT NULL,
  puissance_crete float NOT NULL,
  surface float NOT NULL,
  orientation float NOT NULL,
  orientation_optimum float NOT NULL,
  pente float NOT NULL,
  pente_optimum float NOT NULL,
  nb_onduleur int NOT NULL,
  nb_panneau int NOT NULL,

  lieu_code_insee INT NOT NULL,
  marque_onduleur_id VARCHAR(40) NOT NULL,
  marque_panneau_id VARCHAR(50) NOT NULL,
  installation_id INT NOT NULL,
  installateur_id INT NOT NULL,

  foreign key(lieu_code_insee) REFERENCES lieu(code_insee),
  foreign key(marque_onduleur_id) REFERENCES caracteristiques_onduleur(marque_onduleur),
  foreign key(marque_panneau_id) REFERENCES caracteristiques_panneaux(marque_panneau),
  foreign key(installation_id) REFERENCES installation(id),
  foreign key(installateur_id) REFERENCES installateur(id),
  PRIMARY KEY(id)
)
engine = innodb;