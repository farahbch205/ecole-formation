<?php
session_start();
include "config/db.php";
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit(); }

// إضافة طالب
if(isset($_POST['add_student'])){
    $name=$_POST['full_name'];
    $email=$_POST['email'];
    $formation=$_POST['formation_id'];
    mysqli_query($conn,"INSERT INTO students (full_name,email,formation_id) VALUES ('$name','$email','$formation')");
}

// تعديل طالب
if(isset($_POST['edit_student'])){
    $id=$_POST['student_id'];
    $name=$_POST['full_name'];
    $email=$_POST['email'];
    $formation=$_POST['formation_id'];
    mysqli_query($conn,"UPDATE students SET full_name='$name', email='$email', formation_id='$formation' WHERE id='$id'");
}

// حذف طالب
if(isset($_GET['delete'])){
    $id=$_GET['delete'];
    mysqli_query($conn,"DELETE FROM students WHERE id='$id'");
    header("Location: students.php");
    exit();
}

// جلب الطلاب
$result=mysqli_query($conn,"SELECT students.*, formations.title FROM students LEFT JOIN formations ON students.formation_id=formations.id");
$formations=mysqli_query($conn,"SELECT * FROM formations");

$edit_student=null;
if(isset($_GET['edit'])){
    $id=$_GET['edit'];
    $edit_student=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM students WHERE id='$id'"));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gestion des Étudiants</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body class="page-bg">
<div class="container py-5">

<h1 class="page-title">Gestion des Étudiants</h1>
<p class="page-subtitle">Ajouter, modifier et gérer les étudiants</p>

<!-- Formulaire Ajouter/Modifier -->
<div class="content-card mb-4">
<h4><?= $edit_student ? 'Modifier un étudiant' : 'Ajouter un étudiant' ?></h4>
<form method="POST">
<?php if($edit_student){ ?>
<input type="hidden" name="student_id" value="<?= $edit_student['id'] ?>">
<?php } ?>
<input type="text" name="full_name" class="form-control mb-2" placeholder="Nom complet" value="<?= $edit_student['full_name']??'' ?>" required>
<input type="email" name="email" class="form-control mb-2" placeholder="Email" value="<?= $edit_student['email']??'' ?>" required>
<select name="formation_id" class="form-control mb-3" required>
<option value="">-- Formation --</option>
<?php while($f=mysqli_fetch_assoc($formations)){ 
    $selected = ($edit_student && $f['id']==$edit_student['formation_id'])?'selected':''; ?>
    <option value="<?= $f['id'] ?>" <?= $selected ?>><?= $f['title'] ?></option>
<?php } ?>
</select>
<button type="submit" name="<?= $edit_student?'edit_student':'add_student' ?>" class="btn btn-primary">
<?= $edit_student?'Enregistrer':'Ajouter' ?></button>
<?php if($edit_student){ ?><a href="students.php" class="btn btn-secondary">Annuler</a><?php } ?>
</form>
</div>

<!-- Tableau -->
<div class="content-card">
<table class="table table-bordered table-striped">
<thead><tr><th>ID</th><th>Nom</th><th>Email</th><th>Formation</th><th>Actions</th></tr></thead>
<tbody>
<?php while($row=mysqli_fetch_assoc($result)){ ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['full_name'] ?></td>
<td><?= $row['email'] ?></td>
<td><?= $row['title'] ?></td>
<td>
<a href="students.php?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
<a href="students.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Voulez-vous vraiment supprimer ?')">Supprimer</a>
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
