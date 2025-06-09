document.addEventListener('DOMContentLoaded', init);

async function init() {
  await chargerDonnees();
}

async function chargerDonnees() {
  try {
    const response = await fetch('./request.php?action=donnees'); // <-- RESTE COMME ÇA
    if (!response.ok) throw new Error('Erreur réseau : ' + response.status);

    const data = await response.json();
    remplirDonnees(data.donnees); // <-- on extrait "donnees" directement ici

  } catch (error) {
    console.error('Erreur lors du chargement des données :', error);
  }
}

function remplirDonnees(donnees) {
  const tbody = document.getElementById('lignes_tableau');
  if (!tbody) return;

  tbody.innerHTML = ''; // vide le tableau au cas où

  donnees.forEach(ligne => {
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <th scope="row">${ligne.id}</th>
      <td>${ligne.annee}</td>
      <td>${ligne.mois}</td>
      <td>${ligne.departement}</td>
      <td>${ligne.code_insee}</td>
      <td>${ligne.nom_ville}</td>
      <td>${ligne.code_postal}</td>
      <td>${ligne.nom_pays}</td>
      <td>${ligne.region}</td>
      <td><a href="./details.html">Détails ></a></td>
    `;
    tbody.appendChild(tr);
  });
}