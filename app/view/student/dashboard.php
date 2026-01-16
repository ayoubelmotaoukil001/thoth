<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Thoth LMS</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
        }
        .navbar {
            background: #667eea;
            color: white;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h2 {
            font-size: 24px;
        }
        .navbar .user-info {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            padding: 8px 15px;
            background: rgba(255,255,255,0.2);
            border-radius: 5px;
            transition: background 0.3s;
        }
        .navbar a:hover {
            background: rgba(255,255,255,0.3);
        }
        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .section {
            background: white;
            padding: 30px;
            border-radius: 10px;
            margin-bottom: 30px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        h3 {
            color: #333;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }
        .courses-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        .course-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 20px;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }
        .course-card h4 {
            color: #667eea;
            margin-bottom: 10px;
        }
        .course-card p {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
            margin-bottom: 15px;
        }
        .course-card .btn {
            display: inline-block;
            padding: 8px 15px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .course-card .btn:hover {
            background: #5568d3;
        }
        .course-card .btn-success {
            background: #4CAF50;
        }
        .course-card .btn-success:hover {
            background: #45a049;
        }
        .enrolled-badge {
            background: #4CAF50;
            color: white;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 12px;
            margin-top: 10px;
            display: inline-block;
        }
        .empty-state {
            text-align: center;
            padding: 40px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="navbar">
        <h2>Thoth LMS</h2>
        <div class="user-info">
            <span>Bienvenue, <?php echo htmlspecialchars($student['name']); ?></span>
            <a href="/logout">Déconnexion</a>
        </div>
    </div>

    <div class="container">
        <div class="section">
            <h3>Mes cours inscrits</h3>
            
            <?php if (empty($enrolledCourses)): ?>
                <div class="empty-state">
                    <p>Vous n'êtes inscrit à aucun cours pour le moment.</p>
                </div>
            <?php else: ?>
                <div class="courses-grid">
                    <?php foreach ($enrolledCourses as $course): ?>
                        <div class="course-card">
                            <h4><?php echo htmlspecialchars($course['title']); ?></h4>
                            <p><?php echo htmlspecialchars($course['description']); ?></p>
                            <span class="enrolled-badge">Inscrit le <?php echo date('d/m/Y', strtotime($course['enrollment_date'])); ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="section">
            <h3>Tous les cours disponibles</h3>
            
            <?php if (empty($allCourses)): ?>
                <div class="empty-state">
                    <p>Aucun cours disponible.</p>
                </div>
            <?php else: ?>
                <div class="courses-grid">
                    <?php foreach ($allCourses as $course): ?>
                        <?php 
                            $isEnrolled = false;
                            foreach ($enrolledCourses as $enrolled) {
                                if ($enrolled['id'] == $course['id']) {
                                    $isEnrolled = true;
                                    break;
                                }
                            }
                        ?>
                        <div class="course-card">
                            <h4><?php echo htmlspecialchars($course['title']); ?></h4>
                            <p><?php echo htmlspecialchars($course['description']); ?></p>
                            
                            <?php if (!$isEnrolled): ?>
                                <form method="POST" action="/student/enroll" style="display: inline;">
                                    <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                                    <button type="submit" class="btn btn-success">S'inscrire</button>
                                </form>
                            <?php else: ?>
                                <span class="enrolled-badge">✓ Inscrit</span>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>