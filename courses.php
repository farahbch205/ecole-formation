<?php
session_start();
include "config/db.php";
if(!isset($_SESSION['user_id'])){ header("Location: login.php"); exit(); }

if(isset($_POST['add_formation'])){
    $title=$_POST['title'];
    $description=$_POST['description'];
    $planning=$_POST['planning'];
    mysqli_query($conn,"INSERT INTO formations (title,description,planning) VALUES ('$title','$description','$planning')");
}

if(isset($_POST['edit_formation'])){
    $id=$_POST['formation_id'];
    $title=$_POST['title'];
    $description=$_POST['description'];
    $planning=$_POST['planning'];
    mysqli_query($conn,"UPDATE formations SET title='$title',description='$description',planning='$planning' WHERE id='$id'");
}

if(isset($_GET['delete'])){
    $id=$_GET['delete'];
    mysqli_query($conn,"DELETE FROM formations WHERE id='$id'");
    header("Location: formations.php"); exit();
}

$result=mysqli_query($conn,"SELECT * FROM formations");
$edit_formation=null;
if(isset($_GET['edit'])){
    $id=$_GET['edit'];
    $edit_formation=mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM formations WHERE id='$id'"));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gestion des Formations</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>
<body class="page-bg">
<div class="container py-5">

<h1 class="page-title">Gestion des Formations</h1>
<p class="page-subtitle">Créer et gérer les formations</p>

<div class="content-card mb-4">
<h4><?= $edit_formation ? 'Modifier la formation' : 'Ajouter une formation' ?></h4>
<form method="POST">
<?php if($edit_formation){ ?><input type="hidden" name="formation_id" value="<?= $edit_formation['id'] ?>"><?php } ?>
<input type="text" name="title" class="form-control mb-2" placeholder="Titre" value="<?= $edit_formation['title']??'' ?>" required>
<textarea name="description" class="form-control mb-2" rows="3" placeholder="Description"><?= $edit_formation['description']??'' ?></textarea>
<input type="text" name="planning" class="form-control mb-2" placeholder="Planning" value="<?= $edit_formation['planning']??'' ?>">
<button type="submit" name="<?= $edit_formation?'edit_formation':'add_formation' ?>" class="btn btn-warning text-white"><?= $edit_formation?'Enregistrer':'Ajouter' ?></button>
<?php if($edit_formation){ ?><a href="formations.php" class="btn btn-secondary">Annuler</a><?php } ?>
</form>
</div>

<div class="content-card">
<table class="table table-bordered table-striped">
<thead><tr><th>ID</th><th>Formation</th><th>Description</th><th>Planning</th><th>Actions</th></tr></thead>
<tbody>
<?php while($row=mysqli_fetch_assoc($result)){ ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= $row['title'] ?></td>
<td><?= $row['description'] ?></td>
<td><?= $row['planning'] ?></td>
<td>
<a href="formations.php?edit=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Modifier</a>
<a href="formations.php?delete=<?= $row['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Voulez-vous vraiment supprimer ?')">Supprimer</a>
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

