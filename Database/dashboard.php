<?php
// ✅ เชื่อมต่อฐานข้อมูล และเริ่ม Session
include 'db.php';

// ❌ เช็คว่า login แล้วหรือไม่
if (!isset($_SESSION['user'])) {
    // 🔐 ถ้ายังไม่ login ให้เปลี่ยนไปหน้า Login
    header("Location: Login.php");
    exit();
}
?>
<!-- 🏠 หน้า Dashboard หลัก -->
<h1> Inventory Dashboard</h1>

<!-- 👋 แสดงคำทักทายและชื่อผู้ใช้ -->
<p>ยินดีต้อนรับ, <?php echo $_SESSION['user']['name']; ?>!</p>

<!-- 👤 แสดงบทบาท (Admin หรือ Staff) -->
<p>คุณเข้าสู่ระบบด้วยสิทธิ์: <?php echo $_SESSION['user']['role']; ?></p>

<!-- 📋 เมนูการทำงาน -->
<!-- 📦 ลิงก์ไปหน้าจัดการสินค้า -->
<a href="products.php">จัดการสินค้า</a><br>

<!-- 🛒 ลิงก์ไปหน้าจัดการคำสั่งซื้อ -->
<a href="orders.php">จัดการคำสั่งซื้อ</a><br>

<!-- 🚪 ลิงก์ออกจากระบบ -->
<a href="logout.php">ออกจากระบบ</a><br>

<!-- 📊 ลิงก์ดูยอดขายพนักงาน -->
<a href="staff_sales.php">ยอดขายพนักงาน</a><br>