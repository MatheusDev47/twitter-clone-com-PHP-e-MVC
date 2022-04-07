<?php

namespace App\Models;

use MF\Model\Model;

class Tweet extends Model
{
    private $id;
    private $id_user;
    private $tweet;
    private $data;

    public function __get($attr)
    {
        return $this->$attr;
    }

    public function __set($attr, $value)
    {
        $this->$attr = $value;
        return $this;
    }

    public function add()
    {
        $query = "INSERT INTO tweets(id_usuario, tweet)VALUES(:id_usuario, :tweet)";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id_user'));
        $stmt->bindValue(':tweet', $this->__get('tweet'));
        $stmt->execute();
    }

    public function allTweets()
    {
        $query = "
        SELECT 
            COUNT(*) AS amount 
        FROM 
            tweets AS t 
        LEFT JOIN 
            users as u ON (t.id_usuario = u.id) 
        WHERE 
            id_usuario = :id_user OR t.id_usuario IN 
            (
                SELECT
                    id_user_follow
                FROM
                    users_follows
                WHERE
                    id_user = :id_user
            )";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_user', $this->__get('id_user'));
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    //recuperar com páginação
    public function recoveryPage($limit, $offset)
    {
        $query = "
        SELECT 
            u.name, t.id_usuario, t.tweet, t.id AS id_tweet, DATE_FORMAT(t.data, '%d/%m/%Y %H:%i') AS data 
        FROM 
            tweets AS t 
        LEFT JOIN 
            users as u ON (t.id_usuario = u.id) 
        WHERE 
            id_usuario = :id_user OR t.id_usuario IN 
            (
                SELECT
                    id_user_follow
                FROM
                    users_follows
                WHERE
                    id_user = :id_user
            ) 
        ORDER BY 
            t.data DESC
        LIMIT
            $limit
        OFFSET
            $offset";
        
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_user', $this->__get('id_user'));
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function delete () 
    {
        $query = "DELETE FROM tweets WHERE id = :id_tweet";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_tweet', $this->__get('id'));
        $stmt->execute();
    }

    public function getAmoutTweets ()
    {
        $query = "SELECT COUNT(tweet) AS tweets FROM tweets WHERE id_usuario = :id_user";

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_user', $this->__get('id_user'));
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
}
