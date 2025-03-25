<?php
require_once './Database/dbconfig.php';
$user_id = $_SESSION['user_session'];
$budget_category = $DB_con->query("SELECT * FROM budget WHERE user_id = $user_id");

?>

<div id="myModal4" class="modal">
  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
      <h2>Add Expense</h2>
    </div>
    <hr />
    <div class="modal-body">
      <form action="expenses.php" method="post">
        <div class="add-align">
          <div class="budget-align">
            <label>Expense Name</label>
            <input class="add-input" name="expense_name" type="text" />
          </div>
          <div class="budget-align">
            <label>Amount</label>
            <input class="add-amount" name="expense_amount" type="text" />
          </div>
        </div>
        <div class="budget-align">
          <label>Budget Category Name</label>
          <select name="category">
            <?php while ($category = $budget_category->fetch(PDO::FETCH_ASSOC)): ?>
              <option value="<?= $category['budget_category'] ?>"><?= $category['budget_category'] ?></option>
            <?php endwhile; ?>
          </select>
        </div>
    </div>
    <hr />
    <div class="modal-footer">
      <div class="footer-btn">
        <button class="btn-submit" name="btn-expense_add">Save</button>
        </form>
        <button class="btn-cancel close">Cancel</button>
      </div>
    </div>
  </div>