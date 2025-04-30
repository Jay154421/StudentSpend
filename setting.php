<!-- <?php include_once('layout/header_setting.php') ?> -->

<?php
require_once './Database/dbconfig.php';

if (!$user->is_loggedin()) {
  $user->redirect('index.php');
}

$user_id = $_SESSION['user_session'];
$name = $user->name($user_id);
$photo = $user->profilePhoto($user_id);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/setting.css" />
  <title>Setting</title>
</head>

<body>
  <div style="display: flex; justify-content: space-between">
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
          <a href="dashboard.php">Dashboard</a>
          <a href="custombudget.php">Custom Budget</a>
          <a class="active" href="setting.php">Setting</a>
        </div>
        <a href="components/logout.php">Log Out</a>
      </div>
    </nav>


    <div class="main">
      <h3>Setting</h3>

      <div class="setting-profile">
        <div>
          <?php if ($photo) { ?>
            <img class="photo" src="./image/<?= $photo['photo'] ?>" alt="<?= $photo['photo'] ?>">
          <?php } else { ?>
            <img src="asset/profile.png" alt="" />
          <?php } ?>
        </div>
        <div class="btn-change_photo">
          <button class="modal-button" href="#myModal1">Upload Photo</button>
          <?php
          if (isset($_POST['btn-image'])) {
            $image = $_FILES["uploadfile"]["name"];
            $tempName = $_FILES["uploadfile"]["tmp_name"];
            $folder = "./image/" . $image;
            move_uploaded_file($tempName, $folder);

            if (!file_exists($image)) {
              $user->changePhoto($image, $user_id);
              $user->redirect('setting.php');
            } else {
              $user->uploadPhoto($image, $user_id);
              $user->redirect('setting.php');
            }
          }
          ?>


          <!-- Change Photo Modal -->
          <div id="myModal1" class="modal">
            <!-- Modal content -->
            <div class="modal-content">
              <div class="modal-header">
                <h2>Upload Photo</h2>
              </div>
              <hr />
              <div class="modal-body">
                <form method="post" action="" enctype="multipart/form-data">
                  <div class="form-group">
                    <input class="form-control" type="file" name="uploadfile" value="" />
                  </div>
              </div>
              <hr />
              <div class="modal-footer">
                <div class="footer-btn">
                  <button class="btn-submit" type="submit" name="btn-image">Save</button>
                  </form>
                  <button class="btn-cancel close">Cancel</button>
                </div>
              </div>
            </div>
          </div>
          <!-- End modal -->
        </div>
      </div>
      <div class="text">
        <div>Full Name</div>
        <div class="sub-text"><?= $name['fullname'] ?></div>
      </div>
      <hr />
      <div class="text">
        <div>Email</div>
        <div class="sub-text"><?= $name['email'] ?></div>
      </div>
      <hr />
      <div class="text">
        <div>Username</div>
        <div class="sub-text"><?= $name['username'] ?></div>
      </div>
      <hr />

      <button class="btn-change_password modal-button" href="#myModal2">Change Password</button>


      <?php
      require_once './Database/dbconfig.php';

      if (isset($_POST['btn-password'])) {
        $old_password = $_POST['old_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];

        if ($new_password !== $confirm_password) {
          echo "<p style='color:red;'>Passwords you entered do not match</p>";
        } else {
          $user->changePassword($old_password, $new_password);
        }
      }


      ?>


      <!-- Change Password Modal -->
      <div id="myModal2" class="modal">
        <!-- Modal content -->
        <div class="modal-content">
          <div class="modal-header">
            <h2>Change Password</h2>
          </div>
          <hr />
          <div class="modal-body">
            <form action="" method="post">
              <div class="budget-align">
                <input type="text" name="old_password" placeholder="Old Password" />
                <input type="text" name="new_password" placeholder="New Password" />
                <input type="text" name="confirm_password" placeholder="Confirm Password" />
              </div>
          </div>
          <hr />
          <div class="modal-footer">
            <div class="footer-btn">
              <button class="btn-submit" name="btn-password">Save</button>
              </form>
              <button class="btn-cancel close">Cancel</button>
            </div>
          </div>

        </div>
      </div>
    </div>

  </div>
  <script>
    // Get the button that opens the modal
    var btn = document.querySelectorAll("button.modal-button");

    // All page modals
    var modals = document.querySelectorAll(".modal");

    // Get the <span> element that closes the modal
    var spans = document.getElementsByClassName("close");

    // When the user clicks the button, open the modal
    for (var i = 0; i < btn.length; i++) {
      btn[i].onclick = function(e) {
        e.preventDefault();
        modal = document.querySelector(e.target.getAttribute("href"));
        modal.style.display = "block";
      };
    }

    // When the user clicks on <span> (x), close the modal
    for (var i = 0; i < spans.length; i++) {
      spans[i].onclick = function() {
        for (var index in modals) {
          if (typeof modals[index].style !== "undefined")
            modals[index].style.display = "none";
        }
      };
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
      if (event.target.classList.contains("modal")) {
        for (var index in modals) {
          if (typeof modals[index].style !== "undefined")
            modals[index].style.display = "none";
        }
      }
    };
  </script>
</body>

</html>
<!-- <?php include_once('layout/footer_setting.php') ?> -->