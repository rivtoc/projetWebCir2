
document.addEventListener('DOMContentLoaded', async () => {
  const params = new URLSearchParams(window.location.search);
  const idListe = params.get('idListe');

  if (!idListe) {
    document.getElementById('laCarte').textContent = "ID manquant dans l'URL.";
    return;
  }

  try {
    const response = await fetch(`back/request.php?action=oneResult&id=${encodeURIComponent(idListe)}`);
    if (!response.ok) throw new Error('Erreur réseau : ' + response.status);

    const data = await response.json();
    afficherResultatDetailUnique(data);
  } catch (error) {
    console.error('Erreur lors du chargement des détails :', error);
    document.getElementById('laCarte').textContent = "Erreur de chargement des détails.";
  }
});



function afficherResultatDetailUnique(data) {
  const container = document.getElementById('laCarte');
  if (!container) return;

  if (!data || typeof data !== 'object') {
    container.textContent = "Aucune donnée trouvée.";
    return;
  }

  container.innerHTML = `
  <div class="card shadow-lg mx-auto mt-4" style="max-width: 800px;">
    <div class="card-body">
      <h2 class="card-title text-primary mb-3">${data.nom_pays} - ${data.nom_ville}</h2>
      <h5 class="card-subtitle text-muted mb-4">Installé en ${data.mois} / ${data.annee}</h5>

      <div class="row">
        <div class="col-md-6 mb-3">
          <strong>Surface :</strong> ${data.surface} m²<br>
          <strong>Puissance :</strong> ${data.puissance_crete} kWc<br>
          <strong>Nombre de panneaux :</strong> ${data.nb_panneau}
        </div>
        <div class="col-md-6 mb-3">
          <strong>Latitude :</strong> ${data.latitude}<br>
          <strong>Longitude :</strong> ${data.longitude}<br>
          <strong>Orientation :</strong> ${data.orientation}
        </div>
      </div>

      <hr>

      <p class="mb-0">
        <strong>Installateur :</strong> ${data.installateur}<br>
        <strong>Onduleur :</strong> ${data.marque_onduleur} - ${data.modele_onduleur}<br>
        <strong>Panneau :</strong> ${data.marque_panneau} - ${data.modele_panneau}
      </p>
    </div>
  </div>
  `;
}
