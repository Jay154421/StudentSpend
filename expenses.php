<?php
require_once './Database/dbconfig.php';


$user_id = $_SESSION['user_session'];

if (isset($_POST['btn-expense_add'])) {
    $expense_name = $_POST['expense_name'];
    $expense_amount = $_POST['expense_amount'];
    $category = $_POST['category'];
    $expense->setExpense($expense_name, $expense_amount, $category, $user_id);
    $user->redirect('custombudget.php');
}
