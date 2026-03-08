<?php
// ✅ เชื่อมต่อฐานข้อมูล
include "db.php";

// ✅ ดึง id จาก URL (?id=...)
$id = (int)$_GET['id'];

// 🗑️ ลบสินค้าที่มี product_id ตรงกับ id ที่ส่งมา
$conn->query("DELETE FROM products WHERE product_id = $id");

// ↩️ เปลี่ยนกลับไปหน้า products.php
header("Location: products.php");
exit();