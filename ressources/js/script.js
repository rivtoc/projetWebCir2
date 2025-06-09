'use strict';

document.addEventListener('DOMContentLoaded', init);

async function init() {
  await chargerMenus();
  ajouterEcouteurs();
}

async function chargerMenus() {
  try {
    const response = await fetch('back/request.php?action=search');
    if (!response.ok) throw new Error('Erreur réseau : ' + response.status);

    const data = await response.json();

    remplirMenu(data.marquesOnduleur, 'menuOnduleur');
    remplirMenu(data.marquesPanneau, 'menuPanneau');
    remplirMenu(data.departements, 'menuDepartement');
  } catch (error) {
    console.error('Erreur lors du chargement des menus :', error);
  }
}

function remplirMenu(liste, idSelect) {
  const select = document.getElementById(idSelect);
  if (!select) return;

  select.innerHTML = '<option value="" disabled selected hidden>Sélectionnez...</option>';

  for (const item of liste) {
    const valeur = Object.values(item)[0];
    const option = document.createElement('option');
    option.value = valeur;
    option.textContent = valeur;
    select.appendChild(option);
  }
}

function ajouterEcouteurs() {
  const menus = ['menuOnduleur', 'menuPanneau', 'menuDepartement']; //liste des 3 menus déroulant que l'on veut écouter;
  menus.forEach(id => {           //pour chaque éléments des menus
    const select = document.getElementById(id);
    if (select) {
      select.addEventListener('change', onSelectChange); //on ajoute un écouter change, qui permet de mettre à jour dès qu'il y a une modification;
    } //change permet d'éviter l'usage d'un bouton, et c'est plutôt agréable dans notre situation
  });
}

async function onSelectChange() {
  const marqueOnduleur = document.getElementById('menuOnduleur').value;
  const marquePanneau = document.getElementById('menuPanneau').value;
  const departement = document.getElementById('menuDepartement').value;

  if (!marqueOnduleur || !marquePanneau || !departement) {
    //afficherResultats('Veuillez sélectionner toutes les options.');     //S'execute du moment qu'on a commencé à remplir le formulaire, et que l'on a pas fini
    return;
  }

  await chargerResultatsFiltres(marqueOnduleur, marquePanneau, departement);  //si c'est fini, alors on charge les résultats;
}


async function chargerResultatsFiltres(marqueOnduleur, marquePanneau, departement) {
  const url = `back/request.php?action=filteredResults&marqueOnduleur=${encodeURIComponent(marqueOnduleur)}&marquePanneau=${encodeURIComponent(marquePanneau)}&departement=${encodeURIComponent(departement)}`;
                                                                                //permet de mieux encodé dans l'URL, sinon cet URL permet de récupéré les valeurs du filtres
  try {
    const response = await fetch(url);
    if (!response.ok) throw new Error('Erreur réseau : ' + response.status);

    const data = await response.json();

    if (!data || data.length === 0) {
      afficherResultats('Aucun résultat trouvé.');    //très probable, aucune correspondance dans la base de donnée
      return;
    }

    afficherResultats(data);    //sinon on appelle la fonction d'affichage 
    //afficherResultatsDetails(data);
  } catch (error) {
    console.error('Erreur lors du chargement des résultats :', error);
    afficherResultats('Erreur lors du chargement des résultats.');
  }
}

function afficherResultats(data) {
  const container = document.getElementById('resultats');
  if (!container) return;

  if (typeof data === 'string') {
    container.textContent = data;
    return;
  }
  let i=0;
  for(let da of data){
  container.innerHTML += '<tr><th scope="row"></th><td>'+da.mois +' / '+ da.annee+'</td><td>'+da.nb_panneau+'</td><td>'+ da.surface+' m²</td><td>'+ da.puissance_crete+' W</td><td>'+ da.nom_pays+ ' - '+ da.nom_ville+'</td><td><a href="details.html?idListe=' + da.id + '">Détails</a></td></tr>'
  i++;
  }

}






















