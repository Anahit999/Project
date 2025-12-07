<?php
require_once 'db.php';
if (session_status() === PHP_SESSION_NONE) session_start();
include 'header.php';

// Fetch products
$stmt = $pdo->query("SELECT p.*, u.username FROM products p LEFT JOIN users u ON p.created_by = u.id ORDER BY p.created_at DESC");
$products = $stmt->fetchAll();
?>
<div>
  <h2>Our products</h2>
  <div class="card-grid">
    <?php if(!$products): ?>
      <div class="alert">No products yet. Be the first to <a href="add_product.php">add one</a> (login required).</div>
    <?php endif; ?>

    <?php foreach($products as $p): ?>
      <div class="card">
        <?php if($p['image'] && file_exists('uploads/' . $p['image'])): ?>
          <img src="uploads/<?=htmlspecialchars($p['image'])?>" alt="<?=htmlspecialchars($p['title'])?>">
        <?php else: ?>
          <img src="https://via.placeholder.com/300x150?text=No+image" alt="no image">
        <?php endif; ?>
        <h3><?=htmlspecialchars($p['title'])?></h3>
        <p class="small">By: <?=htmlspecialchars($p['username'] ?? 'Guest')?></p>
        <p><?=nl2br(htmlspecialchars(substr($p['description'],0,150)))?><?php if(strlen($p['description'])>150) echo '...'; ?></p>
        <p><strong>$<?=number_format($p['price'],2)?></strong></p>
      </div>
    <?php endforeach; ?>
  </div>
</div>

<?php include 'footer.php'; ?>
