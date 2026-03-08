<!DOCTYPE html>
<html>
<head>
<title>Login</title>
</head>
<body>
<!-- 🔐 หัวข้อหลัก -->
<h2>เข้าสู่ระบบ</h2>

<?php
// ⚙️ เริ่ม Session
session_start();

// ❌ แสดงข้อความแจ้งเตือน (ถ้ามี)
if(isset($_SESSION['error'])){
    echo "<p style='color:red'>" . $_SESSION['error'] . "</p>";
    // 🗑️ ลบข้อความแจ้งเตือน หลังจากแสดง
    unset($_SESSION['error']);
}
?>

<!-- 📤 ฟอร์ม Login ส่งไปหน้า user_login.php -->
<form action="user_login.php" method="post">
    <!-- ✉️ กรอก Email -->
    Email:<input type="email" name="email" required>
    <br><br>
    
    <!-- 🔑 กรอก Password -->
    Password:<input type="password" name="password" required>
    <br><br>
    
    <!-- ✅ ปุ่มเข้าสู่ระบบ -->
    <input type="submit" value="Login">
</form>
</body>
</html>