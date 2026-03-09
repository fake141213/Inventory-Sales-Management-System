<?php
// ========================================
// FILE: user_login.php - ตัวจัดการ Login
// ========================================
// ไฟล์นี้รับข้อมูล email และ password จากฟอร์ม Login.php
// จากนั้นตรวจสอบว่าตรงกับบัญชีที่กำหนดหรือไม่
// ถ้าถูกจะบันทึก Session และเปลี่ยนไปหน้า Dashboard

// ✅ เชื่อมต่อฐานข้อมูล
// - include "db.php" = โหลดไฟล์ db.php เพื่อใช้ Object ของการเชื่อมต่อ ($conn)
include "db.php";

// ✅ ดึงค่า email และ password จากข้อมูลที่ส่งมา (ถ้าไม่มีจะเป็นค่าว่าง)
// - $_POST['email'] = ค่าที่ส่งมาจากแบบฟอร์ม Login (name="email")
// - ?? '' = ถ้าไม่มีค่า ให้ใช้ค่าว่าง
$username = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// 🔑 ข้อมูลผู้ใช้ (Admin และ Staff) - เก็บใน Array
// ⚠️ สำคัญ: ในโปรแกรมจริง ควรเก็บใน Database + ใช้ password_hash() สำหรับความปลอดภัย
// - ตอนนี้เก็บใน Code เพื่อให้ง่ายในการทดสอบ (ไม่ปลอดภัย!)
$users = [
    "admin" => [
        "email" => "admin@gmail.com",
        "password" => "admin123",     // 🔐 รหัสผ่าน (ต้องป้องกันหากใครอ่านโค้ด)
        "role" => "admin",            // 👤 สิทธิ์ = admin (มีสิทธิ์สูง)
        "name" => "Admin James"       // 📝 ชื่อผู้ใช้
    ],
    "umbrella" => [
        "email" => "umbrella@gmail.com",
        "password" => "staff123",
        "role" => "staff",            // 👤 สิทธิ์ = staff (พนักงาน)
        "name" => "Staff umbrella"
    ],
    "lucas" => [
        "email" => "lucas@gmail.com",
        "password" => "staff123",
        "role" => "staff",
        "name" => "Staff lucas"
    ],
];

// ✅ เช็คว่าส่งข้อมูล email และ password มาหรือไม่
// - isset() = ตรวจสอบว่าตัวแปรถูก set หรือไม่
if (isset($_POST['email']) && isset($_POST['password'])) {
    // ✅ วนลูปตรวจสอบว่า email และ password ตรงกับบัญชีใดบัญชีหนึ่งหรือไม่
    // - foreach = วนลูปไปทีละรายการใน array $users
    foreach ($users as $user) {
        // ⚠️ เช็คว่า email และ password ตรงกันหรือไม่
        if ($username === $user['email'] && $password === $user['password']) {
            // ✅ login สำเร็จ → บันทึกข้อมูลใน SESSION
            // - SESSION = ข้อมูลที่เก็บไว้บนเซิร์ฟเวอร์ สำหรับผู้ใช้คนนั้น
            $_SESSION['user'] = [
                'email' => $user['email'],
                'role' => $user['role'],
                'name' => $user['name']
            ];
            // ✅ เปลี่ยนไปหน้า Dashboard (หน้าหลัก)
            header("Location: dashboard.php");
            exit();
        }
    }
    // ❌ login ไม่ผ่าน → email หรือ password ไม่ตรงกับบัญชีใด
    // - บันทึกข้อความแสดงข้อผิดพลาดใน SESSION
    $_SESSION['error'] = "Email หรือ Password ไม่ถูกต้อง";
    // ❌ เปลี่ยนกลับไปหน้า Login
    header("Location: Login.php");
    exit();
}
?>