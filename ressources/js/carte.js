
document.addEventListener('DOMContentLoaded', init);

async function init() {
  await chargerMenus();
  ajouterEcouteursPoints();
}

async function chargerMenus() {
  const request = 'back/request.php?action=carte';

  try {
    const response = await fetch(request);
    if (!response.ok) {
      console.error('Erreur réseau :', response.status);
      return;
    }

    const data = await response.json();

    if (!data.annee || !data.departements) {
      console.error('Données manquantes dans la réponse API :', data);
      return;
    }

    remplirMenu(data.annee, 'annee_installation');
    remplirMenu(data.departements, 'departement');

  } catch (error) {
    console.error('Erreur lors du chargement des menus :', error);
  }
}


function remplirMenu(liste, idSelect) {
  const select = document.getElementById(idSelect);
  if (!select) return;

  select.innerHTML = '';

  const optionDefault = document.createElement('option');
  optionDefault.value = '';
  optionDefault.textContent = 'Sélectionnez...';
  select.appendChild(optionDefault);

  liste.forEach(item => {
    const valeur = Object.values(item)[0];
    const option = document.createElement('option');
    option.value = valeur;
    option.textContent = valeur;
    select.appendChild(option);
  });
}

window.addEventListener('DOMContentLoaded', chargerMenus);

function ajouterEcouteursPoints() {
  const menus = ['annee_installation', 'departement']; 
  menus.forEach(id => {           
    const select = document.getElementById(id);
    if (select) {
      select.addEventListener('change', onSelectChangePoints); 
    } 
  });
}

async function onSelectChangePoints() {
  const annee = document.getElementById('annee_installation').value;
  const departement = document.getElementById('departement').value;

  if (!annee || !departement) {
    afficherResultatsPoints('Veuillez sélectionner toutes les options.');     //S'execute du moment qu'on a commencé à remplir le formulaire, et que l'on a pas fini
    return;
  }

  await chargerResultatsFiltresPoints(annee, departement);  //si c'est fini, alors on charge les résultats;
}


async function chargerResultatsFiltresPoints(annee, departement) {
  const url = `back/request.php?action=filteredResultsPoint&annee=${encodeURIComponent(annee)}&departement=${encodeURIComponent(departement)}`;
                                                                                //permet de mieux encodé dans l'URL, sinon cet URL permet de récupéré les valeurs du filtres
  try {
    const response = await fetch(url);
    if (!response.ok) throw new Error('Erreur réseau : ' + response.status);

    const data = await response.json();
    console.log(data);
    if (!data || data.length === 0) {
      afficherResultatsPoints('Aucun résultat trouvé.');    //très probable, aucune correspondance dans la base de donnée
      return;
    }

    afficherResultatsPoints(data);    //sinon on appelle la fonction d'affichage 
  } catch (error) {
    console.error('Erreur lors du chargement des résultats :', error);
    afficherResultatsPoints('Erreur lors du chargement des résultats.');
  }
}

function afficherResultatsPoints(data) {
      const container= document.getElementById('resultats')

      container.innerHTML = data;
      map.setLatLng([data[0].latitude, data[0].longitude]);

}