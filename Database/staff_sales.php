<?php
// ✅ เชื่อมต่อฐานข้อมูล
require 'db.php';

// ❌ เช็คว่า login แล้วหรือไม่
if (!isset($_SESSION['user'])) {
    header("Location: Login.php");
    exit();
}

// 👑 เช็คว่าเป็น admin เท่านั้น (staff ไม่สามารถเข้าถึงได้)
if ($_SESSION['user']['role'] !== 'admin') {
    echo "<p style='color:red'>❌ คุณไม่มีสิทธิ์เข้าถึงหน้านี้</p>";
    echo "<a href='dashboard.php'>กลับหน้าหลัก</a>";
    exit();
}

// 📊 ดึงรายชื่อพนักงานทั้งหมด
$sql = "SELECT user_id, name, email FROM users";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>ยอดขายพนักงาน</title>
</head>
<body>

<!-- 👥 หัวข้อหลัก -->
<h2>รายชื่อพนักงาน</h2>

<!-- 📊 ตารางแสดงพนักงาน -->
<table border="1" cellpadding="10">
<tr>
    <!-- 🏷️ หัวตารางแต่ละคอลัมน์ -->
    <th>ID</th>
    <th>ชื่อ</th>
    <th>Email</th>
    <th>ดูยอดขาย</th>
</tr>

<!-- ✅ วนลูปแสดงพนักงานแต่ละคน -->
<?php while($row = $result->fetch_assoc()): ?>
<tr>
    <!-- 🔑 รหัสพนักงาน -->
    <td><?php echo $row['user_id']; ?></td>
    <!-- 📝 ชื่อพนักงาน -->
    <td><?php echo $row['name']; ?></td>
    <!-- ✉️ อีเมลพนักงาน -->
    <td><?php echo $row['email']; ?></td>
    <!-- 📊 ปุ่มไปดูยอดขายของพนักงาน (ส่ง user_id ไปให้) -->
    <td>
        <a href="staff_sales_detail.php?id=<?php echo $row['user_id']; ?>">
            <button>ดูยอดขาย</button>
        </a>
    </td>
</tr>
<?php endwhile; ?>
</table>
<br>

<!-- 🏠 ปุ่มย้อนกลับไปหน้า Dashboard -->
<a href="dashboard.php"><button>ย้อนกลับ</button></a>
</body>
</html>