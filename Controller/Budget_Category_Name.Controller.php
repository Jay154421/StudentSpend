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

    public function getBudget($user_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM budget WHERE user_id=:user_id");
        $stmt->execute(array(":user_id" => $user_id));
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }
}
