<?php
      if (isset($_POST['btn-budget'])) {
        $budget_category = trim($_POST['budget_category']);
        $budget_amount = trim($_POST['amount']);
        
        if (empty($budget_category)) {
          $_SESSION['error'] = "Budget category name is required!";
          $user->redirect('custombudget.php');
          exit();
        }
        
        if (!is_numeric($budget_amount) || $budget_amount <= 0) {
          $_SESSION['error'] = "Please enter a valid positive number for the amount!";
          $user->redirect('custombudget.php');
          exit();
        }
        
        $budget_amount = (float)$budget_amount;
        $total_allowance = $user->getTotalAllowance($user_id);
        $current_budgets_sum = $budget->sumAllBudgets($user_id);
        
        if ($total_allowance && ($current_budgets_sum + $budget_amount) > $total_allowance['amount']) {
          $_SESSION['error'] = "Adding this budget would exceed your total allowance!";
          $user->redirect('custombudget.php');
          exit();
        }
        
        try {
          $budget->setBudget($budget_category, $budget_amount, $user_id);
          $_SESSION['success'] = "Budget created successfully!";
          $user->redirect('custombudget.php');
        } catch (PDOException $e) {
          $_SESSION['error'] = "Error creating budget: " . $e->getMessage();
          $user->redirect('custombudget.php');
        }
      }
      ?>
      <div id="myModal3" class="modal">
        <div class="modal-content">
          <div class="modal-header">
            <h2>Create Budget</h2>
            <button class="modal-close">&times;</button>
          </div>
          <div class="modal-body">
            <form method="post">
              <div class="form-group">
                <label>Budget Category Name</label>
                <input type="text" name="budget_category">
              </div>
              <div class="form-group">
                <label>Amount</label>
                <input type="text" name="amount">
              </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="btn-budget" class="btn btn-primary">
              <i class="fas fa-save"></i> Save
            </button>
            <button type="button" class="btn btn-secondary close">
              <i class="fas fa-times"></i> Cancel
            </button>
            </form>
          </div>
        </div>
      </div>