<?php
      if (isset($_POST['btn-update'])) {
        $amountTotal = $_POST['amount'];
        if (!$amount) {
          echo "<script>
            Swal.fire({
              title: 'Error',
              text: 'Please enter a valid amount for updating the total allowance.',
              icon: 'error',
              confirmButtonText: 'OK'
            });
          </script>";
        } else {
          $total_budgets = $budget->sumAllBudgets($user_id);
          if ($total_budgets > $amountTotal) {
            echo "<script>
              Swal.fire({
                title: 'Budget Warning',
                text: 'Your total allowance is less than your current budget categories!',
                icon: 'warning',
                confirmButtonText: 'OK'
              });
            </script>";
          } else {
            $user->updateTotalAllowance($amountTotal, $user_id);
            $user->redirect('custombudget.php');
          }
        }
      }
      ?>
      <div id="myModal1" class="modal">
        <div class="modal-content">
          <div class="modal-header">
            <h2>Update Total Allowance</h2>
            <button class="modal-close">&times;</button>
          </div>
          <div class="modal-body">
            <form method="post">
              <div class="form-group">
                <label>Amount</label>
                <input name="amount" type="text" value="<?= $amount ? $amount['amount'] : '' ?>">
              </div>
          </div>
          <div class="modal-footer">
            <button type="submit" name="btn-update" class="btn btn-primary">
              <i class="fas fa-save"></i> Save
            </button>
            <button type="button" class="btn btn-secondary close">
              <i class="fas fa-times"></i> Cancel
            </button>
            </form>
          </div>
        </div>
      </div>