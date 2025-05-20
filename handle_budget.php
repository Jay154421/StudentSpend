<?php
require_once './Database/dbconfig.php';
$user_id = $_SESSION['user_session'];

if (isset($_POST['btn-update'])) {
    $budget_id = $_POST['budget_id'];
    $budget_category = $_POST['budget_category'];
    $amount = $_POST['amount'];
    
    // Check if the new total would exceed allowance
    $total_allowance = $user->getTotalAllowance($user_id);
    $current_budgets_sum = $budget->sumAllBudgets($user_id);
    $current_budget = $budget->getBudgetById($budget_id, $user_id);
    
    $new_total = $current_budgets_sum - $current_budget['amount'] + $amount;
    
    if ($total_allowance && $new_total > $total_allowance['amount']) {
        $_SESSION['error'] = "Updating this budget would exceed your total allowance!";
        header("Location: custombudget.php");
        exit();
    }
    
    if ($budget->updateBudget($budget_id, $budget_category, $amount, $user_id)) {
        $_SESSION['success'] = "Budget updated successfully!";
    } else {
        $_SESSION['error'] = "Failed to update budget!";
    }
    header("Location: custombudget.php");
    exit();
}

if (isset($_POST['btn-delete'])) {
    $budget_id = $_POST['budget_id'];
    
    if ($budget->deleteBudget($budget_id, $user_id)) {
        $_SESSION['success'] = "Budget deleted successfully!";
    } else {
        $_SESSION['error'] = "Failed to delete budget!";
    }
    header("Location: custombudget.php");
    exit();
}