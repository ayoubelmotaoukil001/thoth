<?php

namespace app\model;

use app\core\Database;
use PDO;

class Student
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function create($name, $email, $password)
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->db->prepare(
            "INSERT INTO students (name, email, password) VALUES (:name, :email, :password)"
        );

        return $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $hashedPassword
        ]);
    }

    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM students WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function authenticate($email, $password)
    {
        $student = $this->findByEmail($email);

        if ($student && password_verify($password, $student['password'])) {
            return $student;
        }

        return false;
    }

    public function getCourses($studentId)
    {
        $stmt = $this->db->prepare(
            "SELECT c.* FROM courses c
             INNER JOIN enrollments e ON c.id = e.course_id
             WHERE e.student_id = :student_id"
        );
        
        $stmt->execute([':student_id' => $studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function emailExists($email)
    {
        return $this->findByEmail($email) !== false;
    }
}