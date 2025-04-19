<?php
//TODO: temporary data for now. take real data from database later
$user = [
  'id' => 69,
  'name' => 'Cool Guy',
  'role' => 'admin'
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
        <div class="mt-4 nav-content d-flex flex-column flex-grow-1 pt-4 gap-2 w-100">
            <?php if ($user['role'] === 'admin'): ?>
                <a href="?page=applications" class="nav-item border-bottom p-2 text-decoration-none">Application List</a>
                <a href="?page=users" class="nav-item border-bottom p-2 text-decoration-none">User List</a>
                <a href="?page=statistics" class="nav-item border-bottom p-2 text-decoration-none">Statistic</a>
            <?php elseif ($user['role'] === 'reviewer'): ?>
                <a class="nav-item border-bottom p-2 text-decoration-none">Application List</a>
            <?php elseif ($user['role'] === 'student'): ?>
                <a class="nav-item border-bottom p-2 text-decoration-none">Application List</a>
                <a class="nav-item border-bottom p-2 text-decoration-none">Application Draft</a>
            <?php endif; ?>
        </div>
    </div>

    <!-- title section -->
    <div class="grid-title text-dark d-flex align-items-center justify-content-between">
      <div class="title-text display-3 color-primary">Welcome <?= htmlspecialchars($user['name']) ?></div>
      <div class="top-nav h-50 d-flex align-items-center gap-3">
        <i class="fa-regular fa-bell fa-3x color-primary"></i>
        <i class="fa-regular fa-circle-user fa-3x color-primary"></i>
      </div>
    </div>

    <!-- content section  -->
    <div class="grid-content bg-secondary d-flex flex-column align-items-star gap-2 overflow-auto"> <!-- content container -->
        <?php
            switch ($user['role']) {
                case 'admin':
                    $page = $_GET['page'] ?? 'applications';
                    switch ($page) {
                        case 'applications':
                            include('admin-view/application-list.php');
                            break;
                        case 'users':
                            include('admin-view/user-list.php');
                            break;
                        case 'statistics':
                            include('admin-view/admin-statistic.php');
                            break;
                        default:
                            echo "<div class='p-4'>Admin page not found.</div>";
                    }
                    break;

                case 'reviewer':
                    include('reviewer-view/application-list.php');
                    break;

                case 'student':
                    include('student-view/application-list.php');
                    break;

                default:
                    echo "Role not recognized.";
            }
        ?>
      
    </div>

  </div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../../assets/js/adminView.js"></script>

</html>
