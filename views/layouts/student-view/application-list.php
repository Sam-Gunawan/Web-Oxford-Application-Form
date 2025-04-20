
<?php
require_once(__DIR__ . "/../../../include/auth.php");
forbid();

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
      'name' => 'John Doe',
      'date' => '2025-04-11',
      'programme' => 'Master of Computing',
      'status' => 'waitlist',
      'reviewers' => ['Charlie Puth']
  ],
  [
      'id' => 3,
      'name' => 'John Doe',
      'date' => '2025-04-12',
      'programme' => 'Doctor of Philosophy',
      'status' => 'waitlist',
      'reviewers' => ['Dana White', 'Evan Aditya', 'Fiona Shrek']
  ]
];

?>

<div class="h-100 w-100 d-flex flex-column px-4 pt-4 justify-content-evenly">
    <div class="bg-white rounded w-100 p-5 overflow-auto" style="height: 85%;">
        <table border="1" cellspacing="0" cellpadding="8">
            <thead>
                <tr>
                <th>Date Created</th>
                <th>Application Number</th>
                <th>Programme</th>
                <th>Application Status</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($applications as $app): ?>
                    <tr>
                        <td><?= $app['date'] ?></td>
                        <td><?= $app['id'] ?></td>
                        <td><?= $app['programme'] ?></td>
                        <td class="color-error"><?= $app['status'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="d-flex w-100 justify-content-end align-items-center" style="height: 15%;">
        <button class="btn bg-white color-primary fs-3" style="height: 50%; width: 25%;">Create Application</button>
    </div>
</div>
