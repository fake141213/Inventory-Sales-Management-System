<?php
// ========================================
// FILE: index.php - หน้าเปลี่ยนเส้นทาง (Redirect)
// ========================================
// ไฟล์นี้ใช้เป็นจุดเข้าหลัก (entry point)
// ตรวจสอบสถานะการ login และเปลี่ยนไปหน้าที่เหมาะสม

// ⚙️ เริ่ม Session
// - อ่านข้อมูล Session ที่บันทึกไว้ (เช่น $_SESSION['user'])
session_start();

// ✅ ถ้า login แล้ว → ไปหน้า dashboard
// ❌ ถ้ายังไม่ login → ไปหน้า Login
// - isset($_SESSION['user']) = เช็คว่ามีข้อมูลผู้ใช้ใน Session หรือไม่
// - ถ้ามี = login แล้ว → ไปหน้า dashboard
// - ถ้าไม่มี = ยังไม่ login → ไปหน้า Login.php
if(!isset($_SESSION['user'])){
    // ❌ ยังไม่ login → เปลี่ยนไปหน้า Login
    header("Location: Login.php");
}else{
    // ✅ Login แล้ว → เปลี่ยนไปหน้า Dashboard
    header("Location: dashboard.php");
}
// exit() = หยุดการทำงานของโปรแกรมทันที
exit();