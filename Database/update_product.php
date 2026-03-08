<?php
// ✅ เชื่อมต่อฐานข้อมูล
include "db.php";

// ✅ เช็คว่า login แล้วหรือไม่
if (!isset($_SESSION['user'])) {
    header("Location: Login.php");
    exit();
}

// ✅ ดึงข้อมูลจากฟอร์ม
$id       = (int)$_POST['id'];                              // 🔑 รหัสสินค้า
$name     = $conn->real_escape_string($_POST['name']);      // 📝 ชื่อสินค้า (ป้องกัน SQL Injection)
$price    = (float)$_POST['price'];                         // 💰 ราคา
$quantity = (int)$_POST['quantity'];                        // 📦 จำนวนคงคลัง

// ✏️ อัปเดตข้อมูลสินค้า
$conn->query("
    UPDATE products
    SET product_name = '$name',
        price        = $price,
        stock        = $quantity
    WHERE product_id = $id
");

// ↩️ เปลี่ยนไปหน้า products.php เพื่อแสดงรายการสินค้าที่อัปเดต
header("Location: products.php");
exit();
?>