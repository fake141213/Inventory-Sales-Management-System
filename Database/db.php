<?php
// ========================================
// FILE: db.php - ฐานข้อมูลและการเชื่อมต่อ
// ========================================
// ไฟล์นี้เป็นไฟล์ที่ใช้ดำเนินการเชื่อมต่อกับฐานข้อมูล MySQL
// และตั้งค่าพื้นฐาน เช่น Session, Timezone

// ⚙️ เริ่ม Session (ถ้ายังไม่เริ่มเท่านั้น)
// - Session ใช้เพื่อเก็บข้อมูลผู้ใช้ (user info)
// - เช็คว่า session ยังไม่เริ่มเท่านั้น จึง start เพื่อป้องกันข้อผิดพลาด
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 🌍 ตั้งเขตเวลา (Asia/Bangkok)
// - ทำให้ฟังก์ชัน date() และ time() ใช้เวลาไทย
// - สำคัญสำหรับการบันทึกวันเวลาลงฐานข้อมูลให้ถูกต้อง
date_default_timezone_set('Asia/Bangkok');

// 🗄️ เชื่อมต่อฐานข้อมูล MySQL
// - host: localhost (เซิร์ฟเวอร์ที่เรือ)
// - user: root (ชื่อผู้ใช้ MySQL)
// - password: (ว่าง - ไม่มีรหัสผ่าน)
// - database: inventory_db (ชื่อชาโใบฬหั )
// - คืน object mysqli ฬำหนดการเชื่อมต่อ (เก็บในตัวผูค)
$conn = new mysqli("localhost", "root", "", "inventory_db");

// ❌ เช็คว่าเชื่อมต่อสำเร็จหรือไม่
// - ถ้าใส่ข้อมูล host/user/password ผิด จะเกิด connect_error
// - ใช้ die() เพื่อแสดงข้อความแล้วหยุดโปรแกรม
if ($conn->connect_error) {
    die("เชื่อมต่อ DB ไม่ได้: " . $conn->connect_error);
}

// 🔤 ตั้งชุดอักขระเป็น utf8mb4 (รองรับภาษาไทยและ emoji)
// - utf8mb4 = UTF-8 that supports all Unicode characters including emoji
// - สำคัญ: ต้องตั้งค่านี้เพื่อให้ข้อมูลไทยไม่ขาดหาย
$conn->set_charset("utf8mb4");
?>