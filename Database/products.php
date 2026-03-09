<?php
// ========================================
// FILE: products.php - จัดการสินค้า
// ========================================
// ไฟล์นี้รองรับการดำเนินการ CRUD (Create, Read, Update, Delete)
// มี Form เพื่อเพิ่มสินค้าใหม่ และ Table แสดงรายละเอียดสินค้าทั้งหมด
// ผู้ใช้สามารถคลิกปุ่ม Edit และ Delete เพื่อแก้ไขหรือลบสินค้า

// เชื่อมต่อฐานข้อมูล
include "db.php";

// เช็คว่า login แล้วหรือไม่
if (!isset($_SESSION['user'])) { header("Location: Login.php"); exit(); }

// ดึงข้อมูลผู้ใช้ปัจจุบันจาก session
$user   = $_SESSION['user'];

// ดึงรายการสินค้าทั้งหมด เรียงตามชื่อ
// - SELECT * FROM products = ดึงข้อมูลทั้งหมด
// - ORDER BY product_name ASC = เรียงตามชื่อจาก A ไป Z
$result = $conn->query("SELECT * FROM products ORDER BY product_name ASC");
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการสินค้า — Inventory</title>
    <link rel="stylesheet" href="../Front/style.css">
</head>
<body class="dashboard-body">

<!-- ══ SIDEBAR ══ -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">📦</div>
        <div class="brand-title">Inventory System</div>
        <div class="brand-sub">Management Dashboard</div>
    </div>
    <div class="sidebar-user">
        <div class="user-avatar"><?php echo strtoupper(mb_substr($user['name'],0,1)); ?></div>
        <div>
            <div class="user-name"><?php echo htmlspecialchars($user['name']); ?></div>
            <div class="user-role">👑 <?php echo htmlspecialchars($user['role']); ?></div>
        </div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-label">เมนูหลัก</div>
        <a href="dashboard.php" class="nav-item"><span class="nav-icon">🏠</span> Dashboard</a>
        <a href="products.php"  class="nav-item active"><span class="nav-icon">📦</span> จัดการสินค้า</a>
        <a href="orders.php"    class="nav-item"><span class="nav-icon">🛒</span> สั่งซื้อสินค้า</a>
        <a href="order_list.php" class="nav-item"><span class="nav-icon">📋</span> รายการคำสั่งซื้อ</a>
        <a href="staff_sales.php" class="nav-item"><span class="nav-icon">📊</span> ยอดขายพนักงาน</a>
    </nav>
    <div class="sidebar-logout">
        <a href="logout.php" class="logout-btn"><span>🚪</span> ออกจากระบบ</a>
    </div>
</aside>

<!-- ➡️ เนื้อหาหลัก -->
<div class="dash-main">

    <!-- 🔝 แท็บนำทางด้านบน -->
    <nav class="topnav">
        <a href="dashboard.php"  class="topnav-tab">🏠 Dashboard</a>
        <a href="products.php"   class="topnav-tab active">📦 จัดการสินค้า</a>
        <a href="orders.php"     class="topnav-tab">🛒 สั่งซื้อสินค้า</a>
        <a href="order_list.php" class="topnav-tab">📋 รายการคำสั่งซื้อ</a>
        <a href="staff_sales.php" class="topnav-tab">📊 ยอดขายพนักงาน</a>
    </nav>

    <div class="page-content">

        <!-- 🗺️ เส้นทางการนำทาง -->
        <div class="breadcrumb">
            <a href="dashboard.php">Dashboard</a>
            <span class="sep">›</span>
            <span class="current">จัดการสินค้า</span>
        </div>

        <!-- ➕ ฟอร์มเพิ่มสินค้า -->
        <div class="card">
            <div class="card-header">
                <h2>➕ เพิ่มสินค้าใหม่</h2>
            </div>
            <div class="card-body">
                <form action="add_product.php" method="post">
                    <div class="product-form-grid">
                        <div class="form-group">
                            <label>ชื่อสินค้า</label>
                            <input class="form-control" type="text" name="name"
                                   placeholder="ระบุชื่อสินค้า" required>
                        </div>
                        <div class="form-group">
                            <label>ราคา (บาท)</label>
                            <input class="form-control" type="number" name="price"
                                   step="0.01" min="0" placeholder="0.00" required>
                        </div>
                        <div class="form-group">
                            <label>จำนวนสต็อก</label>
                            <input class="form-control" type="number" name="quantity"
                                   min="0" placeholder="0" required>
                        </div>
                        <div class="form-group">
                            <label>&nbsp;</label>
                            <button type="submit" class="btn btn-primary">➕ เพิ่ม</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- 📦 ตารางรายการสินค้า -->
        <div class="card">
            <div class="card-header">
                <h2>📦 รายการสินค้าทั้งหมด</h2>
                <div class="search-wrap">
                    <span class="search-icon">🔍</span>
                    <input class="form-control" type="text" id="search-products"
                           placeholder="ค้นหาสินค้า...">
                </div>
            </div>
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ชื่อสินค้า</th>
                            <th>ราคา</th>
                            <th>สต็อก</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody id="products-tbody">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr class="product-row">
                            <td class="col-id">#<?php echo $row['product_id']; ?></td>
                            <td class="col-name"><?php echo htmlspecialchars($row['product_name']); ?></td>
                            <td class="col-price"><?php echo number_format($row['price'], 2); ?></td>
                            <td class="col-stock"><?php echo $row['stock']; ?></td>
                            <td>
                                <div class="col-actions">
                                    <a href="edit_product.php?id=<?php echo $row['product_id']; ?>"
                                       class="btn btn-ghost btn-sm">✏️ แก้ไข</a>
                                    <a href="delete_product.php?id=<?php echo $row['product_id']; ?>"
                                       class="btn btn-danger-soft btn-sm btn-delete-product"
                                       data-name="<?php echo htmlspecialchars($row['product_name']); ?>">
                                       🗑️ ลบ
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div><!-- /page-content -->
</div><!-- /dash-main -->

<script src="../Front/main.js"></script>
</body>
</html>