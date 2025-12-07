<?php
require_once 'db.php';
if (session_status() === PHP_SESSION_NONE) session_start();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password2 = $_POST['password2'] ?? '';

    if (!$username || !$email || !$password) {
        $errors[] = 'Please fill all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Invalid email.';
    } elseif ($password !== $password2) {
        $errors[] = 'Passwords do not match.';
    } else {
        // check uniqueness
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = :u OR email = :e LIMIT 1");
        $stmt->execute([':u'=>$username, ':e'=>$email]);
        if ($stmt->fetch()) {
            $errors[] = 'Username or email already taken.';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (:u,:e,:p)");
            $stmt->execute([':u'=>$username, ':e'=>$email, ':p'=>$hash]);
            $_SESSION['user'] = ['id' => $pdo->lastInsertId(), 'username'=>$username, 'email'=>$email];
            header('Location: index.php');
            exit;
        }
    }
}

include 'header.php';
?>
<div>
  <h2>Register</h2>
  <?php if($errors): foreach($errors as $err): ?>
    <div class="alert"><?=htmlspecialchars($err)?></div>
  <?php endforeach; endif; ?>

  <form method="post" class="form">
    <input class="input" name="username" placeholder="Username" required>
    <input class="input" name="email" type="email" placeholder="Email" required>
    <input class="input" name="password" type="password" placeholder="Password" required>
    <input class="input" name="password2" type="password" placeholder="Confirm password" required>
    <button class="btn primary" type="submit">Register</button>
  </form>
</div>
<?php include 'footer.php'; ?>
