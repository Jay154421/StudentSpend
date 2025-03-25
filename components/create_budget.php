<?php
require_once './Database/dbconfig.php';
$user_id = $_SESSION['user_session'];

if (isset($_POST['btn-budget'])) {
    $budget_category = $_POST['budget_category'];
    $budget_amount = $_POST['amount'];

    $budget->setBudget($budget_category, $budget_amount, $user_id);
    $user->redirect('custombudget.php');
}
?>

<div id="myModal3" class="modal">
    <!-- Modal content -->
    <div class="modal-content">
        <div class="modal-header">
            <h2>Create Budget</h2>
        </div>
        <hr />
        <div class="modal-body">
            <form action="" method="post">
                <div class="budget-align">
                    <label>Budget Category Name</label>
                    <input type="text" name="budget_category" />
                </div>
                <div class="budget-align">
                    <label>Amount</label>
                    <input type="text" name="amount" />
                </div>
        </div>
        <hr />
        <div class="modal-footer">
            <div class="footer-btn">
                <button class="btn-submit" name="btn-budget">Save</button>
                </form>
                <button class="btn-cancel close">Cancel</button>
            </div>
        </div>

    </div>
</div>