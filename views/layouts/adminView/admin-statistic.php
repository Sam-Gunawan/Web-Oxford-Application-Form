<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="container my-4">
    <div class="row row-cols-1 row-cols-md-3 g-4 justify-content-center">
        <div class="col d-flex">
            <div class="card d-flex flex-column w-100 bg-white">
                <h5 class="card-header text-center">User Role Distribution</h5>
                <div class="card-body flex-grow-1">
                    <canvas id="roleChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col d-flex">
            <div class="card d-flex flex-column w-100 bg-white ">
                <h5 class="card-header text-center">Application Status Distribution</h5>
                <div class="card-body flex-grow-1">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col d-flex">
            <div class="card d-flex flex-column w-100 bg-white">
                <h5 class="card-header text-center">Reviewer Load</h5>
                <div class="card-body flex-grow-1">
                    <canvas id="reviewerChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container my-4">
    <div class="table-responsive">
        <table class="bg-white">
            <thead>
                <tr>
                    <th>Reviewer Name</th>
                    <th>Assigned Applications</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <!-- Table data will be rendered here dynamically -->
            </tbody>
        </table>
    </div>
</div>

<script type="module">
import { getAllUsers, getAllApplications } from '../../controllers/admin-controller.js';

document.addEventListener('DOMContentLoaded', async function () {
    const users = await getAllUsers();
    const applications = await getAllApplications();

    // Count roles
    const roleData = users.reduce((acc, user) => {
        acc[user.role] = (acc[user.role] || 0) + 1;
        return acc;
    }, {});

    // Count application statuses
    const statusData = applications.reduce((acc, app) => {
        acc[app.status] = (acc[app.status] || 0) + 1;
        return acc;
    }, {});

    // Get reviewer names
    const reviewers = users.filter(u => u.role === 'reviewer');
    const allReviewerNames = reviewers.map(r => r.name);

    // Find assigned reviewers from applications
    let assignedReviewers = [];
    applications.forEach(app => {
        if (Array.isArray(app.reviewer_names)) {
            assignedReviewers.push(...app.reviewer_names);
        }
    });
    assignedReviewers = [...new Set(assignedReviewers)];

    // Reviewers who are not assigned to any application
    const idleReviewers = allReviewerNames.filter(name => !assignedReviewers.includes(name));

    const reviewerData = {
        Assigned: assignedReviewers.length,
        Idle: idleReviewers.length
    };

    // Map reviewer names to list of applicant names
    const reviewerAssignments = {};
    reviewers.forEach(rev => reviewerAssignments[rev.name] = []);

    applications.forEach(app => {
        if (Array.isArray(app.reviewer_names)) {
            app.reviewer_names.forEach(revName => {
                if (!reviewerAssignments[revName]) {
                    reviewerAssignments[revName] = [];
                }
                reviewerAssignments[revName].push(app.applicant_name || '(No name)');
            });
        }
    });

    console.log(roleData);
    console.log(statusData);

    // Render charts
    new Chart(document.getElementById('roleChart'), {
        type: 'pie',
        data: {
            labels: Object.keys(roleData),
            datasets: [{
                data: Object.values(roleData),
                backgroundColor: ['#4bc0c0', '#ff9f40', '#ff6384']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: { padding: 0 },
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    new Chart(document.getElementById('statusChart'), {
        type: 'bar',
        data: {
            labels: Object.keys(statusData),
            datasets: [{
                data: Object.values(statusData),
                backgroundColor: ['#36a2eb', '#ffcd56', '#ff6384']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: { padding: 0 },
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true, precision: 0 }
            }
        }
    });

    new Chart(document.getElementById('reviewerChart'), {
        type: 'doughnut',
        data: {
            labels: Object.keys(reviewerData),
            datasets: [{
                data: Object.values(reviewerData),
                backgroundColor: ['#9966ff', '#c9cbcf']
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            layout: { padding: 0 },
            plugins: {
                legend: { position: 'bottom' }
            }
        }
    });

    // Render the reviewer assignments table dynamically
    renderTable(reviewerAssignments);
});

function renderTable(assignments) {
    const tbody = document.querySelector('tbody');
    tbody.innerHTML = '';

    for (const [reviewer, apps] of Object.entries(assignments)) {
        const tr = document.createElement('tr');

        const nameTd = document.createElement('td');
        nameTd.textContent = reviewer;

        const appsTd = document.createElement('td');
        if (apps.length > 0) {
            const ul = document.createElement('ul');
            apps.forEach(app => {
                const li = document.createElement('li');
                li.textContent = app;
                ul.appendChild(li);
            });
            appsTd.appendChild(ul);
        } else {
            appsTd.innerHTML = '<em>—</em>';
        }

        const statusTd = document.createElement('td');
        statusTd.innerHTML = apps.length === 0
            ? '<span class="badge bg-secondary">Idle</span>'
            : '<span class="badge bg-success">Assigned</span>';

        tr.appendChild(nameTd);
        tr.appendChild(appsTd);
        tr.appendChild(statusTd);

        tbody.appendChild(tr);
    }
}
</script>
