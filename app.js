document.addEventListener('DOMContentLoaded', () => {
  chargerTaches();
});

function chargerTaches() {
  fetch('api.php?action=list')
    .then(response => {
      if (!response.ok) {
        throw new Error('Erreur réseau ou authentification (' + response.status + ')');
      }
      return response.json();
    })
    .then(tasks => {
      console.log('Tâches reçues :', tasks);
      //remplir le DOM à partir des tâches reçues
    })
    .catch(error => {
      console.error('Erreur lors du chargement des tâches :', error);
    });
}