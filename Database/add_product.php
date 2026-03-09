<?php
// ========================================
// FILE: add_product.php - เพิ่มสินค้าใหม่
// ========================================
// ไฟล์นี้รับข้อมูลจากฟอร์มเพิ่มสินค้า (products.php)
// และบันทึกข้อมูลลงฐานข้อมูล

// ✅ เชื่อมต่อฐานข้อมูล
include "db.php";

// ✅ ดึงข้อมูล name, price, stock จากฟอร์มที่ส่งมา
// - $_POST['name'] = ชื่อสินค้า
// - $_POST['price'] = ราคาของสินค้า
// - $_POST['quantity'] = จำนวนสต็อกเริ่มต้น
$name = $_POST['name'];
$price = $_POST['price'];
$stock = $_POST['quantity'];

// 💾 SQL เพื่อเพิ่มสินค้าใหม่ลงในตาราง products
// - INSERT INTO = คำสั่งเพิ่มข้อมูลใหม่
// - product_name, price, stock = ชื่อคอลัมน์ในตาราง
// - VALUES() = ค่าที่จะเพิ่ม
// ⚠️ สำคัญ: ควรใช้ prepared statement เพื่อหลีกเลี่ยง SQL Injection
$sql = "INSERT INTO products (product_name, price, stock) VALUES ('$name', $price, $stock)";
$conn = $conn->query($sql);

// ↩️ เปลี่ยนไปหน้า products.php เพื่อแสดงรายการสินค้าที่อัปเดต
// - หลังจากเพิ่มสินค้าสำเร็จ จะกลับไปที่หน้ารายการสินค้า
header("Location: products.php");
exit();