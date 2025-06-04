# \\Author: Groupe 110
# \\Company: ISEN Yncréa Ouest
##

#-------------------------------------------------------------------------------
#--- Create database and add user ----------------------------------------------
#-------------------------------------------------------------------------------
CREATE DATABASE panneau_projet;
USE panneau_projet;
CREATE USER 'project'@'localhost' IDENTIFIED BY 'project_panneau_cir2';
grant ALL PRIVILEGES ON panneau_projet.* TO 'project'@'localhost';
FLUSH PRIVILEGES;
