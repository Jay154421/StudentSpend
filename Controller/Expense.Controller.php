<?php
include_once 'Controller/User.Controller.php';

class Expense extends USER
{
    public function setExpense($expense_name, $expense_amount, $category, $user_id)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO expense(name, amount, category, user_id) VALUES(:expense_name, :expense_amount, :category, :user_id)");
            $stmt->bindparam(":expense_name", $expense_name);
            $stmt->bindparam(":expense_amount", $expense_amount);
            $stmt->bindparam(":category", $category);
            $stmt->bindparam(":user_id", $user_id);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getBudget($user_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM budget WHERE user_id=:user_id");
        $stmt->execute(array(":user_id" => $user_id));
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }
}
