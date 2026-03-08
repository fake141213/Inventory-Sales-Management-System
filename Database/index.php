<?php
// ⚙️ เริ่ม Session
session_start();

// ✅ ถ้า login แล้ว → ไปหน้า dashboard
if(!isset($_SESSION['user'])){
    // ❌ ถ้ายังไม่ login → ไปหน้า Login
    header("Location: Login.php");
}else{
    // ✅ ถ้า login แล้ว → ไปหน้า dashboard
    header("Location: dashboard.php");
}
exit();