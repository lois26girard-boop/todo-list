let allTasks = [];

document.addEventListener('DOMContentLoaded', () => {
    const statusSelect = document.getElementById('filter-status');
    const prioritySelect = document.getElementById('filter-priority');
    const sortSelect = document.getElementById('sort-by');
    const listEl = document.getElementById('tasks-list');
    const deleteDoneBtn = document.getElementById('delete-done-btn');
    const taskForm = document.getElementById('task-form');
    const openModalBtn = document.getElementById('open-task-modal');
    const closeModalBtn = document.getElementById('close-task-modal');
    const taskModal = document.getElementById('task-modal');


    statusSelect.addEventListener('change', applyFiltersAndSort);
    prioritySelect.addEventListener('change', applyFiltersAndSort);
    sortSelect.addEventListener('change', applyFiltersAndSort);

    listEl.addEventListener('change', event => {
        if (event.target.classList.contains('task-toggle')) {
            const li = event.target.closest('.task-item');
            const taskId = li.dataset.taskId;
            toggleTaskDone(taskId, event.target.checked);
        }
    });

    deleteDoneBtn.addEventListener('click', deleteDoneTasks);


        //ouvrir/fermer le modal
    openModalBtn.addEventListener('click', () => {
        taskModal.classList.add('open');
        taskModal.setAttribute('aria-hidden', 'false');
        document.getElementById('task-title').focus();
    });

    closeModalBtn.addEventListener('click', closeTaskModal);

    taskModal.addEventListener('click', event => {
        if (event.target === taskModal) {
            closeTaskModal();
        }
    });

    taskForm.addEventListener('submit', handleTaskFormSubmit);
    
    chargerTaches();

});

function closeTaskModal() {
    const taskModal = document.getElementById('task-modal');
    taskModal.classList.remove('open');
    taskModal.setAttribute('aria-hidden', 'true');
}


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
                allTasks = [];
                renderTasks([]);
                return;
            }

            messageEl.textContent = '';
            allTasks = tasks;
            applyFiltersAndSort();
        })
        .catch(error => {
            console.error(error);
            messageEl.textContent = 'Erreur lors du chargement des tâches.';
            allTasks = [];
            renderTasks([]);
        });
}

function applyFiltersAndSort() {
    const status = document.getElementById('filter-status').value;
    const priority = document.getElementById('filter-priority').value;
    const sortBy = document.getElementById('sort-by').value;

    let tasks = [...allTasks];

    tasks = tasks.filter(task => {
        const done = Number(task.is_done) === 1;

        if (status === 'active') {
            return !done;
        }
        if (status === 'done') {
            return done;
        }
        return true;
    });

    if (priority !== 'all') {
        tasks = tasks.filter(task => task.priority === priority);
    }

    tasks.sort((a, b) => compareTasks(a, b, sortBy));

    renderTasks(tasks);
}

function compareTasks(a, b, sortBy) {
    if (sortBy === 'created_at') {
        const d1 = new Date(a.created_at);
        const d2 = new Date(b.created_at);
        return d1 - d2;
    }

    if (sortBy === 'due_date') {
        if (!a.due_date && !b.due_date) return 0;
        if (!a.due_date) return 1;
        if (!b.due_date) return -1;

        const d1 = new Date(a.due_date);
        const d2 = new Date(b.due_date);
        return d1 - d2;
    }

    if (sortBy === 'priority') {
        const order = { high: 1, normal: 2, low: 3 };
        return (order[a.priority] || 99) - (order[b.priority] || 99);
    }

    return 0;
}

function renderTasks(tasks) {
    const listEl = document.getElementById('tasks-list');
    listEl.innerHTML = '';

    const today = new Date();
    today.setHours(0, 0, 0, 0);

    tasks.forEach(task => {
        const li = document.createElement('li');
        li.classList.add('task-item');
        li.dataset.taskId = task.id;

        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.classList.add('task-toggle');
        checkbox.checked = Number(task.is_done) === 1;

        if (checkbox.checked) {
            li.classList.add('task-done');
        }

        if (task.due_date && Number(task.is_done) === 0) {
            const dueDate = new Date(task.due_date);
            dueDate.setHours(0, 0, 0, 0);
            if (dueDate < today) {
                li.classList.add('task-late');
            }
        }

        const titleEl = document.createElement('strong');
        titleEl.textContent = task.title;

        const metaEl = document.createElement('span');
        metaEl.classList.add('task-meta');
        metaEl.textContent = ` - échéance : ${task.due_date ?? 'aucune'} - priorité : ${task.priority}`;

        li.appendChild(checkbox);
        li.appendChild(titleEl);
        li.appendChild(metaEl);

        listEl.appendChild(li);
    });
}

function toggleTaskDone(taskId, newCheckedValue) {
    fetch('api.php?action=toggle_done', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ id: taskId })
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur HTTP ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (!data.success) {
                throw new Error('Réponse API invalide');
            }

            const task = allTasks.find(t => String(t.id) === String(taskId));
            if (task) {
                task.is_done = data.is_done;
            }

            applyFiltersAndSort();
        })
        .catch(error => {
            console.error(error);
            const li = document.querySelector(`.task-item[data-task-id="${taskId}"]`);
            if (li) {
                const checkbox = li.querySelector('.task-toggle');
                if (checkbox) {
                    checkbox.checked = !newCheckedValue;
                }
            }
            alert('Erreur lors de la mise à jour de la tâche.');
        });
}

function deleteDoneTasks() {
    if (!confirm('Supprimer toutes les tâches terminées visibles pour cet utilisateur ?')) {
        return;
    }

    fetch('api.php?action=delete_done', {
        method: 'POST'
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur HTTP ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            allTasks = allTasks.filter(task => Number(task.is_done) === 0);
            applyFiltersAndSort();
        })
        .catch(error => {
            console.error(error);
            alert('Erreur lors de la suppression des tâches terminées.');
        });
}

function handleTaskFormSubmit(event) {
    event.preventDefault();

    const titleInput = document.getElementById('task-title');
    const descInput = document.getElementById('task-description');
    const dueInput = document.getElementById('task-due-date');
    const prioritySelect = document.getElementById('task-priority');

    const payload = {
        title: titleInput.value.trim(),
        description: descInput.value.trim(),
        due_date: dueInput.value,
        priority: prioritySelect.value
    };

    if (!payload.title) {
        alert('Le titre est obligatoire.');
        return;
    }

    fetch('api.php?action=create', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(payload)
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur HTTP ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (!data.success || !data.task) {
                throw new Error('Réponse API invalide');
            }

            allTasks.push(data.task);
            applyFiltersAndSort();

            titleInput.value = '';
            descInput.value = '';
            dueInput.value = '';
            prioritySelect.value = 'normal';

            closeTaskModal();
        })
        .catch(error => {
            console.error(error);
            alert('Erreur lors de la création de la tâche.');
        });
}