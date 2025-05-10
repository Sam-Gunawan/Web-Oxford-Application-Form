<?php
$users = [
    ['id' => 1, 'name' => 'John Doe', 'email' => 'johndoe@example.com', 'password' => 'hashed_password_1', 'role' => 'student'],
    ['id' => 2, 'name' => 'Alice Wonderland', 'email' => 'alice@example.com', 'password' => 'hashed_password_2', 'role' => 'reviewer'],
    ['id' => 3, 'name' => 'Brian Griffin', 'email' => 'brian@example.com', 'password' => 'hashed_password_3', 'role' => 'reviewer'],
    ['id' => 4, 'name' => 'Charlie Puth', 'email' => 'charlie@example.com', 'password' => 'hashed_password_4', 'role' => 'student'],
    ['id' => 5, 'name' => 'Dana White', 'email' => 'dana@example.com', 'password' => 'hashed_password_5', 'role' => 'admin'],
    ['id' => 6, 'name' => 'Evan Aditya', 'email' => 'evan@example.com', 'password' => 'hashed_password_6', 'role' => 'reviewer'],
    ['id' => 7, 'name' => 'Fiona Shrek', 'email' => 'fiona@example.com', 'password' => 'hashed_password_7', 'role' => 'reviewer']
];

$applications = [
    ['id' => 1, 'name' => 'John Doe', 'date' => '2025-04-10', 'programme' => 'Master of Nursing', 'status' => 'waitlist', 'reviewers' => ['Alice Wonderland']],
    ['id' => 2, 'name' => 'Jane Smith', 'date' => '2025-04-11', 'programme' => 'Master of Computing', 'status' => 'approved', 'reviewers' => ['Charlie Puth']],
    ['id' => 3, 'name' => 'Bob Builder', 'date' => '2025-04-12', 'programme' => 'Doctor of Philosophy', 'status' => 'rejected', 'reviewers' => ['Dana White', 'Evan Aditya', 'Fiona Shrek']]
];

$roleCounts = array_count_values(array_column($users, 'role'));
$statusCounts = array_count_values(array_column($applications, 'status'));

$reviewers = array_filter($users, fn($u) => $u['role'] === 'reviewer');
$allReviewerNames = array_column($reviewers, 'name');
$assignedReviewers = [];

foreach ($applications as $app) {
    $assignedReviewers = array_merge($assignedReviewers, $app['reviewers']);
}
$assignedReviewers = array_unique($assignedReviewers);
$idleReviewers = array_diff($allReviewerNames, $assignedReviewers);

$reviewerStats = [
    'Assigned' => count($assignedReviewers),
    'Idle' => count($idleReviewers)
];

$reviewerAssignments = [];

foreach ($reviewers as $rev) {
    $reviewerAssignments[$rev['name']] = [];
}

foreach ($applications as $app) {
    foreach ($app['reviewers'] as $revName) {
        $reviewerAssignments[$revName][] = $app['name']; // applicant name
    }
}

?>
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
                <?php foreach ($reviewerAssignments as $reviewer => $apps): ?>
                    <tr>
                        <td><?= htmlspecialchars($reviewer) ?></td>
                        <td>
                            <?php if (!empty($apps)): ?>
                                <ul class="mb-0">
                                    <?php foreach ($apps as $applicant): ?>
                                        <li><?= htmlspecialchars($applicant) ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <em>—</em>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= empty($apps) ? '<span class="badge bg-secondary">Idle</span>' : '<span class="badge bg-success">Assigned</span>' ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>


<script>
const roleData = <?= json_encode($roleCounts) ?>;
const statusData = <?= json_encode($statusCounts) ?>;
const reviewerData = <?= json_encode($reviewerStats) ?>;

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
</script>

