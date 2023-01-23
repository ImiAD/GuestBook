<?php

class Comment extends DB
{
    public $id;
    public $userId;
    public $text;
    public $created_at;

    public function save()
    {
        $stmt = $this -> conn -> prepare('INSERT INTO comments (`user_id`, `text`) VALUES (:user_id, :text)');
        $stmt -> execute(['user_id' => $this -> userId, 'text' => $this -> text]);
        $this -> id = $this -> conn -> lastInsertId();
        return $this -> id;
    }

    public function findAll()
    {
        $stmt= $this -> conn -> prepare('SELECT * FROM comments ORDER BY id DESC');
        $stmt -> execute();
        $comments = [];
        while($row = $stmt->fetch(PDO::FETCH_LAZY))
        {
            $comments[] = ['id' => $row -> id,
                           'user_id' => $row -> user_id,
                           'text' => $row -> text,
                           'created_at' => $row -> created_at
            ];
        }
        return $comments;
    }
}