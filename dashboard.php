<?php
require_once './Database/dbconfig.php';

if (!$user->is_loggedin()) {
  $user->redirect('index.php');
}

$user_id = $_SESSION['user_session'];
$name = $user->name($user_id);
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/dashboard.css" />
  <title>Dashboard</title>
</head>

<body>
  <div style="display: flex; justify-content: space-between">
    <nav>
      <div class="left-nav">
        <div class="profile">
          <div><img src="asset/profile.png" alt="" /></div>
          <div><?= $name['username']?></div>
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
      <div></div>
    </div>
  </div>
</body>

</html>