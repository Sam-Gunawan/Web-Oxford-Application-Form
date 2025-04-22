<?php
	if (!defined("SERVER")) {
		http_response_code(403);
		die();
	}


//TODO: temporary data for now. take real data from database later
$applications = [
  [
      'id' => 1,
      'name' => 'John Doe',
      'date' => '2025-04-10',
      'programme' => 'Master of Nursing',
      'status' => 'waitlist',
      'reviewers' => ['Alice Wonderland', 'Brian Griffin']
  ],
  [
      'id' => 2,
      'name' => 'Jane Smith',
      'date' => '2025-04-11',
      'programme' => 'Master of Computing',
      'status' => 'waitlist',
      'reviewers' => ['Charlie Puth']
  ],
  [
      'id' => 3,
      'name' => 'Bob Builder',
      'date' => '2025-04-12',
      'programme' => 'Doctor of Philosophy',
      'status' => 'waitlist',
      'reviewers' => ['Dana White', 'Evan Aditya', 'Fiona Shrek']
  ]
];

$actions = [
	"Message" => [
		"classes" => "bg-primary text-white",
		"query" => "?action=message"
	], 
	"Approve" => [
		"classes" => "bg-primary text-white",
		"query" => "?action=approve"
	], 
	"Reject" => [
		"classes" => "bg-error text-white",	
		"query" => "?action=reject"
	]
];

?>

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
                    <div><?= htmlspecialchars($app['programme']) ?></div>
                    <div class="fw-bold"><?= htmlspecialchars($app['status']) ?></div>
                </div>
                <!-- Reviewer Info -->
                <div class="content-info d-flex flex-column gap-2">
                    <strong>Reviewer</strong>
                    <?php foreach ($app['reviewers'] as $reviewer): ?>
                        <div><?= htmlspecialchars($reviewer) ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3 w-100 justify-content-end px-5 py-3 border-top border-white">
				<?php 
					foreach ($actions as $action => $attrs) {
						echo "<a ". "href=" . (CLEAN_URI ? "/views/reply" :( WEBSITE_ROOT . REPLY_PAGE_URL)) . $attrs["query"] . " class=\"btn " . $attrs["classes"]. 
						"\" style=\"width: 8%; min-width: 16ch;\">" . $action . "</a>";
					}
				?>
            </div>
        </div>

    </div>
<?php endforeach; ?>