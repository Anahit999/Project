<?php
require_once 'db.php';
if (session_status() === PHP_SESSION_NONE) session_start();

if (empty($_SESSION['user'])) {
    // not logged in -> redirect to login
    header('Location: login.php');
    exit;
}

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);

    if (!$title) $errors[] = 'Title required.';

    // handle image upload (optional)
    $imageName = null;
    if (!empty($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
        $f = $_FILES['image'];
        if ($f['error'] !== UPLOAD_ERR_OK) {
            $errors[] = 'Image upload error.';
        } else {
            // very simple MIME check
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $f['tmp_name']);
            finfo_close($finfo);
            $allowed = ['image/jpeg'=>'jpg','image/png'=>'png','image/gif'=>'gif'];
            if (!isset($allowed[$mime])) {
                $errors[] = 'Only JPG/PNG/GIF images allowed.';
            } else {
                $ext = $allowed[$mime];
                $imageName = uniqid('img_') . '.' . $ext;
                if (!move_uploaded_file($f['tmp_name'], __DIR__ . '/uploads/' . $imageName)) {
                    $errors[] = 'Failed to move uploaded file.';
                }
            }
        }
    }

    if (!$errors) {
        $stmt = $pdo->prepare("INSERT INTO products (title, description, price, image, created_by) VALUES (:t,:d,:p,:i,:c)");
        $stmt->execute([
            ':t' => $title,
            ':d' => $description,
            ':p' => $price,
            ':i' => $imageName,
            ':c' => $_SESSION['user']['id']
        ]);
        header('Location: index.php');
        exit;
    }
}

include 'header.php';
?>
<div>
  <h2>Add Product</h2>
  <?php if($errors): foreach($errors as $err): ?>
    <div class="alert"><?=htmlspecialchars($err)?></div>
  <?php endforeach; endif; ?>

  <form method="post" enctype="multipart/form-data" class="form">
    <input class="input" name="title" placeholder="Product title" required>
    <textarea class="input" name="description" placeholder="Description" rows="5"></textarea>
    <input class="input" name="price" type="number" step="0.01" placeholder="Price (USD)">
    <label class="small">Image (optional)</label>
    <input class="input" name="image" type="file" accept="image/*">
    <button class="btn primary" type="submit">Add product</button>
  </form>
</div>

<?php include 'footer.php'; ?>
