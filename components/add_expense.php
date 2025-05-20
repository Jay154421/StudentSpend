 <div id="myModal4" class="modal">
        <div class="modal-content">
          <div class="modal-header">
            <h2>Add Expense</h2>
            <button class="modal-close">&times;</button>
          </div>
          <div class="modal-body">
            <form action="expenses.php" method="post">
              <div class="form-group">
                <label>Expense Name</label>
                <input name="expense_name" type="text">
              </div>
              <div class="form-group">
                <label>Amount</label>
                <input name="expense_amount" type="text">
              </div>
              <div class="form-group">
                <label>Budget Category</label>
                <select name="category">
                  <?php 
                  $budget_category = $DB_con->query("SELECT * FROM budget WHERE user_id = $user_id");
                  while ($category = $budget_category->fetch(PDO::FETCH_ASSOC)): ?>
                    <option value="<?= $category['budget_category'] ?>"><?= $category['budget_category'] ?></option>
                  <?php endwhile; ?>
                </select>
              </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="btn-expense_add" class="btn btn-primary">
              <i class="fas fa-save"></i> Save
            </button>
            <button type="button" class="btn btn-secondary close">
              <i class="fas fa-times"></i> Cancel
            </button>
            </form>
          </div>
        </div>
      </div>