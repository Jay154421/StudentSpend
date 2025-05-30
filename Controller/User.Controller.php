<?php
class USER
{
    public $db;

    function __construct($DB_con)
    {
        $this->db = $DB_con;
    }

    public function redirect($url)
    {
        header("Location: $url");
        exit();
    }

    public function register($username, $fullname, $email, $password)
    {
        try {
            $new_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = $this->db->prepare("INSERT INTO users(username,fullname,email,password) VALUES(:username, :fullname, :email, :password)");
            $stmt->bindparam(":username", $username);
            $stmt->bindparam(":fullname", $fullname);
            $stmt->bindparam(":email", $email);
            $stmt->bindparam(":password", $new_password);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
    public function authRegister($user)
    {
        $username = $_POST['username'];
        $fullname = $_POST['fullname'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $conpassword = $_POST['conpassword'];

        if ($username == "") {
            echo "<p style='color:red;'>Provide username!</p>";
        } else if ($fullname == "") {
            echo "<p style='color:red;'>Provide fullname!</p>";
        } else if ($email == "") {
            echo "<p style='color:red;'>Provide email!</p>";
        } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<p style='color:red;'>Please enter a valid email address!</p>";
        } else if ($password == "") {
            echo "<p style='color:red;'>Provide password!</p>";
        } else if (strlen($password) < 6) {
            echo "<p style='color:red;'>Password must be atleast 6 characters</p>";
        } else if ($password !== $conpassword) {
            echo "<p style='color:red;'>Passwords you entered do not match</p>";
        } else {
            try {
                $stmt = $this->db->prepare("SELECT username,email FROM users WHERE username=? OR email=?");
                $stmt->execute([$username, $email]);
                $row = $stmt->fetch(PDO::FETCH_ASSOC);

                if (is_array($row)) {
                    if ($row['username'] == $username) {
                        echo "<p style='color:red;'>Sorry username already taken!</p>";
                    } else if ($row['email'] == $email) {
                        echo "<p style='color:red;'>Sorry email id already taken!</p>";
                    }
                } else {
                    if ($this->register($username, $fullname, $email, $password)) {
                        $this->redirect("index.php");
                    }
                }
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
    }

    public function authLogin($user)
    {
        $username = $_POST['username_email'];
        $email = $_POST['username_email'];
        $password = $_POST['password'];

        if ($this->login($username, $email, $password)) {
            $this->redirect('Dashboard.php');
        } else {
            echo "<p style='color:red;'>Invalid username/email or password</p>";
        }
    }

    public function login($username, $email, $password)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE username=:username OR email=:email LIMIT 1");
            $stmt->execute(array(':username' => $username, ':email' => $email));
            $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($stmt->rowCount() > 0) {
                if (password_verify($password, $userRow['password'])) {
                    $_SESSION['user_session'] = $userRow['user_id'];
                    return true;
                } else {
                    return false;
                }
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function is_loggedin()
    {
        if (isset($_SESSION['user_session'])) {
            return true;
        }
    }


    public function name($user_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE user_id=:user_id");
        $stmt->execute(array(":user_id" => $user_id));
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }

    public function uploadPhoto($image, $user_id)
    {

        try {
            $stmt = $this->db->prepare("INSERT INTO file(photo,user_id) VALUES(:photo,:user_id)");
            $stmt->bindparam(":photo", $image);
            $stmt->bindparam(":user_id", $user_id);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function changePhoto($image, $user_id)
    {
        try {
            $stmt = $this->db->prepare("UPDATE file SET  photo=:photo WHERE user_id=:user_id");
            $stmt->bindparam(":photo", $image);
            $stmt->bindparam(":user_id", $user_id);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function profilePhoto($user_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM file WHERE user_id=:user_id");
        $stmt->execute(array(":user_id" => $user_id));
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }


    public function changePassword($currentPassword, $newPassword)
    {
        // Check if the current password is correct
        if (!$this->verifyPassword($currentPassword)) {
            echo "<p style='color:red;'>Wrong password</p>";
        }

        // Hash the new password
        $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the password in the database
        $stmt = $this->db->prepare("UPDATE users SET password = :password WHERE user_id = :user_id");
        $stmt->bindParam(':password', $hashedNewPassword);
        $stmt->bindParam(':user_id', $_SESSION['user_session']);
        $result = $stmt->execute();

        if ($result) {
            return true;
        } else {
            throw new Exception("Failed to update password");
        }
    }

    private function verifyPassword($password)
    {
        $stmt = $this->db->prepare("SELECT password FROM users WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $_SESSION['user_session']);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && password_verify($password, $row['password'])) {
            return true;
        }
        return false;
    }
}
