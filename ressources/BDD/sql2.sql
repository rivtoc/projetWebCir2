CREATE TABLE IF NOT EXISTS projetWeb
(
  id INT NOT NULL AUTO_INCREMENT,
  annee YEAR NOT NULL,
  mois INT NOT NULL,

  departement VARCHAR(50),
  code_insee INT,
  nom_ville VARCHAR(50),
  code_postal VARCHAR(50),
  nom_pays VARCHAR(50),
  region VARCHAR(50),

  marque_onduleur VARCHAR(40),
  modele_onduleur VARCHAR(50),

  marque_panneau VARCHAR(40),
  modele_panneau VARCHAR(50),

  installateur VARCHAR(50),

  production_pvgis VARCHAR(40),
  longitude FLOAT,
  latitude FLOAT,
  puissance_crete FLOAT,
  surface FLOAT,
  orientation VARCHAR(50),
  orientation_optimum FLOAT,
  pente FLOAT,
  pente_optimum FLOAT,
  nb_onduleur INT,
  nb_panneau INT,

  PRIMARY KEY(id)
)
ENGINE=InnoDB;
