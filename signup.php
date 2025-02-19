<html>

<head>
  <title>Sign Up</title>
  <link rel="stylesheet" href="css/signup.css" />
</head>

<body>
  <a style="font-weight: 500;" class="login-button" href="index.php">Login</a>

  <div class="form-container">
    <h2>Sign Up</h2>
    <form method="post">
      <?php
      require_once './Database/dbconfig.php';

      // if ($user->is_loggedin() != "") {
      //   $user->redirect('Dashboard.php');
      // }

      if (isset($_POST['btn-register'])) {
        $user->authRegister($_POST);
      }
      ?>
      <input placeholder="Username" type="text" name="username" />
      <input placeholder="Full Name" type="text" name="fullname" />
      <input placeholder="Email" type="email" name="email" />
      <input placeholder="Password" type="password" name="password" />
      <input placeholder="Confirm Password" type="password" name="conpassword" />
      <button style='font-weight: 500;' class="btn-submit" type="submit" name="btn-register">Submit</button>
    </form>
    <div class="login-link">
      Already have account?
      <a href="index.php"> Login here </a>
    </div>
  </div>
  <div class="image-container">
    <img src="asset/right-image.png" />
  </div>
</body>

</html>