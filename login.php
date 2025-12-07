<?php
require_once 'db.php';
if (session_status() === PHP_SESSION_NONE) session_start();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernameOrEmail = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$usernameOrEmail || !$password) {
        $errors[] = 'Fill both fields.';
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :ue OR email = :ue LIMIT 1");
        $stmt->execute([':ue'=>$usernameOrEmail]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            // login success
            $_SESSION['user'] = ['id'=>$user['id'], 'username'=>$user['username'], 'email'=>$user['email']];
            header('Location: index.php');
            exit;
        } else {
            $errors[] = 'Invalid credentials.';
        }
    }
}

include 'header.php';
?>
<div>
  <h2>Login</h2>
  <?php if($errors): foreach($errors as $err): ?>
    <div class="alert"><?=htmlspecialchars($err)?></div>
  <?php endforeach; endif; ?>

  <form method="post" class="form">
    <input class="input" name="username" placeholder="Username or email" required>
    <input class="input" name="password" type="password" placeholder="Password" required>
    <button class="btn primary" type="submit">Login</button>
  </form>
</div>
<?php include 'footer.php'; ?>
