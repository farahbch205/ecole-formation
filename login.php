<?php
session_start();

/*
  هذا Login تجريبي:
  أي بريد + أي كلمة مرور = دخول مباشر
*/

if (isset($_POST['login'])) {

    // نأخذ البريد فقط للاحتفاظ به في الجلسة
    $email = $_POST['email'];

    // إنشاء Session (كأن المستخدم مسجل)
    $_SESSION['user_id'] = $email;
    $_SESSION['user_email'] = $email;

    // توجيه إلى لوحة التحكم
    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Connexion - École de Formation</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="style.css">
</head>

<body class="login-bg">

<div class="login-container">

    <h1 class="login-title">École de Formation</h1>
    <p class="login-subtitle">Connexion à l’administration</p>

    <form method="POST">
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control"
                   placeholder="admin@email.com" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Mot de passe</label>
            <input type="password" name="password" class="form-control"
                   placeholder="••••••••" required>
        </div>

        <button type="submit" name="login" class="btn btn-primary w-100 login-btn">
            Se connecter
        </button>
    </form>

</div>

</body>
</html>
