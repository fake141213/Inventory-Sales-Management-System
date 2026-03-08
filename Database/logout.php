<?php
// ⚙️ เริ่ม Session
session_start();

// ✅ เชื่อมต่อฐานข้อมูล
include 'db.php';

// 🗑️ ลบ Session ทั้งหมด (ตัดการเชื่อมต่อผู้ใช้)
session_destroy();

// ↩️ เปลี่ยนกลับไปหน้า Login
header("Location: Login.php");
exit();