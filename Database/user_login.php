<?php
// ✅ เชื่อมต่อฐานข้อมูล
include "db.php";

// ✅ ดึงค่า email และ password จากฟอร์ม (ถ้าไม่มีจะเป็นค่าว่าง)
$username = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// 🔑 ข้อมูลผู้ใช้ (Admin และ Staff)
// ⚠️ สำคัญ: ในโปรแกรมจริง ควรเก็บใน Database แล้วใช้ password_hash()
$users = [
    "admin" => [
        "email" => "admin@gmail.com",
        "password" => "admin123",     // 🔐 รหัสผ่าน (ต้องป้องกันหากใครอ่านโค้ด)
        "role" => "admin",            // 👤 สิทธิ์ = admin
        "name" => "Admin James"       // 📝 ชื่อผู้ใช้
    ],
    "umbrella" => [
        "email" => "umbrella@gmail.com",
        "password" => "staff123",
        "role" => "staff",            // 👤 สิทธิ์ = staff
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
if (isset($_POST['email']) && isset($_POST['password'])) {
    // ✅ วนลูปตรวจสอบว่า email และ password ตรงกับบัญชีใดบัญชีหนึ่งหรือไม่
    foreach ($users as $user) {
        if ($username === $user['email'] && $password === $user['password']) {
            // ✅ login สำเร็จ → บันทึกข้อมูลใน SESSION
            $_SESSION['user'] = [
                'email' => $user['email'],
                'role' => $user['role'],
                'name' => $user['name']
            ];
            // ✅ เปลี่ยนไปหน้า Dashboard
            header("Location: dashboard.php");
            exit();
        }
    }
    // ❌ login ไม่ผ่าน → email หรือ password ไม่ตรงกับบัญชีใด
    $_SESSION['error'] = "Email หรือ Password ไม่ถูกต้อง";
    // ❌ เปลี่ยนกลับไปหน้า Login
    header("Location: Login.php");
    exit();
}
?>