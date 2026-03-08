<?php
// ✅ เชื่อมต่อฐานข้อมูล
include "db.php";

// ✅ ดึงข้อมูล name, price, stock จากฟอร์ม
$name = $_POST['name'];
$price = $_POST['price'];
$stock = $_POST['quantity'];

// 💾 SQL เพื่อเพิ่มสินค้าใหม่ลงในตาราง products
$sql = "INSERT INTO products (product_name, price, stock) VALUES ('$name', $price, $stock)";
$conn = $conn->query($sql);

// ↩️ เปลี่ยนไปหน้า products.php เพื่อแสดงรายการสินค้าที่อัปเดต
header("Location: products.php");
exit();