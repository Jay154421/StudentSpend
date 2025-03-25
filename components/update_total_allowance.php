<?php
require_once './Database/dbconfig.php';
$user_id = $_SESSION['user_session'];
$amount = $user->getTotalAllowance($user_id);

if (isset($_POST['btn-update'])) {
    $amountTotal = $_POST['amount'];
    if (!$amount) {
        echo "<p style='color:red;'>Please enter a valid amount for updating the total allowance.</p>";
    } else {
        $user->updateTotalAllowance($amountTotal, $user_id);
        $user->redirect('custombudget.php');
    }
}
?>

<div id="myModal1" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <div class="modal-header">
            <h2>Update Total Allowance</h2>
        </div>
        <hr />
        <div class="modal-body">
            <form method="post">
                <div class="input-user">
                    <label>Amount</label>
                    <input name="amount" type="text" placeholder="<?= $amount ? $amount['amount'] : 'No amount' ?>">
                </div>
        </div>
        <hr />
        <div class="modal-footer">
            <div class="footer-btn">
                <button class="btn-submit" name="btn-update">Save</button>
                </form>
                <button class="btn-cancel close">Cancel</button>
            </div>
        </div>
    </div>
</div>