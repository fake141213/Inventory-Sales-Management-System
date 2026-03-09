<?php
// ========================================
// FILE: staff_sales_detail.php - รายละเอียดยอดขายพนักงาน
// ========================================
// ไฟล์นี้แสดงรายละเอียดยอดขายของพนักงานแต่ละคน
// รวมสินค้าที่ขายทั้งหมด วันที่ขาย และจำนวนเงินรวม
// ⚠️ สำคัญ: เฉพาะ admin เท่านั้นที่สามารถเข้าได้

// ✅ เชื่อมต่อฐานข้อมูล
require 'db.php';

// ❌ เช็คว่า login แล้วหรือไม่
if (!isset($_SESSION['user'])) {
    header("Location: Login.php");
    exit();
}

// ✅ ดึงข้อมูลผู้ใช้ปัจจุบันจาก session
$user = $_SESSION['user'];

// 👑 เช็คว่าเป็น admin เท่านั้น
if ($user['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit();
}

// ✅ ดึง user_id จาก URL (?id=...) แบบปลอดภัย
// - $_GET['id'] = ค่าที่ส่งมาจาก URL
// - intval() = แปลงเป็นตัวเลข เพื่อป้องกัน SQL Injection
// - ?? 0 = ถ้าไม่มีค่า ให้ใช้ 0
$user_id = intval($_GET['id'] ?? 0);
if ($user_id <= 0) {
    header("Location: staff_sales.php");
    exit();
}

// 📊 SQL ดึงข้อมูลยอดขายของพนักงาน
// - JOIN หลายตาราง: orders + users + order_items + products
// - แสดงข้อมูล: ชื่อพนักงาน, ชื่อสินค้า, จำนวน, ราคา, subtotal, วันที่
// - WHERE users.user_id = $user_id = เงื่อนไข = ดึงเฉพาะข้อมูลพนักงานที่ระบุ
// - ORDER BY orders.order_date DESC = เรียงลำดับจากวันที่ใหม่สุด
$sql = "
SELECT
    users.name,
    products.product_name,
    order_items.qty,
    order_items.unit_price,
    order_items.subtotal,
    orders.order_date
FROM orders
JOIN users      ON orders.user_id          = users.user_id
JOIN order_items ON orders.order_id        = order_items.order_id
JOIN products   ON order_items.product_id  = products.product_id
WHERE users.user_id = $user_id
ORDER BY orders.order_date DESC
";
$result = $conn->query($sql);

// ดึงชื่อพนักงาน และคำนวณยอดรวมทั้งหมด
$staff_name = '';
$grand_total = 0;
$rows = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        // ⚠️ ดึงชื่อพนักงานเพียงครั้งแรก
        if (!$staff_name) $staff_name = $row['name'];
        $grand_total += $row['subtotal'];
        $rows[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ยอดขาย<?php echo $staff_name ? ' — ' . htmlspecialchars($staff_name) : ''; ?> — Inventory</title>
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
        <a href="dashboard.php"   class="nav-item"><span class="nav-icon">🏠</span> Dashboard</a>
        <a href="products.php"    class="nav-item"><span class="nav-icon">📦</span> จัดการสินค้า</a>
        <a href="orders.php"      class="nav-item"><span class="nav-icon">🛒</span> สั่งซื้อสินค้า</a>
        <a href="order_list.php"  class="nav-item"><span class="nav-icon">📋</span> รายการคำสั่งซื้อ</a>
        <a href="staff_sales.php" class="nav-item active"><span class="nav-icon">📊</span> ยอดขายพนักงาน</a>
    </nav>
    <div class="sidebar-logout">
        <a href="logout.php" class="logout-btn"><span>🚪</span> ออกจากระบบ</a>
    </div>
</aside>

<!-- ➡️ เนื้อหาหลัก -->
<div class="dash-main">

    <!-- 🔝 แท็บนำทางด้านบน -->
    <nav class="topnav">
        <a href="dashboard.php"   class="topnav-tab">🏠 Dashboard</a>
        <a href="products.php"    class="topnav-tab">📦 จัดการสินค้า</a>
        <a href="orders.php"      class="topnav-tab">🛒 สั่งซื้อสินค้า</a>
        <a href="order_list.php"  class="topnav-tab">📋 รายการคำสั่งซื้อ</a>
        <a href="staff_sales.php" class="topnav-tab active">📊 ยอดขายพนักงาน</a>
    </nav>

    <div class="page-content">

        <!-- 🗺️ เส้นทางการนำทาง -->
        <div class="breadcrumb">
            <a href="dashboard.php">Dashboard</a>
            <span class="sep">›</span>
            <a href="staff_sales.php">ยอดขายพนักงาน</a>
            <span class="sep">›</span>
            <span class="current"><?php echo $staff_name ? htmlspecialchars($staff_name) : 'รายละเอียด'; ?></span>
        </div>

        <!-- 📊 แถบสรุปข้อมูล -->
        <?php if ($staff_name): ?>
        <div class="order-summary" style="margin-bottom:22px;">
            <div class="sum-item">
                <div class="sum-label">👤 พนักงาน</div>
                <div class="sum-value"><?php echo htmlspecialchars($staff_name); ?></div>
            </div>
            <div class="sum-divider"></div>
            <div class="sum-item">
                <div class="sum-label">📦 รายการทั้งหมด</div>
                <div class="sum-value"><?php echo count($rows); ?> รายการ</div>
            </div>
            <div class="sum-divider"></div>
            <div class="sum-item">
                <div class="sum-label">💰 ยอดขายรวม</div>
                <div class="sum-value total">฿<?php echo number_format($grand_total, 2); ?></div>
            </div>
        </div>
        <?php endif; ?>

        <!-- 📋 ตารางรายละเอียดการขาย -->
        <div class="card">
            <div class="card-header">
                <h2>📋 รายละเอียดการขาย</h2>
                <a href="staff_sales.php" class="btn btn-ghost btn-sm">ย้อนกลับ</a>
            </div>
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>📛 สินค้า</th>
                            <th>🔢 จำนวน</th>
                            <th>💵 ราคา/หน่วย</th>
                            <th>💰 รวม</th>
                            <th>📅 วันที่ขาย</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($rows)): ?>
                        <?php foreach ($rows as $row): ?>
                        <tr>
                            <td class="col-name"><?php echo htmlspecialchars($row['product_name']); ?></td>
                            <td><?php echo $row['qty']; ?></td>
                            <td class="col-price"><?php echo number_format($row['unit_price'], 2); ?></td>
                            <td class="col-price"><?php echo number_format($row['subtotal'], 2); ?></td>
                            <td><?php echo htmlspecialchars($row['order_date']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr class="empty-row">
                            <td colspan="5">📭 ยังไม่มีรายการขายของพนักงานคนนี้</td>
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