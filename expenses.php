<?php
require_once './Database/dbconfig.php';

$user_id = $_SESSION['user_session'];

if (isset($_POST['btn-expense_add'])) {
    $expense_name = $_POST['expense_name'];
    $expense_amount = $_POST['expense_amount'];
    $category = $_POST['category'];

    // Validate the amount
    if (!is_numeric($expense_amount) || $expense_amount <= 0) {
        $_SESSION['error'] = "Please enter a valid positive number for the amount!";
        $user->redirect('custombudget.php');
        exit();
    }

    // Check if the expense would exceed the budget category limit
    $current_spent = $budget->getSpentExpense($user_id, $category);
    $remaining = $budget_limit - $current_spent;

    if ($expense_amount > $remaining) {
        $_SESSION['error'] = "This expense would exceed your budget for this category!";
        $user->redirect('custombudget.php');
        exit();
    }

    // If all validations pass, add the expense
    try {
        $expense->setExpense($expense_name, $expense_amount, $category, $user_id);
        $_SESSION['success'] = "Expense added successfully!";
        $user->redirect('custombudget.php');
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error adding expense: " . $e->getMessage();
        $user->redirect('custombudget.php');
    }
}
