<?php
session_start();
include "config/db.php";
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit(); }

//* AJOUT D'UN FORMATEUR 

if(isset($_POST['add_teacher'])){
    $name=$_POST['name'];
    $email=$_POST['email'];
    $formation=$_POST['formation_id'];
    mysqli_query($conn,"INSERT INTO teachers (full_name,email,formation_id) VALUES ('$name','$email','$formation')");
}
//* MODIFICATION D'UN FORMATEU

if(isset($_POST['edit_teacher'])){
    $id=$_POST['teacher_id'];
    $name=$_POST['name'];
    $email=$_POST['email'];
    $formation=$_POST['formation_id'];
    mysqli_query($conn,"UPDATE teachers SET full_name='$name', email='$email', formation_id='$formation' WHERE id='$id'");
}
//* SUPPRESSION D'UN FORMATEUR
if(isset($_GET['delete'])){
    $id=$_GET['delete'];
    mysqli_query($conn,"DELETE FROM teachers WHERE id='$id'");
    header("Location: teachers.php"); exit();
}

// Liste des formateurs avec leurs formation
$result=mysqli_query($conn,"SELECT teachers.*, formations.title FROM teachers LEFT JOIN formations ON teachers.formation_id=formations.id");
$formations=mysqli_query($conn,"SELECT * FROM formations");
$edit_teacher=null;
 //* MODE ÉDITION
if(isset($_GET['edit'])){
    $id=$_GET['edit'];
    $edit_teacher=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM teachers WHERE id='$id'"));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gestion des Formateurs</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body class="page-bg">
<div class="container py-5">

<h1 class="page-title">Gestion des Formateurs</h1>
<p class="page-subtitle">Ajouter et gérer les formateurs</p>

<div class="content-card mb-4">
<h4><?= $edit_teacher ? 'Modifier un formateur' : 'Ajouter un formateur' ?></h4>
<form method="POST">
<?php if($edit_teacher){ ?><input type="hidden" name="teacher_id" value="<?= $edit_teacher['id'] ?>"><?php } ?>
<input type="text" name="name" class="form-control mb-2" placeholder="Nom complet" value="<?= $edit_teacher['full_name']??'' ?>" required>
<input type="email" name="email" class="form-control mb-2" placeholder="Email" value="<?= $edit_teacher['email']??'' ?>" required>
<select name="formation_id" class="form-control mb-3" required>
<option value="">-- Formation --</option>
<?php while($f=mysqli_fetch_assoc($formations)){
    $selected = ($edit_teacher && $f['id']==$edit_teacher['formation_id'])?'selected':''; ?>
    <option value="<?= $f['id'] ?>" <?= $selected ?>><?= $f['title'] ?></option>
<?php } ?>
</select>
<button type="submit" name="<?= $edit_teacher?'edit_teacher':'add_teacher' ?>" class="btn btn-primary"><?= $edit_teacher?'Enregistrer':'Ajouter' ?></button>
<?php if($edit_teacher){ ?><a href="teachers.php" class="btn btn-secondary">Annuler</a><?php } ?>
</form>
</div>

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
<a href="teachers.php?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
<a href="teachers.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Voulez-vous vraiment supprimer ?')">Supprimer</a>
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

