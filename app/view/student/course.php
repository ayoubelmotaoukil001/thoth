<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($course['title']); ?> - Thoth LMS</title>
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
            max-width: 900px;
            margin: 30px auto;
            padding: 0 20px;
        }
        .course-detail {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .course-detail h1 {
            color: #333;
            margin-bottom: 20px;
        }
        .course-detail .description {
            color: #666;
            line-height: 1.8;
            margin-bottom: 30px;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .btn:hover {
            background: #5568d3;
        }
        .btn-secondary {
            background: #6c757d;
        }
        .btn-secondary:hover {
            background: #5a6268;
        }
        .btn-success {
            background: #4CAF50;
        }
        .btn-success:hover {
            background: #45a049;
        }
        .enrolled-badge {
            background: #4CAF50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
            margin-bottom: 20px;
        }
        .actions {
            display: flex;
            gap: 15px;
            margin-top: 30px;
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
        <div class="course-detail">
            <?php if ($isEnrolled): ?>
                <span class="enrolled-badge">✓ Vous êtes inscrit à ce cours</span>
            <?php endif; ?>

            <h1><?php echo htmlspecialchars($course['title']); ?></h1>
            
            <div class="description">
                <?php echo nl2br(htmlspecialchars($course['description'])); ?>
            </div>

            <div class="actions">
                <?php if (!$isEnrolled): ?>
                    <form method="POST" action="/student/course/<?php echo $course['id']; ?>/enroll">
                        <button type="submit" class="btn btn-success">S'inscrire à ce cours</button>
                    </form>
                <?php endif; ?>
                
                <a href="/student/dashboard" class="btn btn-secondary">Retour au dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>