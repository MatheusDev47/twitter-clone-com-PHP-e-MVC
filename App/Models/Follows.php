<?php

namespace App\Models;

use MF\Model\Model;

class Follows extends Model
{
    private $id;
    private $id_user;
    private $id_user_follow;

    public function __get($attr)
    {
        return $this->$attr;
    }

    public function __set($attr, $value)
    {
        $this->$attr = $value;
        return $this;
    }

    public function follow($id_user_follow)
    {
        $query = "INSERT INTO users_follows(id_user, id_user_follow)VALUES(:id_user, :id_user_follow)";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_user', $this->__get('id_user'));
        $stmt->bindValue(':id_user_follow', $id_user_follow);
        $stmt->execute();
    }

    public function unfollow($id_user_follow)
    {
        $query = "DELETE FROM users_follows WHERE id_user = :id_user AND id_user_follow = :id_user_follow";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_user', $this->__get('id_user'));
        $stmt->bindValue(':id_user_follow', $id_user_follow);
        $stmt->execute();
    }

    public function getAmountFollows () 
    {
        $query = "
        SELECT 
            COUNT(id_user_follow) AS follows, 
        (
            SELECT 
                COUNT(id_user) AS followers
            FROM 
                users_follows 
            WHERE 
                id_user_follow = :id_user
        )AS followers
        FROM 
            users_follows 
        WHERE 
            id_user = :id_user";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_user', $this->__get('id_user'));
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
