<?php
session_start();
// التأكد من أن المستخدم مسجل الدخول
// إذا لم يكن مسجل، يعاد توجيهه إلى صفحة تسجيل الدخول
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - École de Formation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard-bg">

    <div class="container py-5">

       <!-- العنوان الكبير في الصفحة الرئيسية -->
        <div class="text-center mb-5">
            <h1 class="dashboard-title">École de Formation</h1>
            <p class="dashboard-subtitle">Plateforme de gestion scolaire</p>
        </div>

      
        <!-- المربعات الرئيسية (Cards) للوصول إلى كل قسم -->
        <div class="row g-4">
                       <!-- بطاقة إدارة الطلاب -->
            <div class="col-md-6 col-lg-3">
                <a href="students.php">
                    <div class="dashboard-card">
                        <h3>👩‍🎓 Étudiants</h3>
                        <p>Gestion des étudiants</p>
                    </div>
                </a>
            </div>

              <!-- بطاقة إدارة الأساتذة -->
            <div class="col-md-6 col-lg-3">
                <a href="teachers.php">
                    <div class="dashboard-card">
                        <h3>👨‍🏫 Formateurs</h3>
                        <p>Gestion des formateurs</p>
                    </div>
                </a>
            </div>

                  <!-- بطاقة إدارة الدورات -->
            <div class="col-md-6 col-lg-3">
                <a href="courses.php">
                    <div class="dashboard-card">
                        <h3>📚 Formations</h3>
                        <p>Gestion des formations</p>
                    </div>
                </a>
            </div>
                     <!-- بطاقة متابعة الحضور -->
            <div class="col-md-6 col-lg-3">
                <a href="attendance.php">
                    <div class="dashboard-card">
                        <h3>📊 Présence</h3>
                        <p>Suivi des présences</p>
                    </div>
                </a>
            </div>

        </div>
    </div>

</body>

</html>
