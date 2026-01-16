<?php

namespace app\controller;

use app\core\Controller;
use app\core\Auth;
use app\model\Student;
use app\model\Course;
use app\model\Enrollment;

class StudentController extends Controller
{
    private $studentModel;
    private $courseModel;
    private $enrollmentModel;

    public function __construct()
    {
        $this->studentModel = new Student();
        $this->courseModel = new Course();
        $this->enrollmentModel = new Enrollment();
    }

    public function showRegister()
    {
        if (Auth::check()) {
            $this->redirect('/student/dashboard');
        }
        $this->view('student/register');
    }

    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/register');
        }

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        $errors = [];

        if (empty($name)) {
            $errors[] = "Le nom est requis";
        }

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Email invalide";
        }

        if (strlen($password) < 8) {
            $errors[] = "Le mot de passe doit contenir au moins 8 caractères";
        }

        if ($password !== $confirmPassword) {
            $errors[] = "Les mots de passe ne correspondent pas";
        }

        if ($this->studentModel->emailExists($email)) {
            $errors[] = "Cet email est déjà utilisé";
        }

        if (!empty($errors)) {
            $this->view('student/register', ['errors' => $errors]);
            return;
        }

        if ($this->studentModel->create($name, $email, $password)) {
            $this->redirect('/login?registered=1');
        } else {
            $this->view('student/register', ['errors' => ['Erreur lors de l\'inscription']]);
        }
    }

    public function showLogin()
    {
        if (Auth::check()) {
            $this->redirect('/student/dashboard');
        }
        $this->view('student/login');
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        $student = $this->studentModel->authenticate($email, $password);

        if ($student) {
            Auth::login($student['id'], $student);
            $this->redirect('/student/dashboard');
        } else {
            $this->view('student/login', ['error' => 'Email ou mot de passe incorrect']);
        }
    }

    public function dashboard()
    {
        Auth::requireAuth();

        $studentId = Auth::id();
        $enrolledCourses = $this->enrollmentModel->getStudentEnrollments($studentId);
        $allCourses = $this->courseModel->getAll();

        $this->view('student/dashboard', [
            'enrolledCourses' => $enrolledCourses,
            'allCourses' => $allCourses,
            'student' => Auth::user()
        ]);
    }

    public function logout()
    {
        Auth::logout();
        $this->redirect('/login');
    }

    public function enrollCourse()
    {
        Auth::requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/student/dashboard');
        }

        $courseId = $_POST['course_id'] ?? null;

        if (!$courseId) {
            $this->redirect('/student/dashboard');
        }

        $course = $this->courseModel->findById($courseId);
        
        if (!$course) {
            $this->redirect('/student/dashboard');
        }

        $this->enrollmentModel->enroll(Auth::id(), $courseId);
        $this->redirect('/student/dashboard');
    }
}