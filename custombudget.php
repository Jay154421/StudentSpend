<!-- Header -->
<?php
require_once './Database/dbconfig.php';
$user_id = $_SESSION['user_session'];
$name = $user->name($user_id);
$photo = $user->profilePhoto($user_id);

if (!$user->is_loggedin()) {
  $user->redirect('index.php');
}
$all_category = $DB_con->query("SELECT * FROM budget WHERE user_id = $user_id");
$all_expense = $DB_con->query("SELECT * FROM expense WHERE user_id = $user_id");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="css/custmonbudget.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <title>Custom Budget</title>
</head>

<body>
  <div style="display: flex; justify-content: space-between">
    <!-- left sidebar -->
    <nav>
      <div class="left-nav">
        <div class="profile">
          <div>
            <?php if ($photo) { ?>
              <img class="nav-image" class="photo" src="./image/<?= $photo['photo'] ?>">
            <?php } else { ?>
              <img src="asset/profile.png" alt="" />
            <?php } ?>
          </div>
          <div><?= $name['username'] ?></div>
        </div>
        <div class="nav-list">
          <a href="dashboard.php">Dashboard</a>
          <a class="active" href="custombudget.php">Custom Budget</a>
          <a href="setting.php">Setting</a>
        </div>
        <a href="components/logout.php">Log Out</a>
      </div>
    </nav>
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
        <?php
        //Check spent-expense and remaining-expense

        $budget_amount =  $category['amount'];
        $budget_category = $category['budget_category'];
        $spent_expense = $budget->getSpentExpense($user_id,  $budget_category);
        if ($spent_expense == null) {
          $spent_expense = 0;
        }

        $remaining_expense = $budget_amount - $spent_expense;
        $percentege = ($spent_expense / $budget_amount) * 100;

        // Check if remaining expense exceeds budget amount
        if ($remaining_expense < 0) {
          $error_message = "Warning: Remaining expense exceeds budget amount!";
        } else {
          $error_message = "";
        }

        // Check if remaining expense exceeds budget amount
        if ($remaining_expense < 0) {
          $error_message = "Warning: Remaining expense exceeds budget amount!";
          $class = "error";
        } else {
          $error_message = "";
          $class = "";
        }

        // Add this condition before the form fields
        if ($error_message) {
          $percentege = 100;
          echo "
          <script>
          Swal.fire({
            title: 'Error',
            text: '$error_message',
            icon: 'warning',
            confirmButtonText: 'OK'
          })
          </script>
          ";
        }

        $remaining_color = $remaining_expense >= $spent_expense ? 'black' : 'red';
        ?>

        <div class="box">
          <div class="top">
            <div style="font-weight: 700"><?= $category['budget_category'] ?></div>
            <div>₱<?= $category['amount'] ?> <label>Budgeted</label></div>
          </div>
          <div class="circle">
            <div class="inner-circle" style="width: <?php echo $percentege ?? '0' ?>%;"></div>
          </div>
          <div class="bottom">
            <div>₱<?= $spent_expense ?> spent</div>
            <div style="color: <?= $remaining_color; ?>">₱<?= $remaining_expense ?> remaining</div>
          </div>
        </div>
      <?php endwhile; ?>
    </div>


    <div class="budget">
      <div class="budget-header">
        <div>Total Allowance: ₱<?= $amount ? $amount['amount'] : "0" ?></div>
        <div>Remaining : ₱<span id="remaining-budget"></span></div>
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
  <script>
    document.addEventListener("DOMContentLoaded", function() {

      let totalBudget = parseFloat("<?= isset($amount['amount']) ? $amount['amount'] : 0 ?>") || 0;
      let totalExpenses = 0;

      let expenseRows = document.querySelectorAll(".table-header tr:not(:first-child)");
      expenseRows.forEach(function(row) {
        let expenseCell = row.cells[2];
        if (expenseCell) {
          let expenseAmount = parseFloat(expenseCell.innerText.replace(/[^0-9.-]+/g, ""));
          if (!isNaN(expenseAmount)) {
            totalExpenses += expenseAmount;
          }
        }
      });

      let remainingBudget = totalBudget - totalExpenses;

      let remainingBudgetElement = document.getElementById('remaining-budget');
      if (remainingBudgetElement) {
        if (remainingBudget < 0) {
          remainingBudgetElement.style.color = "red";
        }
        remainingBudgetElement.innerText = remainingBudget.toLocaleString(undefined, {
          minimumFractionDigits: 2,
          maximumFractionDigits: 2
        });
      } else {
        console.warn("Element #remaining-budget not found!");
      }
    });


    // Get the button that opens the modal
    var btn = document.querySelectorAll("button.modal-button");

    // All page modals
    var modals = document.querySelectorAll(".modal");

    // Get the <span> element that closes the modal
    var spans = document.getElementsByClassName("close");

    // When the user clicks the button, open the modal
    for (var i = 0; i < btn.length; i++) {
      btn[i].onclick = function(e) {
        e.preventDefault();
        modal = document.querySelector(e.target.getAttribute("href"));
        modal.style.display = "block";
      };
    }

    // When the user clicks on <span> (x), close the modal
    for (var i = 0; i < spans.length; i++) {
      spans[i].onclick = function() {
        for (var index in modals) {
          if (typeof modals[index].style !== "undefined")
            modals[index].style.display = "none";
        }
      };
    }

    // When the user clicks anywhere outside of the modal, close it
    window.onclick = function(event) {
      if (event.target.classList.contains("modal")) {
        for (var index in modals) {
          if (typeof modals[index].style !== "undefined")
            modals[index].style.display = "none";
        }
      }
    };
  </script>
</body>

</html>