let allTasks = [];

document.addEventListener('DOMContentLoaded', () => {
    const statusSelect = document.getElementById('filter-status');
    const prioritySelect = document.getElementById('filter-priority');
    const sortSelect = document.getElementById('sort-by');

    statusSelect.addEventListener('change', applyFiltersAndSort);
    prioritySelect.addEventListener('change', applyFiltersAndSort);
    sortSelect.addEventListener('change', applyFiltersAndSort);

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
        const order = { haute: 1, normale: 2, basse: 3 };
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

        if (Number(task.is_done) === 1) {
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

        li.appendChild(titleEl);
        li.appendChild(metaEl);

        listEl.appendChild(li);
    });
}