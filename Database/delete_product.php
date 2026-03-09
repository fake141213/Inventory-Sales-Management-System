<?php
// ========================================
// FILE: delete_product.php - ลบสินค้า
// ========================================
// ไฟล์นี้ใช้เพื่อลบสินค้าจากฐานข้อมูล
// เมื่อผู้ใช้คลิกปุ่มลบในหน้า products.php จะมาที่ไฟล์นี้

// ✅ เชื่อมต่อฐานข้อมูล
include "db.php";

// ✅ ดึง id จาก URL (?id=...)
// - $_GET['id'] = ค่าที่ส่งมาจาก URL (เช่น ?id=5)
// - (int) = แปลงเป็นตัวเลข เพื่อป้องกัน SQL Injection
$id = (int)$_GET['id'];

// 🗑️ ลบสินค้าที่มี product_id ตรงกับ id ที่ส่งมา
// - DELETE FROM = คำสั่งลบข้อมูล
// - WHERE product_id = $id = เงื่อนไข = ลบเฉพาะสินค้าที่มี product_id เท่ากับ id
$conn->query("DELETE FROM products WHERE product_id = $id");

// ↩️ เปลี่ยนกลับไปหน้า products.php
// - หลังจากลบสำเร็จ จะกลับไปที่หน้ารายการสินค้า
header("Location: products.php");
exit();