<?php
require_once './Database/dbconfig.php';

// Start session and check login
if (!$user->is_loggedin()) {
    $user->redirect('index.php');
    exit();
}

$user_id = $_SESSION['user_session'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update Expense
    if (isset($_POST['btn-expense_update'])) {
        $expense_id = $_POST['expense_id'];
        $name = trim($_POST['expense_name']);
        $amount = trim($_POST['expense_amount']);
        $category = trim($_POST['category']);
        $date = trim($_POST['expense_date']);

        try {
            $stmt = $DB_con->prepare("UPDATE expense SET name = :name, amount = :amount, 
                                    category = :category, date = :date 
                                    WHERE expense_id = :expense_id AND user_id = :user_id");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':amount', $amount);
            $stmt->bindParam(':category', $category);
            $stmt->bindParam(':date', $date);
            $stmt->bindParam(':expense_id', $expense_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            $_SESSION['success'] = "Expense updated successfully!";
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error updating expense: " . $e->getMessage();
        }
        $user->redirect('custombudget.php');
        exit();
    }

    // Delete Expense
    if (isset($_POST['btn-expense_delete'])) {
        $expense_id = $_POST['expense_id'];

        try {
            $stmt = $DB_con->prepare("DELETE FROM expense WHERE expense_id = :expense_id AND user_id = :user_id");
            $stmt->bindParam(':expense_id', $expense_id);
            $stmt->bindParam(':user_id', $user_id);
            $stmt->execute();

            $_SESSION['success'] = "Expense deleted successfully!";
        } catch (PDOException $e) {
            $_SESSION['error'] = "Error deleting expense: " . $e->getMessage();
        }
        $user->redirect('custombudget.php');
        exit();
    }
}

$user->redirect('custombudget.php');
