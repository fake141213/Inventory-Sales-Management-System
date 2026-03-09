<?php
// ========================================
// FILE: order_list.php - รายการคำสั่งซื้อ
// ========================================
// ไฟล์นี้แสดงรายการคำสั่งซื้อทั้งหมด
// ปกติใช้ JOIN เพื่อดึงข้อมูลจากหลายตาราง (orders + users)

// ✅ เชื่อมต่อฐานข้อมูล
include "db.php";

// ❌ เช็คว่า login แล้วหรือไม่
// - ถ้าไม่ login จะส่งกลับไปหน้า Login.php
if (!isset($_SESSION['user'])) {
    header("Location: Login.php");
    exit();
}

// ✅ ดึงข้อมูลผู้ใช้ปัจจุบันจาก session
$user = $_SESSION['user'];

// 📊 ดึงรายการคำสั่งซื้อทั้งหมด พร้อมชื่อพนักงาน (join ตารางกับ users)
// - SELECT = ดึงข้อมูล
// - JOIN users = รวมข้อมูลจากตาราง users
// - ON = เงื่อนไขการรวม (orders.user_id = users.user_id)
// - ORDER BY order_date DESC = เรียงลำดับจากวันที่ใหม่สุดไปเก่าสุด
$sql = "
SELECT orders.order_id, orders.order_date, users.name
FROM orders
JOIN users ON orders.user_id = users.user_id
ORDER BY orders.order_date DESC
";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการคำสั่งซื้อ — Inventory</title>
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
        <div class="user-avatar"><?php echo strtoupper(mb_substr($user['name'], 0, 1)); ?></div>
        <div>
            <div class="user-name"><?php echo htmlspecialchars($user['name']); ?></div>
            <div class="user-role">👑 <?php echo htmlspecialchars($user['role']); ?></div>
        </div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-label">เมนูหลัก</div>
        <a href="dashboard.php"  class="nav-item"><span class="nav-icon">🏠</span> Dashboard</a>
        <a href="products.php"   class="nav-item"><span class="nav-icon">📦</span> จัดการสินค้า</a>
        <a href="orders.php"     class="nav-item"><span class="nav-icon">🛒</span> สั่งซื้อสินค้า</a>
        <a href="order_list.php" class="nav-item active"><span class="nav-icon">📋</span> รายการคำสั่งซื้อ</a>
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
        <a href="products.php"   class="topnav-tab">📦 จัดการสินค้า</a>
        <a href="orders.php"     class="topnav-tab">🛒 สั่งซื้อสินค้า</a>
        <a href="order_list.php" class="topnav-tab active">📋 รายการคำสั่งซื้อ</a>
        <a href="staff_sales.php" class="topnav-tab">📊 ยอดขายพนักงาน</a>
    </nav>

    <div class="page-content">

        <!-- 🗺️ เส้นทางการนำทาง -->
        <div class="breadcrumb">
            <a href="dashboard.php">Dashboard</a>
            <span class="sep">›</span>
            <span class="current">รายการคำสั่งซื้อ</span>
        </div>

        <!-- 📋 ตารางรายการคำสั่งซื้อ -->
        <div class="card">
            <div class="card-header">
                <h2>📋 รายการคำสั่งซื้อทั้งหมด</h2>
                <a href="orders.php" class="btn btn-primary btn-lg">+ สร้างคำสั่งซื้อใหม่</a>
            </div>
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>🔑 Order ID</th>
                            <th>📅 วันที่สั่งซื้อ</th>
                            <th>👤 พนักงาน</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if ($result && $result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="col-id">#<?php echo $row['order_id']; ?></td>
                            <td><?php echo htmlspecialchars($row['order_date']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                        </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr class="empty-row">
                            <td colspan="3">📭 ยังไม่มีคำสั่งซื้อในระบบ</td>
                        </tr>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div><!-- /page-content -->
</div><!-- /dash-main -->

<script src="../Front/main.js"></script>
</body>
</html>