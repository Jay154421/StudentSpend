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