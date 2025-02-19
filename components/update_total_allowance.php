<?php require_once './Database/dbconfig.php';
    $user_id = $_SESSION['user_session'];
    $amount = $user->getTotalAllowance($user_id);
?>

<div id="myModal1" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <form method="post">
            <div class="modal-header">
                <h2>Update Total Allowance</h2>
            </div>
            <hr />
            <div class="modal-body">
                <div class="input-user">
                    <label>Amount</label>
                    <input type="text" placeholder="<?= $amount['amount'] ?>" />
                </div>
            </div>
            <hr />
            <div class="modal-footer">
                <div class="footer-btn">
                    <button class="btn-submit" name="btn-save">Save</button>
                    <button class="btn-cancel close">Cancel</button>
                </div>
            </div>
        </form>
    </div>
</div>