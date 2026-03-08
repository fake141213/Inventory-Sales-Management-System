<?php
// ✅ เชื่อมต่อฐานข้อมูล
include "db.php";

// ❌ เช็คว่า login แล้วหรือไม่
if (!isset($_SESSION['user'])) {
    header("Location: Login.php");
    exit();
}

// 📊 ดึงรายการคำสั่งซื้อทั้งหมด พร้อมชื่อพนักงาน (join ตารางกับ users)
$sql = "
SELECT orders.order_id, orders.order_date, users.name
FROM orders
JOIN users ON orders.user_id = users.user_id
ORDER BY orders.order_date DESC
";
$result = $conn->query($sql);
?>
<!-- 📋 หัวข้อหลัก -->
<h2>รายการคำสั่งซื้อ</h2>

<!-- 📊 ตารางแสดงคำสั่งซื้อ -->
<table border="1">
<tr>
    <!-- 🏷️ หัวตารางแต่ละคอลัมน์ -->
    <th>Order ID</th>
    <th>วันที่</th>
    <th>พนักงาน</th>
</tr>

<!-- ✅ วนลูปแสดงคำสั่งซื้อแต่ละรายการ -->
<?php while ($row = $result->fetch_assoc()): ?>
<tr>
    <!-- 🔑 รหัสคำสั่งซื้อ -->
    <td><?php echo $row['order_id']; ?></td>
    <!-- 📅 วันที่สั่งซื้อ -->
    <td><?php echo $row['order_date']; ?></td>
    <!-- 👤 ชื่อพนักงานที่ทำการสั่ง -->
    <td><?php echo $row['name']; ?></td>
</tr>
<?php endwhile; ?>
</table>
<br>

<!-- ➕ ลิงก์สร้างคำสั่งซื้อใหม่ -->
<a href="orders.php">สร้างคำสั่งซื้อใหม่</a>
<br>

<!-- 🏠 ลิงก์กลับไปหน้า Dashboard -->
<a href="dashboard.php">กลับไปหน้า Dashboard</a>