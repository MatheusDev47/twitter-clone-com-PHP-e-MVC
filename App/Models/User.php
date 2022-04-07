<?php

namespace App\Models;

use MF\Model\Model;

class User extends Model
{
    private $id;
    private $name;
    private $email;
    private $pass;

    public function __get($attr)
    {
        return $this->$attr;
    }

    public function __set($attr, $value)
    {
        $this->$attr = $value;
        return $this;
    }

    //salvar
    public function registerUser()
    {
        $query = "INSERT INTO users(name, email, pass)VALUES(:name, :email, :pass)";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':name', $this->__get('name'));
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->bindValue(':pass', $this->__get('pass'));
        $stmt->execute();
    }

    //validar se um cadastro pode ser feito
    public function registerValidation($attribute)
    {
        // return strlen($attribute) >= 3 ? $attribute : $attribute = '';
        if (strlen($attribute) >= 3) {
            return htmlspecialchars($attribute);
        }
    }

    //recuperar um usuário por email
    public function getUserEmail()
    {
        $query = "SELECT email FROM users WHERE email = :email";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function auth()
    {
        $query = "SELECT id, name, email, pass FROM users WHERE email = :email AND pass = :pass";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $this->__get('email'));
        $stmt->bindValue(':pass', $this->__get('pass'));
        $stmt->execute();
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!empty($user['id']) && !empty($user['name'])) {
            $this->__set('id', $user['id']);
            $this->__set('name', $user['name']);
        }
        return $this;
    }

    public function getUsers()
    {
        //Utilizando JOINS para verificar se um usuário está seguindo ou não outro usuário 
        // $query = "
        // SELECT 
        //     u.id, f.id_user_follow, u.name, u.email 
        // FROM 
        //     users AS u
        // LEFT JOIN 
        //     users_follows AS f ON u.id = f.id_user_follow
        // WHERE 
        //     u.name LIKE :name AND u.id != :id_user";   

        //Utilizando subconsultas para verificar se um usuário está seguindo ou não outro usuário 
        $query = "
        SELECT 
            u.id, u.name, u.email,
        (
           SELECT 
                COUNT(*)
           FROM
                users_follows AS uf
            WHERE
                uf.id_user = :id_user AND uf.id_user_follow = u.id
        ) AS follow_yn 
        FROM 
            users AS u
        WHERE 
            u.name LIKE :name AND u.id != :id_user";
        

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':name', '%'.$this->__get('name').'%');
        $stmt->bindValue(':id_user', $this->__get('id'));
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function getAmoutTweets ()
    {
        $query = "SELECT COUNT(tweet) AS tweets FROM tweets WHERE id_usuario = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
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
                id_user_follow = :id
        )AS followers
        FROM 
            users_follows 
        WHERE 
            id_user = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $this->__get('id'));
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
