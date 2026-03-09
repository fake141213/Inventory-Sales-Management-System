<!-- 
========================================
ไฟล์: Login.php - หน้าเข้าสู่ระบบ
========================================
ไฟล์นี้แสดงหน้าฟอร์ม Login ของระบบ
ผู้ใช้ต้องป้อน email และ password เพื่อเข้าสู่ระบบ ส่งไปยัง user_login.php
หน้า Login UI ใช้เลย์เอาต์ที่เหมาะสำหรับผู้ใช้
-->
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ — Inventory</title>
    <link rel="stylesheet" href="../Front/style.css">
    <style>
        body.login-page {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background: #f0f2f5;
        }
    </style>
</head>
<body class="login-page">

    <div class="login-box">

        <div class="login-logo">
            <div class="icon">🏪</div>
            <h1>ยินดีต้อนรับ</h1>
            <p>กรุณาเข้าสู่ระบบเพื่อดำเนินการต่อ</p>
        </div>

        <?php
        session_start();
        if (isset($_SESSION['error'])) {
            echo '<div class="login-error">⚠️ ' . htmlspecialchars($_SESSION['error']) . '</div>';
            unset($_SESSION['error']);
        }
        ?>

        <form action="user_login.php" method="post">
            <div class="form-group">
                <label for="email">อีเมล</label>
                <div class="input-icon-wrap">
                    <span class="input-icon">✉️</span>
                    <input class="form-control" type="email" id="email" name="email"
                           placeholder="กรุณากรอกอีเมลพนักงาน" required>
                </div>
            </div>

            <div class="form-group">
                <label for="password">รหัสผ่าน</label>
                <div class="input-icon-wrap">
                    <span class="input-icon">🔑</span>
                    <input class="form-control" type="password" id="password" name="password"
                           placeholder="กรุณากรอกรหัสผ่าน" required>
                </div>
            </div>

            <div class="login-submit-wrap">
                <button type="submit" class="btn btn-primary btn-block btn-lg">
                    🔐 เข้าสู่ระบบ
                </button>
            </div>
        </form>

        <div class="login-footer-note">
            Inventory System &copy; <?php echo date('Y'); ?>
        </div>

    </div>

<script src="../Front/main.js"></script>
</body>
</html>