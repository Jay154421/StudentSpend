<?php include_once('layout/header_setting.php') ?>

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
      <?php include('components/UploadPhoto.php') ?>
    </div>
  </div>
  <div class="text">
    <div>Full Name</div>
    <div class="sub-text">Christian154</div>
  </div>
  <hr />
  <div class="text">
    <div>Email</div>
    <div class="sub-text">Christian.Forrest@gmail.com</div>
  </div>
  <hr />
  <div class="text">
    <div>Username</div>
    <div class="sub-text">Christian154</div>
  </div>
  <hr />

  <button class="btn-change_password modal-button" href="#myModal2">Change Password</button>

  <?php include_once('components/change_password.php') ?>
</div>

<?php include_once('layout/footer_setting.php') ?>