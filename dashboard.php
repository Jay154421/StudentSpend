<?php
require_once './Database/dbconfig.php';

if (!$user->is_loggedin()) {
  $user->redirect('index.php');
}

$user_id = $_SESSION['user_session'];
$name = $user->name($user_id);
$photo = $user->profilePhoto($user_id);
$totalBudgets = $budget->countAllBudgets($user_id);
$totalExpense = $expense->countAllExpense($user_id);

// Fetch expenses
$stmt = $DB_con->prepare("SELECT * FROM expense WHERE user_id = :user_id");
$stmt->execute(['user_id' => $user_id]);
$expenses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Prepare data
$dailyTotals = [];
$weeklyTotals = [
  'Sunday' => 0,
  'Monday' => 0,
  'Tuesday' => 0,
  'Wednesday' => 0,
  'Thursday' => 0,
  'Friday' => 0,
  'Saturday' => 0
];

// Define months in order
$monthOrder = [
  'January',
  'February',
  'March',
  'April',
  'May',
  'June',
  'July',
  'August',
  'September',
  'October',
  'November',
  'December'
];
$monthlyTotals = array_fill_keys($monthOrder, 0);

// Process each expense
foreach ($expenses as $expense) {
  $date = $expense['date'];
  $amount = $expense['amount'];

  // Weekly (Monday, Tuesday, ...)
  $weekday = date('l', strtotime($date));
  $weeklyTotals[$weekday] += $amount;

  // Monthly (January, February, ...)
  $month = date('F', strtotime($date));
  if (isset($monthlyTotals[$month])) {
    $monthlyTotals[$month] += $amount;
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/dashboard.css" />
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <title>Dashboard</title>
</head>

<body>
  <nav>
    <div class="left-nav">
      <div class="profile">
        <div>
          <?php if ($photo) { ?>
            <img class="nav-image" class="photo" src="./image/<?= $photo['photo'] ?>">
          <?php } else { ?>
            <img src="asset/profile.png" alt="" />
          <?php } ?>
        </div>
        <div><?= $name['username'] ?></div>
      </div>
      <div class="nav-list">
        <a class="active" href="dashboard.php">Dashboard</a>
        <a href="custombudget.php">Custom Budget</a>
        <a href="setting.php">Setting</a>
      </div>
      <a href="components/logout.php">Log Out</a>
    </div>
  </nav>

  <div class="main">
    <h3>Dashboard</h3>
    <div class="container">
      <div class="card-budget">
        <div>Number of Budget</div>
        <div style="font-weight: 600;"> <?= $totalBudgets ?></div>
      </div>
      <div class="card-expense">
        <div>Number of Expense</div>
        <div style="font-weight: 600;"><?= $totalExpense ?></div>
      </div>
    </div>

    <!-- View mode dropdown -->
    <div class="view-mode-select">
      <label for="viewMode">View Mode:</label>
      <select id="viewMode">
        <option value="weekly">Weekly</option>
        <option value="monthly">Monthly</option>
      </select>
    </div>

    <!-- Chart container -->
    <div class="chart-container">
      <canvas id="expenseChart"></canvas>
    </div>
  </div>

  <!-- Chart.js Data Script -->
  <script>
    const chartData = {
      weekly: {
        labels: <?= json_encode(array_keys($weeklyTotals)) ?>,
        data: <?= json_encode(array_values($weeklyTotals)) ?>
      },
      monthly: {
        labels: <?= json_encode(array_keys($monthlyTotals)) ?>,
        data: <?= json_encode(array_values($monthlyTotals)) ?>
      }
    };

    const ctx = document.getElementById('expenseChart').getContext('2d');

    let currentChart = new Chart(ctx, {
      type: 'bar',
      data: {
        labels: chartData.weekly.labels,
        datasets: [{
          label: 'Expense Amount',
          data: chartData.weekly.data,
          backgroundColor: 'rgba(75, 192, 192, 0.2)',
          borderColor: 'rgba(75, 192, 192, 1)',
          borderWidth: 1
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });

    // Change view mode dynamically
    document.getElementById('viewMode').addEventListener('change', function() {
      const mode = this.value;
      currentChart.data.labels = chartData[mode].labels;
      currentChart.data.datasets[0].data = chartData[mode].data;
      currentChart.update();
    });
  </script>
</body>

</html>
</div>
</body>

</html>