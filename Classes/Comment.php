<?php

/**
 * Класс для работы и манипуляции с комментариями пользователей
 */
class Comment extends DB
{
    public $id;
    public $userId;
    public $text;
    public $created_at;

    /**
     * Сохранение комментария в базу данных
     * @return false|string
     */
    public function save()
    {
        $stmt = $this->conn->prepare('INSERT INTO comments (`user_id`, `text`) VALUES (:user_id, :text)');
        $stmt->execute(['user_id' => $this->userId, 'text' => $this->text]);
        $this->id = $this->conn->lastInsertId();

        return $this->id;
    }

    /**
     * Возвращение массива всех комментариев из базы данных
     * @return array
     */
    public function findAll()
    {
        $stmt = $this->conn->prepare('SELECT * FROM comments ORDER BY id DESC');
        $stmt->execute();
        $comments  = [];

        while($row = $stmt->fetch(PDO::FETCH_LAZY)) {
            $comments[] = ['id'         => $row->id,
                           'user_id'    => $row->user_id,
                           'text'       => $row->text,
                           'created_at' => $row->created_at
            ];
        }

        return $comments;
    }

    /**
     * Удаляет все комментарии авторизовавшегося пользователя
     * @return void
     */
    public function clean()
    {
        $stmt = $this->conn->prepare('DELETE FROM comments WHERE user_id  = :user_id');
        $stmt->execute(['user_id' => $this->userId]);
    }
}