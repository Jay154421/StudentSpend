<?php
require_once './Database/dbconfig.php';
$user_id = $_SESSION['user_session'];
$amount = $user->getTotalAllowance($user_id);


// if (is_string($_POST['amount'])) {
//     header("Location: test.php");
// }

if (isset($_POST['btn-save'])) {
    $amountTotal = $_POST['amount'];
    $user->setTotalAllowance($amountTotal, $user_id);
    $user->redirect('custombudget.php');
}
?>

<div id="myModal2" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <form method="post">
            <div class="modal-header">
                <h2>Create Total Allowance</h2>
            </div>
            <hr />
            <div class="modal-body">
                <div class="input-user">
                    <label>Amount</label>
                    <input type="text" name="amount" />
                </div>
            </div>
            <hr />
            <div class="modal-footer">
                <div class="footer-btn">
                    <button class="btn-submit" type="submit" name="btn-save">Save</button>
                    <button class="btn-cancel close">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>