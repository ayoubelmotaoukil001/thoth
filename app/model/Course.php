<?php

namespace app\model;

use app\core\Database;
use PDO;

class Course
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll()
    {
        $stmt = $this->db->query("SELECT * FROM courses ORDER BY title ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM courses WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($title, $description)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO courses (title, description) VALUES (:title, :description)"
        );

        return $stmt->execute([
            ':title' => $title,
            ':description' => $description
        ]);
    }
}