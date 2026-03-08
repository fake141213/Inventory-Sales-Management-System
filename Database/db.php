<?php
// ⚙️ เริ่ม Session (ถ้ายังไม่เริ่มเท่านั้น)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 🌍 ตั้งเขตเวลา (Asia/Bangkok)
date_default_timezone_set('Asia/Bangkok');

// 🗄️ เชื่อมต่อฐานข้อมูล MySQL
// - host: localhost
// - user: root
// - password: (ว่าง)
// - database: inventory_db
$conn = new mysqli("localhost", "root", "", "inventory_db");

// ❌ เช็คว่าเชื่อมต่อสำเร็จหรือไม่
if ($conn->connect_error) {
    die("เชื่อมต่อ DB ไม่ได้: " . $conn->connect_error);
}

// 🔤 ตั้งชุดอักขระเป็น utf8mb4 (รองรับภาษาไทยและ emoji)
$conn->set_charset("utf8mb4");
?>