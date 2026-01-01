<?php
session_start();
include "config/db.php";
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit(); }

// إضافة/تعديل حضور
if(isset($_POST['mark'])){
    $student_id = $_POST['student_id'];
    $status = $_POST['status'];

    // التأكد إن كان يوجد حضور لنفس اليوم
    $check = mysqli_query($conn, "SELECT * FROM attendance WHERE student_id='$student_id' AND date=CURDATE()");
    if(mysqli_num_rows($check) == 0){
        mysqli_query($conn, "INSERT INTO attendance (student_id, date, status) VALUES ('$student_id', CURDATE(), '$status')");
    } else {
        mysqli_query($conn, "UPDATE attendance SET status='$status' WHERE student_id='$student_id' AND date=CURDATE()");
    }
}

// تعديل حضور سابق
if(isset($_POST['edit_attendance'])){
    $id = $_POST['attendance_id'];
    $status = $_POST['status'];
    mysqli_query($conn, "UPDATE attendance SET status='$status' WHERE id='$id'");
}

// حذف حضور
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM attendance WHERE id='$id'");
    header("Location: attendance.php"); exit();
}

// جلب جميع الحضور
$result = mysqli_query($conn, "SELECT a.id, s.full_name, f.title as formation, a.status, a.date 
                               FROM attendance a 
                               JOIN students s ON a.student_id = s.id 
                               LEFT JOIN formations f ON s.formation_id = f.id
                               ORDER BY a.date DESC");

// جلب الطلاب لقائمة الاختيار
$students = mysqli_query($conn, "SELECT * FROM students");
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Suivi de Présence</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body class="page-bg">

<div class="container py-5">
<h1 class="page-title">Suivi de Présence</h1>
<p class="page-subtitle">Gestion de la présence des étudiants</p>

<!-- Formulaire تسجيل الحضور -->
<div class="content-card mb-4">
<h4>Marquer la présence</h4>
<form method="POST" class="row g-3 align-items-center">
    <div class="col-md-6">
        <select name="student_id" class="form-control" required>
            <option value="">-- Choisir l'étudiant --</option>
            <?php while($s = mysqli_fetch_assoc($students)){ ?>
            <option value="<?= $s['id'] ?>"><?= $s['full_name'] ?></option>
            <?php } ?>
        </select>
    </div>
    <div class="col-md-4">
        <select name="status" class="form-control" required>
            <option value="Présent">Présent</option>
            <option value="Absent">Absent</option>
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" name="mark" class="btn btn-primary w-100">Valider</button>
    </div>
</form>
</div>

<!-- جدول الحضور -->
<div class="content-card">
<table class="table table-bordered table-striped">
<thead>
<tr>
    <th>ID</th>
    <th>Étudiant</th>
    <th>Formation</th>
    <th>Status</th>
    <th>Date</th>
    <th>Actions</th>
</tr>
</thead>
<tbody>
<?php while($row = mysqli_fetch_assoc($result)){ ?>
<tr>
    <td><?= $row['id'] ?></td>
    <td><?= $row['full_name'] ?></td>
    <td><?= $row['formation'] ?></td>
    <td class="<?= $row['status']=='Présent'?'text-success fw-bold':'text-danger fw-bold' ?>"><?= $row['status'] ?></td>
    <td><?= $row['date'] ?></td>
    <td>
        <form method="POST" style="display:inline-block;">
            <input type="hidden" name="attendance_id" value="<?= $row['id'] ?>">
            <select name="status" class="form-select form-select-sm d-inline-block" style="width:auto;">
                <option value="Présent" <?= $row['status']=='Présent'?'selected':'' ?>>Présent</option>
                <option value="Absent" <?= $row['status']=='Absent'?'selected':'' ?>>Absent</option>
            </select>
            <button type="submit" name="edit_attendance" class="btn btn-sm btn-info text-white">Modifier</button>
        </form>
        <a href="attendance.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Voulez-vous vraiment supprimer ?')">Supprimer</a>
    </td>
</tr>
<?php } ?>
</tbody>
</table>
</div>

<a href="index.php" class="btn btn-secondary mt-3">Retour au dashboard</a>
</div>
</body>
</html>
