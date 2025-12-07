<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<link rel="stylesheet" href="style.css">
<div class="container">
  <div class="header">
    <h1><a href="index.php" style="text-decoration:none;color:inherit;">Jewelry Catalog</a></h1>
    <div class="nav">
      <a href="index.php">Home</a>
      <a href="add_product.php" class="button">Add Product</a>
      <?php if(!empty($_SESSION['user'])): ?>
          <span class="small">Hi, <?=htmlspecialchars($_SESSION['user']['username'])?></span>
          <a href="logout.php" class="btn">Logout</a>
      <?php else: ?>
          <a href="register.php" class="btn">Register</a>
          <a href="login.php" class="btn">Login</a>
      <?php endif; ?>
      <a href="https://www.google.com" target="_blank" class="btn">Google</a>
    </div>
  </div>
