<?php

class Comment extends DB
{
    public $id;
    public $userId;
    public $text;
    public $created_at;
    public int $notesOnPage = 5;

    public function save()
    {
        $stmt = $this->conn->prepare('INSERT INTO comments (`user_id`, `text`) VALUES (:user_id, :text)');
        $stmt->execute(['user_id' => $this -> userId, 'text' => $this -> text]);
        $this->id = $this->conn->lastInsertId();
        return $this->id;
    }

    public function findAll()
    {
        $stmt= $this->conn->prepare('SELECT * FROM comments ORDER BY id DESC');
        $stmt->execute();
        $comments = [];
        while($row = $stmt->fetch(PDO::FETCH_LAZY))
        {
            $comments[] = ['id' => $row->id,
                           'user_id' => $row->user_id,
                           'text' => $row->text,
                           'created_at' => $row->created_at
            ];
        }
        return $comments;
    }

    public function clean()
    {
        $stmt = $this->conn->prepare('DELETE FROM comments WHERE user_id  = :user_id');
        $stmt->execute(['user_id' => $this -> userId]);
    }
    public function lenPage()
    {
        return ceil($this->lenCom()/$this->notesOnPage);
    }
    public function pagin($page)
    {
        $from = ($page-1)*$this->notesOnPage;
        $stmt = $this->conn->prepare('SELECT * FROM comments ORDER BY id DESC LIMIT  $from,$this->notesOnPage');
        $stmt->execute();
        return $stmt->fetchAll();
    }

    private function lenCom()
    {
        $stmt = $this->conn->prepare('SELECT COUNT(*) as count FROM comments');
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

}