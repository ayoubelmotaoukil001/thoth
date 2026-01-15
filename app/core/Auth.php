<?php

class Auth
{
    public static function login($studentId, $studentData)
    {
        $_SESSION['student_id'] = $studentId;
        $_SESSION['student_name'] = $studentData['name'];
        $_SESSION['student_email'] = $studentData['email'];
    }

    public static function logout()
    {
        session_unset();
        session_destroy();
    }

    public static function check()
    {
        return isset($_SESSION['student_id']);
    }

    public static function requireAuth()
    {
        if (!self::check()) {
            header('Location: /login');
            exit();
        }
    }

    public static function user()
    {
        if (self::check()) {
            return [
                'id' => $_SESSION['student_id'],
                'name' => $_SESSION['student_name'],
                'email' => $_SESSION['student_email']
            ];
        }
        return null;
    }

    public static function id()
    {
        return $_SESSION['student_id'] ?? null;
    }
}