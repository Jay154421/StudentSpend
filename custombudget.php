<?php
require_once './Database/dbconfig.php';

// Start session and check login
if (!$user->is_loggedin()) {
    $user->redirect('index.php');
    exit();
}

$user_id = $_SESSION['user_session'];
$name = $user->name($user_id);
$photo = $user->profilePhoto($user_id);

// Handle all form submissions first
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update Total Allowance
    if (isset($_POST['btn-update'])) {
        $amountTotal = $_POST['amount'];
        if (!is_numeric($amountTotal)) {
            $_SESSION['error'] = 'Please enter a valid amount';
        } else {
            $total_budgets = $budget->sumAllBudgets($user_id);
            if ($total_budgets > $amountTotal) {
                $_SESSION['error'] = 'Your total allowance is less than current budgets!';
            } else {
                $user->updateTotalAllowance($amountTotal, $user_id);
                $_SESSION['success'] = 'Allowance updated successfully';
                $user->redirect('custombudget.php');
                exit();
            }
        }
    }

    // Create Total Allowance
    if (isset($_POST['btn-save'])) {
        $amountTotal = $_POST['amount'];
        if (!is_numeric($amountTotal)) {
            $_SESSION['error'] = 'Please enter a valid amount';
        } else {
            $isTotalAllowance = $user->getTotalAllowance($user_id);
            if ($isTotalAllowance) {
                $_SESSION['error'] = 'Total allowance already set!';
            } else {
                $user->setTotalAllowance($amountTotal, $user_id);
                $_SESSION['success'] = 'Allowance set successfully';
                $user->redirect('custombudget.php');
                exit();
            }
        }
    }

    // Create Budget
    if (isset($_POST['btn-budget'])) {
        $budget_category = trim($_POST['budget_category']);
        $budget_amount = trim($_POST['amount']);

        if (empty($budget_category)) {
            $_SESSION['error'] = "Budget category name is required!";
        } elseif (!is_numeric($budget_amount) || $budget_amount <= 0) {
            $_SESSION['error'] = "Please enter a valid positive number for the amount!";
        } else {
            $budget_amount = (float)$budget_amount;
            $total_allowance = $user->getTotalAllowance($user_id);
            $current_budgets_sum = $budget->sumAllBudgets($user_id);

            if ($total_allowance && ($current_budgets_sum + $budget_amount) > $total_allowance['amount']) {
                $_SESSION['error'] = "Adding this budget would exceed your total allowance!";
            } else {
                try {
                    $budget->setBudget($budget_category, $budget_amount, $user_id);
                    $_SESSION['success'] = "Budget created successfully!";
                    $user->redirect('custombudget.php');
                    exit();
                } catch (PDOException $e) {
                    $_SESSION['error'] = "Error creating budget: " . $e->getMessage();
                }
            }
        }
    }
}

// Get data for display
$all_category = $DB_con->query("SELECT * FROM budget WHERE user_id = $user_id");
$all_expense = $DB_con->query("SELECT * FROM expense WHERE user_id = $user_id ORDER BY name DESC");
$amount = $user->getTotalAllowance($user_id);
$total_budgets = $budget->sumAllBudgets($user_id);

