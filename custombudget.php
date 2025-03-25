<!-- Header -->
<?php include 'layout/header_custombudget.php'; ?>

<div class="main">
  <h3>Custom Budget</h3>

  <!--START All Button to click -->
  <div class="btn-list">
    <button class="primary modal-button" href="#myModal1">
      Update Total Allowance
    </button>
    <button class="primary modal-button" href="#myModal2">
      Create Total Allowance
    </button>
    <button class="non-primary modal-button" href="#myModal3">
      Create Budget
    </button>
    <button class="non-primary modal-button" href="#myModal4">
      Add Expense
    </button>
  </div>

  <!-- The Modal for Update Total Allowance -->
  <?php include 'components/update_total_allowance.php'; ?>

  <!-- The Modal for  Create Total Allowance  -->
  <?php include 'components/create_total_allowance.php'; ?>

  <!-- The Modal for  Create Budget  -->
  <?php include 'components/create_budget.php'; ?>

  <!-- The Modal for Add Expense-->
  <?php include 'components/add_expense.php'; ?>
</div>

<!--END All Button to click -->

<!-- pop up the category list -->
<div class="container">
  <?php while ($category = $all_category->fetch(PDO::FETCH_ASSOC)): ?>
    <div class="box">
      <div class="top">
        <div style="font-weight: 700"><?= $category['budget_category'] ?></div>
        <div>₱<?= $category['amount'] ?> <label>Budgeted</label></div>
      </div>
      <div class="circle">
        <div class="inner-circle"></div>
      </div>
      <div class="bottom">
        <div>₱50 spent</div>
        <div>₱450 remaining</div>
      </div>
    </div>
  <?php endwhile; ?>
</div>


<div class="budget">
  <div class="budget-header">
    <div>Total Budget: <?= $amount ? $amount['amount'] : "0" ?></div>
    <div>Remaining : <?php echo "5001236"; ?></div>
  </div>
</div>

<!-- table of list expenses  -->
<div class="table-container">
  <table class="table-header">
    <tr>
      <th>Budget Category Name</th>
      <th>Expense Name</th>
      <th>Amount</th>
      <th>Date</th>
    </tr>
    <?php while ($expenses = $all_expense->fetch(PDO::FETCH_ASSOC)): ?>
      <tr>
        <td><?= $expenses['category'] ?></td>
        <td><?= $expenses['name'] ?></td>
        <td><?= $expenses['amount'] ?></td>
        <td><?= $expenses['date'] ?></td>
      </tr>
    <?php endwhile; ?>
  </table>
</div>

</div>
</div>

<!-- footer -->
<?php include 'layout/footer_custombudget.php'; ?>