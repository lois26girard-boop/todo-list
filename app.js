document.addEventListener('DOMContentLoaded', () => {
  chargerTaches();
});

function chargerTaches() {
  const messageEl = document.getElementById('tasks-message');
  const listEl = document.getElementById('tasks-list');

  messageEl.textContent = 'Chargement des tâches...';
  listEl.innerHTML = '';

  fetch('api.php?action=list')
    .then(response => {
      if (!response.ok) {
        throw new Error('Erreur HTTP ' + response.status);
      }
      return response.json();
    })
    .then(tasks => {
      if (!Array.isArray(tasks) || tasks.length === 0) {
        messageEl.textContent = 'Aucune tâche pour le moment.';
        return;
      }

      messageEl.textContent = '';
      renderTasks(tasks);
    })
    .catch(error => {
      console.error(error);
      messageEl.textContent = 'Erreur lors du chargement des tâches.';
    });
}

function renderTasks(tasks) {
  const listEl = document.getElementById('tasks-list');
  listEl.innerHTML = '';

  tasks.forEach(task => {
    const li = document.createElement('li');
    li.classList.add('task-item');

    if (Number(task.is_done) === 1) {
      li.classList.add('task-done');
    }

    const titleEl = document.createElement('strong');
    titleEl.textContent = task.title;

    const metaEl = document.createElement('span');
    metaEl.classList.add('task-meta');
    metaEl.textContent = ` - ${task.description} — échéance : ${task.due_date ?? 'aucune'} — priorité : ${task.priority}`;

    li.appendChild(titleEl);
    li.appendChild(metaEl);

    listEl.appendChild(li);
  });
}