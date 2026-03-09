<?php
// ========================================
// FILE: update_product.php - อัปเดตสินค้า
// ========================================
// ไฟล์นี้รับข้อมูลจากฟอร์มแก้ไขสินค้า (edit_product.php)
// และอัปเดตข้อมูลลงฐานข้อมูล

// ✅ เชื่อมต่อฐานข้อมูล
include "db.php";

// ❌ เช็คว่า login แล้วหรือไม่
if (!isset($_SESSION['user'])) {
    header("Location: Login.php");
    exit();
}

// ✅ ดึงข้อมูลจากฟอร์ม
// - $_POST['id'] = ID ของสินค้าที่จะแก้ไข
// - $_POST['name'] = ชื่อสินค้าใหม่
// - $_POST['price'] = ราคาใหม่
// - $_POST['quantity'] = จำนวนสต็อกใหม่
$id       = (int)($_POST['id'] ?? 0);
// - real_escape_string() = ป้องกัน SQL Injection โดยหลีกเลี่ยงอักขระพิเศษ
$name     = $conn->real_escape_string($_POST['name'] ?? '');
$price    = (float)($_POST['price'] ?? 0);
$quantity = (int)($_POST['quantity'] ?? 0);

// ⚠️ เช็คว่า ID ถูกต้องหรือไม่ (ต้อง > 0)
if ($id <= 0) {
    header("Location: products.php");
    exit();
}

// 💾 SQL เพื่ออัปเดตข้อมูลสินค้า
// - UPDATE = คำสั่งแก้ไขข้อมูล
// - SET = กำหนดค่าใหม่
// - WHERE product_id = $id = เงื่อนไข = แก้ไขเฉพาะสินค้าที่มี product_id เท่ากับ id
$conn->query("
    UPDATE products
    SET product_name = '$name',
        price        = $price,
        stock        = $quantity
    WHERE product_id = $id
");

// ↩️ เปลี่ยนกลับไปหน้า products.php
// - หลังจากอัปเดตสำเร็จ จะกลับไปที่หน้ารายการสินค้า
header("Location: products.php");
exit();
?>