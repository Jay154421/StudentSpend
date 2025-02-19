<html>

<head>
  <title>StudentSpend</title>
  <link rel="stylesheet" href="css/login.css" />
</head>

<body>
  <a class="signup-btn" style="font-weight: 500;" href="signup.php">Sign Up</a>

  <div class="left">
    <h2>Login now</h2>
    <form method="post">
      <?php
      require_once './Database/dbconfig.php';

      if ($user->is_loggedin() != "") {
        $user->redirect('Dashboard.php');
      }

      if (isset($_POST['btn-login'])) {
        $user->authLogin($_POST['btn-login']);
      }
      ?>
      <div class="input-group">
        <input placeholder="Username or Email" type="text" name="username_email" />
      </div>
      <div class="input-group">
        <input placeholder="Password" type="password" name="password" />
        <a href="#"> Forgot Password? </a>
      </div>
      <button style="font-weight: 500;" class="btn" type="submit" name="btn-login">Login</button>
    </form>
    <div class="signup">
      Not registered yet?
      <a href="signup.php"> Create an account </a>
    </div>
  </div>
  <div class="right">
    <img
      alt="Illustration of a person standing next to a large receipt and financial icons"
      src="asset/right-image.png" />
  </div>
</body>

</html>