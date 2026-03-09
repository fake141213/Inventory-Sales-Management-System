<?php
// ========================================
// FILE: dashboard.php - หน้าหลักของระบบ (หน้าแรก)
// ========================================
// ไฟล์นี้แสดง Dashboard ภาพรวมของระบบทั้งหมด
// มีการแสดง Stats สถิติต่าง ๆ (จำนวนสินค้า, จำนวนคำสั่ง, สต็อกใกล้หมด, รายได้รวม)
// มี Quick Menu เพื่อเลือกไปยังหน้าต่าง ๆ ได้อย่างรวดเร็ว

// เชื่อมต่อฐานข้อมูล
include 'db.php';

// เช็คว่า login แล้วหรือไม่
if (!isset($_SESSION['user'])) {
    header("Location: Login.php");
    exit();
}

// ดึงชื่อและสิทธิ์ของผู้ใช้จาก session
$name = $_SESSION['user']['name'];
$role = $_SESSION['user']['role'];
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Inventory System</title>
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
        <div class="user-avatar"><?php echo strtoupper(mb_substr($name, 0, 1)); ?></div>
        <div>
            <div class="user-name"><?php echo htmlspecialchars($name); ?></div>
            <div class="user-role">👑 <?php echo htmlspecialchars($role); ?></div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-label">เมนูหลัก</div>
        <a href="dashboard.php" class="nav-item active">
            <span class="nav-icon">🏠</span> Dashboard
        </a>
        <a href="products.php" class="nav-item">
            <span class="nav-icon">📦</span> จัดการสินค้า
        </a>
        <a href="orders.php" class="nav-item">
            <span class="nav-icon">🛒</span> สั่งซื้อสินค้า
        </a>
        <a href="order_list.php" class="nav-item">
            <span class="nav-icon">📋</span> รายการคำสั่งซื้อ
        </a>
        <a href="staff_sales.php" class="nav-item">
            <span class="nav-icon">📊</span> ยอดขายพนักงาน
        </a>
    </nav>

    <div class="sidebar-logout">
        <a href="logout.php" class="logout-btn">
            <span>🚪</span> ออกจากระบบ
        </a>
    </div>
</aside>

<!-- ➡️ เนื้อหาหลัก -->
<div class="dash-main">

    <nav class="topnav">
        <a href="dashboard.php" class="topnav-tab active">🏠 Dashboard</a>
        <a href="products.php" class="topnav-tab">📦 จัดการสินค้า</a>
        <a href="orders.php" class="topnav-tab">🛒 สั่งซื้อสินค้า</a>
        <a href="order_list.php" class="topnav-tab">📋 รายการคำสั่งซื้อ</a>
        <a href="staff_sales.php" class="topnav-tab">📊 ยอดขายพนักงาน</a>
    </nav>

    <div class="dash-content">

        <div class="page-header">
            <div class="page-title">Dashboard</div>
            <div class="welcome-text">ยินดีต้อนรับ, <?php echo htmlspecialchars($name); ?></div>
        </div>

        <!-- 📊 ภาพรวมระบบ -->
        <div class="section">
            <div class="section-header">
                <div>
                    <div class="section-title">ภาพรวมระบบ</div>
                    <div class="section-sub">ข้อมูลสรุปทั้งหมดของระบบ Inventory</div>
                </div>
                <div class="section-datetime">
                    🕐 <?php echo date('d/m/Y H:i'); ?>
                </div>
            </div>

            <div class="stats-grid">
                <?php
                $total_products = 0;
                $total_orders   = 0;
                $low_stock      = 0;
                $total_revenue  = 0;
                try {
                    $r = $conn->query("SELECT COUNT(*) FROM products");
                    if ($r) $total_products = $r->fetch_row()[0];
                    $r = $conn->query("SELECT COUNT(*) FROM orders");
                    if ($r) $total_orders = $r->fetch_row()[0];
                    $r = $conn->query("SELECT COUNT(*) FROM products WHERE stock <= 5");
                    if ($r) $low_stock = $r->fetch_row()[0];
                    $r = $conn->query("SELECT SUM(total) FROM orders");
                    if ($r) $total_revenue = $r->fetch_row()[0] ?? 0;
                } catch (Exception $e) {}
                ?>
                <div class="stat-card blue">
                    <div class="stat-icon">📦</div>
                    <div>
                        <div class="stat-value"><?php echo $total_products; ?></div>
                        <div class="stat-label">สินค้าทั้งหมด</div>
                    </div>
                </div>
                <div class="stat-card green">
                    <div class="stat-icon">🛒</div>
                    <div>
                        <div class="stat-value"><?php echo $total_orders; ?></div>
                        <div class="stat-label">คำสั่งซื้อทั้งหมด</div>
                    </div>
                </div>
                <div class="stat-card red">
                    <div class="stat-icon">⚠️</div>
                    <div>
                        <div class="stat-value"><?php echo $low_stock; ?></div>
                        <div class="stat-label">สินค้าใกล้หมด</div>
                    </div>
                </div>
                <div class="stat-card gold">
                    <div class="stat-icon">💰</div>
                    <div>
                        <div class="stat-value">฿<?php echo number_format($total_revenue); ?></div>
                        <div class="stat-label">รายได้รวม</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ⚡ เมนูลัด -->
        <div class="section">
            <div class="quick-label">⚡ เมนูลัด</div>
            <div class="quick-btns">
                <a href="products.php"   class="quick-btn btn-primary">📦 จัดการสินค้า</a>
                <a href="orders.php"     class="quick-btn btn-success">🛒 สั่งซื้อสินค้า</a>
                <a href="order_list.php" class="quick-btn">📋 รายการคำสั่งซื้อ</a>
                <a href="staff_sales.php" class="quick-btn">📊 ยอดขายพนักงาน</a>
            </div>
        </div>

    </div>
</div>

<script src="../Front/main.js"></script>
</body>
</html>