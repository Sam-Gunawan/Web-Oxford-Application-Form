<?php
//TODO: temporary data for now. take real data from database later
$user = [
  'id' => 69,
  'name' => 'Cool Guy',
  'role' => 'admin'
];

$applications = [
  [
      'id' => 1,
      'name' => 'John Doe',
      'date' => '2025-04-10',
      'reviewers' => ['Alice Wonderland', 'Brian Griffin']
  ],
  [
      'id' => 2,
      'name' => 'Jane Smith',
      'date' => '2025-04-11',
      'reviewers' => ['Charlie Puth']
  ],
  [
      'id' => 3,
      'name' => 'Bob Builder',
      'date' => '2025-04-12',
      'reviewers' => ['Dana White', 'Evan Aditya', 'Fiona Shrek']
  ]
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Grid Layout Page</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">

  <link rel="stylesheet" href="../../assets/css/adminView.css">
</head>
<body>

  <div class="parent-container bg-white">

    <!-- nav section -->
    <div class="d-flex flex-column grid-nav bg-primary text-white d-flex align-items-center justify-content-center"> <!-- the nav container -->
        <div class="bg-white p-3 d-flex rounded align-items-center justify-content-center" style="max-height: 33.3%; width: 100%;">
          <img src="../../assets/images/Oxford-University-Circlet.svg.png" alt="Oxford Logo" style="max-height: 100%; max-width: 100%; object-fit: contain;">
        </div>
        <div class="mt-4 nav-content d-flex flex-column flex-grow-1 pt-4 gap-2 text-white w-100">
            <span class="nav-item border-bottom p-2">Application List</span>
            <span class="nav-item border-bottom p-2">Reviewer List</span>
            <span class="nav-item border-bottom p-2">Statistic</span>
        </div>
    </div>

    <!-- title section -->
    <div class="grid-title text-dark d-flex align-items-center justify-content-between">
      <div class="title-text display-3" style="color: black">Welcome <?= htmlspecialchars($user['name']) ?></div>
      <div class="top-nav h-50 d-flex align-items-center gap-3">
        <i class="fa-regular fa-bell fa-3x" style="color: black"></i>
        <i class="fa-regular fa-circle-user fa-3x" style="color: black"></i>
      </div>
    </div>

    <!-- content section  -->
    <div class="grid-content bg-secondary d-flex flex-column align-items-star gap-2 overflow-auto"> <!-- content container -->
      <?php foreach ($applications as $app): ?>
        <?php $collapseId = "application" . $app['id']; ?>
        <!-- the card -->
        <div class="card mb-2 shadow-sm rounded">
          <div class="content-item  bg-white p-4 d-flex align-items-center justify-content-between w-100">

            <div class="d-flex align-items-center gap-3">
              <i class="fa-regular fa-circle-user fa-2x"></i>
              <span><?= htmlspecialchars($app['name']) ?></span>
            </div>

            <div class="d-flex align-items-center gap-2">
              <button class="btn bg-primary text-white">Assign</button>
              <button class="btn bg-error text-white">Delete</button>

              <button class="btn toggle-btn" data-bs-toggle="collapse" data-bs-target="#<?= $collapseId ?>">
                <i class="fa fa-caret-down"></i> 
              </button>
            </div>
          </div>


          <!-- collapsable dropdown section -->
          <div class="collapse bg-secondary" id="<?= $collapseId ?>">
            <div class="card-body border-top d-flex justify-content-end px-5">
              <!-- Application Info -->
              <div class="content-info d-flex flex-column gap-2">
                <strong>Application Info</strong>
                <div><?= htmlspecialchars($app['date']) ?></div>
              </div>
              <!-- Reviewer Info -->
              <div class="content-info d-flex flex-column gap-2">
                <strong>Reviewer</strong>
                <?php foreach ($app['reviewers'] as $reviewer): ?>
                  <div><?= htmlspecialchars($reviewer) ?></div>
                <?php endforeach; ?>
              </div>
            </div>
          </div>


        </div>
      <?php endforeach; ?>
      
    </div>

  </div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/adminView.js"></script>

</html>
