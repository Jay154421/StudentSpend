<?php
include_once 'Controller/User.Controller.php';

class budget extends USER
{
    public function setBudget($budget_category, $category_amount, $user_id)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO budget(budget_category,amount,user_id) VALUES(:budget_category,:amount,:user_id)");
            $stmt->bindparam(":budget_category", $budget_category);
            $stmt->bindparam(":amount", $category_amount);
            $stmt->bindparam(":user_id", $user_id);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function getSpentExpense($user_id, $budget_category)
    {
        global $DB_con;
        $stmt = $DB_con->prepare("SELECT SUM(amount) as total_spent FROM expense WHERE user_id = :user_id AND category = :budget_category");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':budget_category', $budget_category);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total_spent'] ?? 0;
    }

    public function getBudget($user_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM budget WHERE user_id=:user_id");
        $stmt->execute(array(":user_id" => $user_id));
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }

    public function countAllBudgets($user_id)
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM budget WHERE user_id=:user_id");
        $stmt->execute(array(":user_id" => $user_id));
        $total = $stmt->fetchColumn();
        return $total;
    }

    public function sumAllBudgets($user_id)
    {
        $stmt = $this->db->prepare("SELECT SUM(amount) as total FROM budget WHERE user_id=:user_id");
        $stmt->execute(array(":user_id" => $user_id));
        $total = $stmt->fetchColumn();
        return $total;
    }

    public function updateBudget($budget_id, $budget_category, $amount, $user_id)
    {
        try {
            $stmt = $this->db->prepare("UPDATE budget SET budget_category = :budget_category, amount = :amount WHERE budget_id = :budget_id AND user_id = :user_id");
            $stmt->bindparam(":budget_category", $budget_category);
            $stmt->bindparam(":amount", $amount);
            $stmt->bindparam(":budget_id", $budget_id);
            $stmt->bindparam(":user_id", $user_id);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function deleteBudget($budget_id, $user_id)
    {
        try {
            // First delete all expenses associated with this budget category
            $stmt = $this->db->prepare("DELETE FROM expense WHERE user_id = :user_id AND category IN (SELECT budget_category FROM budget WHERE budget_id = :budget_id)");
            $stmt->bindparam(":user_id", $user_id);
            $stmt->bindparam(":budget_id", $budget_id);
            $stmt->execute();

            // Then delete the budget category
            $stmt = $this->db->prepare("DELETE FROM budget WHERE budget_id = :budget_id AND user_id = :user_id");
            $stmt->bindparam(":budget_id", $budget_id);
            $stmt->bindparam(":user_id", $user_id);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function getBudgetById($budget_id, $user_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM budget WHERE budget_id = :budget_id AND user_id = :user_id");
        $stmt->execute(array(":budget_id" => $budget_id, ":user_id" => $user_id));
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
