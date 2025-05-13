<?php
	if (!defined("SERVER")) {
		http_response_code(403);
		die();
	}


//TODO: temporary data for now. take real data from database later
$users = [
    [
      'id' => 1,
      'name' => 'John Doe',
      'email' => 'johndoe@example.com',
      'password' => 'hashed_password_1',
      'role' => 'student'
    ],
    [
      'id' => 2,
      'name' => 'Alice Wonderland',
      'email' => 'alice@example.com',
      'password' => 'hashed_password_2',
      'role' => 'reviewer'
    ],
    [
      'id' => 3,
      'name' => 'Brian Griffin',
      'email' => 'brian@example.com',
      'password' => 'hashed_password_3',
      'role' => 'reviewer'
    ],
    [
      'id' => 4,
      'name' => 'Charlie Puth',
      'email' => 'charlie@example.com',
      'password' => 'hashed_password_4',
      'role' => 'student'
    ],
    [
      'id' => 5,
      'name' => 'Dana White',
      'email' => 'dana@example.com',
      'password' => 'hashed_password_5',
      'role' => 'admin'
    ],
    [
      'id' => 6,
      'name' => 'Evan Aditya',
      'email' => 'evan@example.com',
      'password' => 'hashed_password_6',
      'role' => 'reviewer'
    ],
    [
      'id' => 7,
      'name' => 'Fiona Shrek',
      'email' => 'fiona@example.com',
      'password' => 'hashed_password_7',
      'role' => 'reviewer'
    ]
  ];
  

?>

<?php foreach ($users as $user): ?>
    <?php $collapseId = "users" . $user['id']; ?>
    <!-- the card -->
    <div class="card mb-2 shadow-sm rounded">
        <div class="content-item  bg-white p-4 d-flex align-items-center justify-content-between w-100">
            <div class="d-flex align-items-center gap-3">
                <i class="fa-regular fa-circle-user fa-2x"></i>
                <span><?= htmlspecialchars($user['name']) ?></span>
            </div>

            <div class="d-flex align-items-center gap-2">
                <button class="btn toggle-btn" data-bs-toggle="collapse" data-bs-target="#<?= $collapseId ?>">
                    <i class="fa fa-caret-down"></i> 
                </button>
            </div>
        </div>


        <!-- collapsable dropdown section -->
        <div class="collapse bg-secondary" id="<?= $collapseId ?>">
            <div class="card-body border-top d-flex justify-content-end px-5">
                <!-- label -->
                <div class="content-info d-flex flex-column gap-2">
                    <strong>Label</strong>
                    <div class="fw-bold">id</div>
                    <div class="fw-bold">name</div>
                    <div class="fw-bold">email</div>
                    <div class="fw-bold">password</div>
                    <div class="fw-bold">role</div>
                </div>
                <!-- User Info -->
                <div class="content-info d-flex flex-column gap-2">
                    <strong>User Info</strong>
                    <div><?= htmlspecialchars($user['id']) ?></div>
                    <div><?= htmlspecialchars($user['name']) ?></div>
                    <div><?= htmlspecialchars($user['email']) ?></div>
                    <div><?= htmlspecialchars($user['password']) ?></div>
                    <div><?= htmlspecialchars($user['role']) ?></div>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3 w-100 justify-content-end px-5 py-3 border-top border-white">
                <button class="btn bg-primary text-white" style="width: 8%;">Edit</button>
                <button class="btn bg-error text-white" style="width: 8%;">Delete</button>
            </div>
        </div>

    </div>
<?php endforeach; ?>