'use strict';

function chargerMenus() {
  fetch('back/request.php?action=search')
    .then(response => {
      if (!response.ok) throw new Error('Erreur réseau : ' + response.status);
      return response.json();
    })
    .then(data => {
      remplirMenu(data.marquesOnduleur, 'menuOnduleur');
      remplirMenu(data.marquesPanneau, 'menuPanneau');
      remplirMenu(data.departements, 'menuDepartement');
    })
    .catch(error => {
      console.error('Erreur lors du chargement des menus :', error);
    });
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