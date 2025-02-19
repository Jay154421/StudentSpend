<?php
require_once './Database/dbconfig.php';

if (!$user->is_loggedin()) {
  $user->redirect('index.php');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/custmonbudget.css" />
  <title>Custom Budget</title>
</head>

<body>
  <div style="display: flex; justify-content: space-between">
    <!-- left sidebar -->
    <nav>
      <div class="left-nav">
        <div class="profile">
          <div><img src="asset/profile.png" alt="" /></div>
          <div>Christian P. Forrest</div>
        </div>
        <div class="nav-list">
          <a href="dashboard.php">Dashboard</a>
          <a class="active" href="custombudget.php">Custom Budget</a>
          <a href="setting.php">Setting</a>
        </div>
        <a href="components/logout.php">Log Out</a>
      </div>
    </nav>