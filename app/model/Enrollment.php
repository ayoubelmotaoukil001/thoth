<?php

namespace app\model;

use app\core\Database;
use PDO;

class Enrollment
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function enroll($studentId, $courseId)
    {
        if ($this->isEnrolled($studentId, $courseId)) {
            return false;
        }

        $stmt = $this->db->prepare(
            "INSERT INTO enrollments (student_id, course_id) VALUES (:student_id, :course_id)"
        );

        return $stmt->execute([
            ':student_id' => $studentId,
            ':course_id' => $courseId
        ]);
    }

    public function isEnrolled($studentId, $courseId)
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM enrollments 
             WHERE student_id = :student_id AND course_id = :course_id"
        );

        $stmt->execute([
            ':student_id' => $studentId,
            ':course_id' => $courseId
        ]);

        return $stmt->fetchColumn() > 0;
    }

    public function getStudentEnrollments($studentId)
    {
        $stmt = $this->db->prepare(
            "SELECT c.*, e.enrollment_date 
             FROM courses c
             INNER JOIN enrollments e ON c.id = e.course_id
             WHERE e.student_id = :student_id
             ORDER BY e.enrollment_date DESC"
        );

        $stmt->execute([':student_id' => $studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}