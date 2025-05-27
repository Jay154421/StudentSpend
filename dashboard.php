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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <title>Dashboard</title>
</head>

<body>
  <div class="app-container">
    <nav class="modern-nav">
      <div class="nav-header">
        <div class="profile">
          <?php if ($photo) { ?>
            <img class="nav-image" src="./image/<?= $photo['photo'] ?>">
          <?php } else { ?>
            <img class="nav-image" src="asset/profile.png" alt="Profile" />
          <?php } ?>
          <div class="profile-info">
            <h4><?= $name['username'] ?></h4>
            <span>Premium User</span>
          </div>
        </div>
        <button class="nav-toggle" aria-label="Toggle menu">
          <i class="fas fa-bars"></i>
        </button>
      </div>
      <div class="nav-list">
        <a href="dashboard.php" class="active">
          <i class="fas fa-chart-line"></i>
          <span>Dashboard</span>
        </a>
        <a href="custombudget.php">
          <i class="fas fa-wallet"></i>
          <span>Custom Budget</span>
        </a>
        <a href="setting.php">
          <i class="fas fa-cog"></i>
          <span>Settings</span>
        </a>
        <a href="components/logout.php" class="logout-btn">
          <i class="fas fa-sign-out-alt"></i>
          <span>Log Out</span>
        </a>
      </div>
    </nav>

    <main class="main-content">
      <div class="header">
        <h1>Dashboard</h1>
        <div class="date-display">
          <span><?= date('F j, Y') ?></span>
        </div>
      </div>

      <div class="stats-container">
        <div class="stat-card budget-card">
          <div class="stat-icon">
            <i class="fas fa-piggy-bank"></i>
          </div>
          <div class="stat-info">
            <h3>Number of Budgets</h3>
            <p><?= $totalBudgets ?></p>
          </div>
        </div>
        <div class="stat-card expense-card">
          <div class="stat-icon">
            <i class="fas fa-receipt"></i>
          </div>
          <div class="stat-info">
            <h3>Number of Expenses</h3>
            <p><?= $totalExpense ?></p>
          </div>
        </div>
      </div>

      <div class="chart-section">
        <div class="chart-header">
          <h2>Expense Analytics</h2>
          <div class="view-mode-select">
            <div class="custom-select">
              <select id="viewMode">
                <option value="weekly">Weekly</option>
                <option value="monthly">Monthly</option>
              </select>
              <div class="select-arrow">
                <i class="fas fa-chevron-down"></i>
              </div>
            </div>
          </div>
        </div>
        <div class="chart-container">
          <canvas id="expenseChart"></canvas>
        </div>
      </div>
    </main>
  </div>

  <script>
    // Toggle mobile menu
    const navToggle = document.querySelector('.nav-toggle');
    const navList = document.querySelector('.nav-list');

    navToggle.addEventListener('click', () => {
      navList.classList.toggle('show');
    });

    // Chart data and initialization remains the same
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
          backgroundColor: 'rgba(6, 171, 153, 0.6)',
          borderColor: 'rgba(6, 171, 153, 1)',
          borderWidth: 1,
          borderRadius: 6,
          borderSkipped: false,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          },
          tooltip: {
            backgroundColor: '#fff',
            titleColor: '#333',
            bodyColor: '#666',
            borderColor: '#eee',
            borderWidth: 1,
            padding: 12,
            usePointStyle: true,
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
              color: 'rgba(200, 238, 234, 0.3)'
            }
          },
          x: {
            grid: {
              display: false
            }
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