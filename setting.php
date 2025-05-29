<?php
require_once './Database/dbconfig.php';

// Process form submissions before any output
if (!$user->is_loggedin()) {
  $user->redirect('index.php');
  exit();
}

$user_id = $_SESSION['user_session'];
$name = $user->name($user_id);
$photo = $user->profilePhoto($user_id);

// Handle photo upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn-image'])) {
  $image = $_FILES["uploadfile"]["name"];
  $tempName = $_FILES["uploadfile"]["tmp_name"];
  $folder = "./image/" . $image;

  if (move_uploaded_file($tempName, $folder)) {
    if (!file_exists($image)) {
      $user->uploadPhoto($image, $user_id);
    }
    $user->redirect('setting.php');
    exit();
  }
}

// Handle password change
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn-password'])) {
  $old_password = $_POST['old_password'];
  $new_password = $_POST['new_password'];
  $confirm_password = $_POST['confirm_password'];

  if ($new_password !== $confirm_password) {
    $_SESSION['error'] = "Passwords you entered do not match";
  } else {
    $user->changePassword($old_password, $new_password);
    $_SESSION['success'] = "Password changed successfully";
    $user->redirect('setting.php');
    exit();
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Settings</title>
  <link rel="stylesheet" href="css/setting.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
  <div class="app-container">
    <!-- Modern Navigation -->
    <nav class="modern-nav">
      <div class="nav-header">
        <div class="profile">
          <?php if ($photo) { ?>
            <img class="nav-image" src="./image/<?= $photo['photo'] ?>">
          <?php } else { ?>
            <img class="nav-image" src="asset/profile.png" alt="Profile">
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
        <a href="dashboard.php">
          <i class="fas fa-chart-line"></i>
          <span>Dashboard</span>
        </a>
        <a href="custombudget.php">
          <i class="fas fa-wallet"></i>
          <span>Custom Budget</span>
        </a>
        <a class="active" href="setting.php">
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
        <h1>Settings</h1>
      </div>

      <!-- Profile Section -->
      <div class="profile-section">
        <div class="profile-header">
          <?php if ($photo) { ?>
            <img class="profile-photo" src="./image/<?= $photo['photo'] ?>" alt="Profile Photo">
          <?php } else { ?>
            <img class="profile-photo" src="asset/profile.png" alt="Profile Photo">
          <?php } ?>
          <div class="profile-actions">
            <button class="btn btn-primary modal-button" href="#myModal1">
              <i class="fas fa-camera"></i>
              Change Photo
            </button>
          </div>
        </div>

        <!-- Profile Info -->
        <div class="profile-info-section">
          <div class="info-item">
            <span class="info-label">Full Name</span>
            <span class="info-value"><?= $name['fullname'] ?></span>
          </div>
          <div class="info-item">
            <span class="info-label">Email</span>
            <span class="info-value"><?= $name['email'] ?></span>
          </div>
          <div class="info-item">
            <span class="info-label">Username</span>
            <span class="info-value"><?= $name['username'] ?></span>
          </div>
        </div>

        <div style="margin-top: 30px;">
          <button class="btn btn-secondary modal-button" href="#myModal2">
            <i class="fas fa-key"></i>
            Change Password
          </button>
        </div>
      </div>

      <!-- Change Photo Modal -->
      <div id="myModal1" class="modal">
        <div class="modal-content">
          <div class="modal-header">
            <h2>Change Profile Photo</h2>
            <button class="modal-close">&times;</button>
          </div>
          <div class="modal-body">
            <form method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label for="uploadfile">Select new photo</label>
                <input type="file" class="form-control" id="uploadfile" name="uploadfile" required>
              </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="btn-image" class="btn btn-primary">
              <i class="fas fa-save"></i> Save
            </button>
            <button type="button" class="btn btn-secondary close">
              <i class="fas fa-times"></i> Cancel
            </button>
            </form>
          </div>
        </div>
      </div>

      <!-- Change Password Modal -->
      <div id="myModal2" class="modal">
        <div class="modal-content">
          <div class="modal-header">
            <h2>Change Password</h2>
            <button class="modal-close">&times;</button>
          </div>
          <div class="modal-body">
            <form method="post">
              <div class="form-group">
                <label>Current Password</label>
                <input type="password" class="form-control" name="old_password" placeholder="Enter current password" required>
              </div>
              <div class="form-group">
                <label>New Password</label>
                <input type="password" class="form-control" name="new_password" placeholder="Enter new password" required>
              </div>
              <div class="form-group">
                <label>Confirm New Password</label>
                <input type="password" class="form-control" name="confirm_password" placeholder="Confirm new password" required>
              </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="btn-password" class="btn btn-primary">
              <i class="fas fa-save"></i> Save Changes
            </button>
            <button type="button" class="btn btn-secondary close">
              <i class="fas fa-times"></i> Cancel
            </button>
            </form>
          </div>
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

    // Modal functionality
    var btn = document.querySelectorAll("button.modal-button");
    var modals = document.querySelectorAll(".modal");
    var closeButtons = document.querySelectorAll(".modal-close, .close");

    btn.forEach(function(button) {
      button.onclick = function(e) {
        e.preventDefault();
        var modal = document.querySelector(button.getAttribute("href"));
        modal.classList.add("show");
      };
    });

    closeButtons.forEach(function(button) {
      button.onclick = function() {
        modals.forEach(function(modal) {
          modal.classList.remove("show");
        });
      };
    });

    window.onclick = function(event) {
      if (event.target.classList.contains("modal")) {
        modals.forEach(function(modal) {
          modal.classList.remove("show");
        });
      }
    };

    // Show error/success messages
    <?php if (isset($_SESSION['error'])): ?>
      Swal.fire({
        title: 'Error',
        text: '<?= $_SESSION['error'] ?>',
        icon: 'error',
        confirmButtonText: 'OK'
      });
      <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
      Swal.fire({
        title: 'Success',
        text: '<?= $_SESSION['success'] ?>',
        icon: 'success',
        confirmButtonText: 'OK'
      });
      <?php unset($_SESSION['success']); ?>
    <?php endif; ?>
  </script>
</body>

</html>