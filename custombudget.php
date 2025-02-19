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
  <div class="box">
    <div class="top">
      <div style="font-weight: 700">Personal</div>
      <div>₱500.00 <label>Budgeted</label></div>
    </div>
    <div class="circle">
      <div class="inner-circle"></div>
    </div>
    <div class="bottom">
      <div>₱50 spent</div>
      <div>₱450 remaining</div>
    </div>
  </div>
</div>


<div class="budget">
  <div>Total Budget : <?= $amount['amount'] ?></div>
  <div>Remaining : ₱4,550</div>
</div>

<!-- table of list expenses  -->
<div style="margin: 0 15px">
  <table>
    <tr>
      <th>Budget Category Name</th>
      <th>Expense Name</th>
      <th>Amount</th>
      <th>Date</th>
    </tr>
    <tr>
      <td>Personal</td>
      <td>Ballpen</td>
      <td>₱50</td>
      <td>8/12/2024</td>
    </tr>
  </table>
</div>
</div>
</div>

<!-- footer -->
<?php include 'layout/footer_custombudget.php'; ?>