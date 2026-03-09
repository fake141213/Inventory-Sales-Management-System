<?php
// ========================================
// FILE: edit_product.php - ฟอร์มแก้ไขสินค้า
// ========================================
// ไฟล์นี้แสดงข้อมูลสินค้าตามชื่อ product_id ที่ส่งมา
// ดึงข้อมูลจาก URL (?id=...) จากนั้นแสดงในฟอร์มเพื่อแก้ไขข้อมูล
// ส่งข้อมูลที่แก้ไขไปยัง update_product.php

// เชื่อมต่อฐานข้อมูล
include "db.php";

// เช็คว่า login แล้วหรือไม่
if (!isset($_SESSION['user'])) { header("Location: Login.php"); exit(); }

// ดึงข้อมูลผู้ใช้ปัจจุบันจาก session
$user   = $_SESSION['user'];

// ดึง ID จาก URL (?id=...)
// - $_GET['id'] = ค่าที่่ส่งมาจาก URL
// - (int) = แปลงเป็นตัวเลข เพื่อป้องกัน SQL Injection
$id     = (int)$_GET['id'];

// ดึงข้อมูลสินค้า ทั่่ตรงกับ ID
$result = $conn->query("SELECT * FROM products WHERE product_id = $id");
$row    = $result->fetch_assoc();

// ถ้าไม่มีข้อมูล = ไม่พบสินค้า (เช่น ชื่อผิด) จะมีข้อความ Not Found
// - ใช้ exit() เพื่อหยุดโปรแกรม
if (!$row) { echo "ไม่พบสินค้า"; exit(); }
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขสินค้า — Inventory</title>
    <link rel="stylesheet" href="../Front/style.css">
</head>
<body class="dashboard-body">

<!-- ⬅️ แถบด้านข้าง (Sidebar) -->
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
        <a href="dashboard.php"  class="nav-item"><span class="nav-icon">🏠</span> Dashboard</a>
        <a href="products.php"   class="nav-item active"><span class="nav-icon">📦</span> จัดการสินค้า</a>
        <a href="orders.php"     class="nav-item"><span class="nav-icon">🛒</span> สั่งซื้อสินค้า</a>
        <a href="order_list.php" class="nav-item"><span class="nav-icon">📋</span> รายการคำสั่งซื้อ</a>
        <a href="staff_sales.php" class="nav-item"><span class="nav-icon">📊</span> ยอดขายพนักงาน</a>
    </nav>
    <div class="sidebar-logout">
        <a href="logout.php" class="logout-btn"><span>🚪</span> ออกจากระบบ</a>
    </div>
</aside>

<!-- ➡️ เนื้อหาหลัก -->
<div class="dash-main">

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
            <a href="dashboard.php">หน้าแรก</a>
            <span class="sep">›</span>
            <a href="products.php">จัดการสินค้า</a>
            <span class="sep">›</span>
            <span class="current">แก้ไขสินค้า</span>
        </div>

        <div class="card edit-card">
            <div class="card-header">
                <h2>✏️ แก้ไขสินค้า</h2>
                <span style="font-size:12px;color:var(--ink-faint);">ID #<?php echo $row['product_id']; ?></span>
            </div>
            <div class="card-body">
                <form action="update_product.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $row['product_id']; ?>">

                    <div class="form-group">
                        <label>ชื่อสินค้า</label>
                        <input class="form-control" type="text" name="name"
                               value="<?php echo htmlspecialchars($row['product_name']); ?>" required>
                    </div>

                    <div class="edit-form-grid">
                        <div class="form-group" style="margin-bottom:0">
                            <label>ราคา (บาท)</label>
                            <input class="form-control" type="number" name="price"
                                   step="0.01" min="0" value="<?php echo $row['price']; ?>" required>
                        </div>
                        <div class="form-group" style="margin-bottom:0">
                            <label>จำนวนสต็อก</label>
                            <input class="form-control" type="number" name="quantity"
                                   min="0" value="<?php echo $row['stock']; ?>" required>
                        </div>
                    </div>

                    <div class="edit-form-actions">
                        <button type="submit" class="btn btn-primary">💾 บันทึก</button>
                        <a href="products.php" class="btn btn-ghost">ยกเลิก</a>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<script src="../Front/main.js"></script>
</body>
</html>