<?php
include 'Controller/User.Controller.php';

class totalAllowance extends USER
{
    public function setTotalAllowance($amount, $user_id)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO total_allowance(amount,user_id) VALUES(:amount,:user_id)");
            $stmt->bindparam(":amount", $amount);
            $stmt->bindparam(":user_id", $user_id);
            $stmt->execute();
            return $stmt;
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function isTotalAllowance($user_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM total_allowance WHERE user_id=:user_id");
        $stmt->execute(array(":user_id" => $user_id));
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }

    public function getTotalAllowance($user_id)
    {
        $stmt = $this->db->prepare("SELECT * FROM total_allowance WHERE user_id=:user_id");
        $stmt->execute(array(":user_id" => $user_id));
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    }

    public function updateTotalAllowance($amount, $user_id)
    {
        try {
            $stmt = $this->db->prepare("UPDATE total_allowance SET  amount=:new_amount WHERE user_id = $user_id");
            $stmt->bindparam(":new_amount", $amount);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
}
