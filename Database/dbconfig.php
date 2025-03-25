<?php
session_start();
$DB_host = "localhost";
$DB_user = "root";
$DB_pass = "";
$DB_name = "studentspend";

try {
     $DB_con = new PDO("mysql:host={$DB_host};dbname={$DB_name}", $DB_user, $DB_pass);
     $DB_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
     echo $e->getMessage();
}

include 'Controller/Total_Allowance.Controller.php';
$user = new totalAllowance($DB_con);

include_once 'Controller/Budget_Category_Name.Controller.php';
$budget = new budget($DB_con);

include_once 'Controller/Expense.Controller.php';
$expense = new Expense($DB_con);
