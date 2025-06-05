document.addEventListener('DOMContentLoaded', init);

async function init() {
  await chargerStats();
}


async function chargerStats() {
  try {
      const response = await fetch('back/request.php?action=stats');
      if (!response.ok) throw new Error('Erreur réseau : ' + response.status);

      const data = await response.json();

      remplirTotal(data.total, 'enregistrements_base');
      remplirInstallAns(data, 'installations_ans');
      remplirInstallRegion(data, 'installations_regions');
      remplirInstallAnsRegion(data, 'installations_ans_regions');
      remplirInstall(data.nbInstallateurs, 'installateurs');
      remplirOnduleur(data.nbOnduleurs, 'marques_onduleurs');
      remplirPanneaux(data.nbPanneaux, 'marques_panneaux');
    } catch (error) {
      console.error('Erreur lors du chargement des stats :', error);
  }
}

function remplirTotal(total, idSelect){
  const select = document.getElementById(idSelect);
  if (!select) return;

  select.innerHTML = '';

  const stat = document.createElement("div");
  stat.textContent = '→ Nombre d’enregistrements en base : ' + total;

  select.appendChild(stat);
}

function remplirInstallAns(data, idSelect) {
  const select = document.getElementById(idSelect);
  if (!select) return;

  const installAns = data.installAns;

  installAns.forEach(item => {
    const stat = document.createElement("div");
    stat.textContent = `* Année ${item.annee} : ${item.nbInstallAns} installations`;
    select.appendChild(stat);
  });
}


function remplirInstallRegion(data, idSelect) {
  const select = document.getElementById(idSelect);
  if (!select) return;

  const installRegion = data.installRegion;

  installRegion.forEach(item => {
    const stat = document.createElement("div");
    stat.textContent = `* Région ${item.region} : ${item.nbInstallRegion} installations`;
    select.appendChild(stat);
  });
}

function remplirInstallAnsRegion(data, idSelect) {
  const select = document.getElementById(idSelect);
  if (!select) return;

  const installAnsRegion = data.installAnsRegion;

  installAnsRegion.forEach(item => {
    const stat = document.createElement("div");
    stat.textContent = `* Année ${item.annee}, région ${item.region} : ${item.nbInstallAnsRegion} installations`;
    select.appendChild(stat);
  });
}

function remplirInstall(installateurs, idSelect){
  const select = document.getElementById(idSelect);
  if (!select) return;

  select.innerHTML = '';

  const stat = document.createElement("div");
  stat.textContent = '→ Nombre d’installateurs : ' + installateurs;

  select.appendChild(stat);
}

function remplirOnduleur(onduleurs, idSelect){
  const select = document.getElementById(idSelect);
  if (!select) return;

  select.innerHTML = '';

  const stat = document.createElement("div");
  stat.textContent = '→ Nombre de marques d’onduleurs : ' + onduleurs;

  select.appendChild(stat);
}

function remplirPanneaux(panneaux, idSelect){
  const select = document.getElementById(idSelect);
  if (!select) return;

  select.innerHTML = '';

  const stat = document.createElement("div");
  stat.textContent = '→ Nombre de marques de panneaux solaires : ' + panneaux;

  select.appendChild(stat);
}

window.addEventListener('DOMContentLoaded', chargerStats);