// Calculate remaining budget for display
$remaining_budget = 0;
if ($amount) {
    $total_expenses = 0;
    $expenses = $DB_con->query("SELECT SUM(amount) as total FROM expense WHERE user_id = $user_id");
    if ($expense_total = $expenses->fetch(PDO::FETCH_ASSOC)) {
        $total_expenses = $expense_total['total'] ?: 0;
    }
    $remaining_budget = $amount['amount'] - $total_expenses;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Custom Budget</title>
    <link rel="stylesheet" href="css/custmonbudget.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <div class="app-container">
        <!-- Modern Navigation -->
        <nav class="modern-nav">
            <div class="nav-header">
                <div class="profile">
                    <?php if ($photo) { ?>
                        <img class="nav-image" src="./image/<?= htmlspecialchars($photo['photo']) ?>">
                    <?php } else { ?>
                        <img class="nav-image" src="asset/profile.png" alt="Profile">
                    <?php } ?>
                    <div class="profile-info">
                        <h4><?= htmlspecialchars($name['username']) ?></h4>
                        <span>Premium User</span>
                    </div>
                </div>
                <button class="nav-toggle" aria-label="Toggle menu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
            <div class="nav-list">
                <a href="dashboard.php">
                    <i class="fas fa-chart-line"></i>
                    <span>Dashboard</span>
                </a>
                <a class="active" href="custombudget.php">
                    <i class="fas fa-wallet"></i>
                    <span>Custom Budget</span>
                </a>
                <a href="setting.php">
                    <i class="fas fa-cog"></i>
                    <span>Settings</span>
                </a>
                <a href="components/logout.php" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>Log Out</span>
                </a>
            </div>
        </nav>

        <main class="main-content">
            <!-- Display success/error messages -->
            <?php if (isset($_SESSION['error'])): ?>
                <script>
                    Swal.fire({
                        title: 'Error',
                        text: '<?= addslashes($_SESSION['error']) ?>',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                </script>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <script>
                    Swal.fire({
                        title: 'Success',
                        text: '<?= addslashes($_SESSION['success']) ?>',
                        icon: 'success',
                        confirmButtonText: 'OK'
                    });
                </script>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <!-- Check if total allowance is exceeded by budgets -->
            <?php if ($amount && $total_budgets && $amount['amount'] < $total_budgets): ?>
                <script>
                    Swal.fire({
                        title: 'Budget Warning',
                        text: 'Your total allowance has been exceeded by your budget categories!',
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                </script>
            <?php endif; ?>

            <div class="header">
                <h1>Custom Budget</h1>
                <div class="date-display">
                    <span><?= date('F j, Y') ?></span>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="action-buttons">
                <button class="btn btn-primary modal-button" href="#myModal1">
                    <i class="fas fa-sync-alt"></i>
                    Update Total Allowance
                </button>
                <button class="btn btn-primary modal-button" href="#myModal2">
                    <i class="fas fa-plus-circle"></i>
                    Create Total Allowance
                </button>
                <button class="btn btn-secondary modal-button" href="#myModal3">
                    <i class="fas fa-folder-plus"></i>
                    Create Budget
                </button>
                <button class="btn btn-secondary modal-button" href="#myModal4">
                    <i class="fas fa-receipt"></i>
                    Add Expense
                </button>
            </div>

            <!-- Modal for Edit Budget -->
            <div id="editBudgetModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Edit Budget</h2>
                        <button class="modal-close">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form method="post" action="handle_budget.php">
                            <input type="hidden" name="budget_id" id="edit_budget_id">
                            <div class="form-group">
                                <label>Category Name</label>
                                <input type="text" name="budget_category" id="edit_budget_category" required>
                            </div>
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="number" step="0.01" name="amount" id="edit_budget_amount" required>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="btn-update" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update
                        </button>
                        <button type="button" class="btn btn-secondary close">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal for Edit Expense -->
            <div id="editExpenseModal" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Edit Expense</h2>
                        <button class="modal-close">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form action="handle_expense.php" method="post">
                            <input type="hidden" name="expense_id" id="edit_expense_id">
                            <div class="form-group">
                                <label>Expense Name</label>
                                <input name="expense_name" id="edit_expense_name" type="text" required>
                            </div>
                            <div class="form-group">
                                <label>Amount</label>
                                <input name="expense_amount" id="edit_expense_amount" type="text" required>
                            </div>
                            <div class="form-group">
                                <label>Budget Category</label>
                                <select name="category" id="edit_expense_category" required>
                                    <?php
                                    $budget_category = $DB_con->query("SELECT * FROM budget WHERE user_id = $user_id");
                                    while ($category = $budget_category->fetch(PDO::FETCH_ASSOC)): ?>
                                        <option value="<?= htmlspecialchars($category['budget_category']) ?>">
                                            <?= htmlspecialchars($category['budget_category']) ?>
                                        </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Date</label>
                                <input type="date" name="expense_date" id="edit_expense_date" required>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="btn-expense_update" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update
                        </button>
                        <button type="button" class="btn btn-secondary close">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Budget Cards Grid -->
            <div class="budget-grid">
                <?php while ($category = $all_category->fetch(PDO::FETCH_ASSOC)): ?>
                    <?php
                    $budget_amount = $category['amount'];
                    $budget_category = $category['budget_category'];
                    $spent_expense = $budget->getSpentExpense($user_id, $budget_category);
                    $spent_expense = $spent_expense ?: 0;
                    $remaining_expense = $budget_amount - $spent_expense;
                    $percentage = ($spent_expense / $budget_amount) * 100;

                    if ($remaining_expense < 0) {
                        $error_message = "Warning: Remaining expense exceeds budget amount!";
                        $percentage = 100;
                        echo "<script>
                            Swal.fire({
                                title: 'Error',
                                text: '$error_message',
                                icon: 'warning',
                                confirmButtonText: 'OK'
                            });
                        </script>";
                    }
                    ?>

                    <div class="budget-card">
                        <div class="budget-header">
                            <div class="budget-title"><?= htmlspecialchars($category['budget_category']) ?></div>
                            <div class="budget-amount">₱<?= number_format($category['amount'], 2) ?></div>
                        </div>

                        <div class="progress-container">
                            <div class="progress-bar">
                                <div class="progress-fill" style="width: <?= $percentage ?>%"></div>
                            </div>
                        </div>

                        <div class="budget-stats">
                            <div class="spent">₱<?= number_format($spent_expense, 2) ?> spent</div>
                            <div class="remaining <?= $remaining_expense < 0 ? 'negative' : '' ?>">
                                ₱<?= number_format($remaining_expense, 2) ?> remaining
                            </div>
                        </div>

                        <div class="budget-actions">
                            <button class="btn-icon btn-edit" onclick="openEditBudgetModal(
                                <?= $category['budget_id'] ?>, 
                                '<?= htmlspecialchars($category['budget_category']) ?>', 
                                <?= htmlspecialchars($category['amount']) ?>
                            )">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-icon btn-delete" onclick="confirmDelete(<?= $category['budget_id'] ?>)">
                                <i class="fas fa-trash-alt"></i>
                            </button>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>

            <!-- Summary Section -->
            <div class="summary-section">
                <div class="summary-header">
                    <div class="summary-title">Total Allowance</div>
                    <div class="summary-amount">₱<?= $amount ? number_format($amount['amount'], 2) : "0.00" ?></div>
                </div>
                <div class="summary-header">
                    <div class="summary-title">Remaining Budget</div>
                    <div class="summary-remaining <?= $remaining_budget < 0 ? 'negative' : '' ?>">
                        ₱<?= number_format($remaining_budget, 2) ?>
                    </div>
                </div>
            </div>

            <!-- Expense Table -->
            <div class="expense-table">
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Budget Category</th>
                                <th>Expense Name</th>
                                <th>Amount</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($expenses = $all_expense->fetch(PDO::FETCH_ASSOC)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($expenses['category']) ?></td>
                                    <td><?= htmlspecialchars($expenses['name']) ?></td>
                                    <td>₱<?= number_format($expenses['amount'], 2) ?></td>
                                    <td><?= htmlspecialchars($expenses['date']) ?></td>
                                    <td class="actions">
                                        <button class="btn-icon btn-edit" onclick="openEditExpenseModal(
                                            <?= $expenses['expense_id'] ?>, 
                                            '<?= htmlspecialchars($expenses['name']) ?>', 
                                            '<?= htmlspecialchars($expenses['amount']) ?>', 
                                            '<?= htmlspecialchars($expenses['category']) ?>',
                                            '<?= htmlspecialchars($expenses['date']) ?>'
                                        )">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn-icon btn-delete" onclick="confirmDeleteExpense(<?= $expenses['expense_id'] ?>)">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Modal for Update Total Allowance -->
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
                                <input name="amount" type="text" value="<?= $amount ? $amount['amount'] : '' ?>" required>
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

            <!-- Modal for Create Total Allowance -->
            <div id="myModal2" class="modal">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2>Create Total Allowance</h2>
                        <button class="modal-close">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form method="post">
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="text" name="amount" required>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" name="btn-save" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save
                        </button>
                        <button type="button" class="btn btn-secondary close">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Modal for Create Budget -->
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
                                <input type="text" name="budget_category" required>
                            </div>
                            <div class="form-group">
                                <label>Amount</label>
                                <input type="text" name="amount" required>
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

            <!-- Modal for Add Expense -->
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
                                <input name="expense_name" type="text" required>
                            </div>
                            <div class="form-group">
                                <label>Amount</label>
                                <input name="expense_amount" type="text" required>
                            </div>
                            <div class="form-group">
                                <label>Budget Category</label>
                                <select name="category" required>
                                    <?php
                                    $budget_category = $DB_con->query("SELECT * FROM budget WHERE user_id = $user_id");
                                    while ($category = $budget_category->fetch(PDO::FETCH_ASSOC)): ?>
                                        <option value="<?= htmlspecialchars($category['budget_category']) ?>">
                                            <?= htmlspecialchars($category['budget_category']) ?>
                                        </option>
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
        </main>
    </div>

    <script>
        // Toggle mobile menu
        const navToggle = document.querySelector('.nav-toggle');
        const navList = document.querySelector('.nav-list');
        const navHeader = document.querySelector('.nav-header');

        navToggle.addEventListener('click', (e) => {
            e.stopPropagation();
            navList.classList.toggle('show');
        });

        // Close menu when clicking outside
        document.addEventListener('click', (e) => {
            if (!navList.contains(e.target) && e.target !== navToggle) {
                navList.classList.remove('show');
            }
        });

        // Confirm delete
        function confirmDelete(budgetId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#06ab99',
                cancelButtonColor: '#ff6b6b',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'post';
                    form.action = 'handle_budget.php';

                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'budget_id';
                    input.value = budgetId;
                    form.appendChild(input);

                    const btn = document.createElement('input');
                    btn.type = 'hidden';
                    btn.name = 'btn-delete';
                    form.appendChild(btn);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        // Modal functionality
        var btn = document.querySelectorAll("button.modal-button");
        var modals = document.querySelectorAll(".modal");
        var closeButtons = document.querySelectorAll(".modal-close, .close");

        btn.forEach(function(button) {
            button.onclick = function(e) {
                e.preventDefault();
                var modal = document.querySelector(button.getAttribute("href"));
                modal.classList.add("show");
            };
        });

        closeButtons.forEach(function(button) {
            button.onclick = function() {
                modals.forEach(function(modal) {
                    modal.classList.remove("show");
                });
            };
        });

        window.onclick = function(event) {
            if (event.target.classList.contains("modal")) {
                modals.forEach(function(modal) {
                    modal.classList.remove("show");
                });
            }
        };

        // Open edit budget modal with data
        function openEditBudgetModal(id, category, amount) {
            document.getElementById('edit_budget_id').value = id;
            document.getElementById('edit_budget_category').value = category;
            document.getElementById('edit_budget_amount').value = amount;

            const modal = document.getElementById('editBudgetModal');
            modal.classList.add('show');
        }

        // Open edit expense modal with data
        function openEditExpenseModal(id, name, amount, category, date) {
            document.getElementById('edit_expense_id').value = id;
            document.getElementById('edit_expense_name').value = name;
            document.getElementById('edit_expense_amount').value = amount;
            document.getElementById('edit_expense_category').value = category;
            document.getElementById('edit_expense_date').value = date;

            const modal = document.getElementById('editExpenseModal');
            modal.classList.add('show');
        }

        // Confirm expense deletion
        function confirmDeleteExpense(expenseId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#06ab99',
                cancelButtonColor: '#ff6b6b',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'post';
                    form.action = 'handle_expense.php';

                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'expense_id';
                    input.value = expenseId;
                    form.appendChild(input);

                    const btn = document.createElement('input');
                    btn.type = 'hidden';
                    btn.name = 'btn-expense_delete';
                    form.appendChild(btn);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
</body>

</html>