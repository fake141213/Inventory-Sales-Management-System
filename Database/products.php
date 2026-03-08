<?php
// ✅ เชื่อมต่อฐานข้อมูล
include "db.php";

// ✅ ดึงข้อมูลสินค้าทั้งหมดจากตาราง products
$result = $conn->query("SELECT * FROM products");
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>จัดการสินค้า</title>
</head>
<body>
    <!-- 📦 หัวข้อหลักของหน้า -->
    <h2>จัดการสินค้า</h2>
    
    <!-- ➕ ส่วนเพิ่มสินค้าใหม่ -->
    <h3>เพิ่มสินค้า</h3>
    <!-- 📤 ฟอร์มส่งข้อมูลไปยัง add_product.php -->
    <form action="add_product.php" method="post">
        <!-- 📝 กรอกชื่อสินค้า (บังคับ required) -->
        <label for="name">ชื่อสินค้า:</label>
        <input type="text" id="name" name="name" required><br><br>
        
        <!-- 💰 กรอกราคา (ทศนิยม 2 ตำแหน่ง) -->
        <label for="price">ราคา:</label>
        <input type="number" id="price" name="price" step="0.01" required><br><br>
        
        <!-- 🔢 กรอกจำนวนสินค้า -->
        <label for="quantity">จำนวน:</label>
        <input type="number" id="quantity" name="quantity" required><br><br>
        
        <!-- ✅ ปุ่มส่งข้อมูล -->
        <input type="submit" value="เพิ่มสินค้า">
    </form>
    <hr>
    
    <!-- 📋 ส่วนแสดงรายการสินค้าทั้งหมด -->
    <h3>รายการสินค้า</h3>
    <!-- 📊 ตารางแสดงข้อมูล -->
    <table border="1">
        <tr>
            <!-- 🏷️ หัวตารางแต่ละคอลัมน์ -->
            <th>ID</th>
            <th>ชื่อสินค้า</th>
            <th>ราคา</th>
            <th>จำนวน</th>
            <th>จัดการ</th>
        </tr>
        <!-- ✅ วนลูปแสดงสินค้าแต่ละรายการ -->
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <!-- 🔑 รหัสสินค้า (Product ID) -->
            <td><?php echo $row['product_id']; ?></td>
            <!-- 📛 ชื่อสินค้า -->
            <td><?php echo $row['product_name']; ?></td>
            <!-- 💵 ราคาสินค้า -->
            <td><?php echo $row['price']; ?></td>
            <!-- 📦 จำนวนคงคลัง (Stock) -->
            <td><?php echo $row['stock']; ?></td>
            <!-- ⚙️ ปุ่มแก้ไขและลบ -->
            <td>
                <!-- ✏️ ลิงก์ไปหน้าแก้ไขสินค้า (ส่ง id ไปให้) -->
                <a href="edit_product.php?id=<?php echo $row['product_id']; ?>">แก้ไข</a> |
                <!-- 🗑️ ลิงก์ลบสินค้า (มี confirm ถามก่อนลบ) -->
                <a href="delete_product.php?id=<?php echo $row['product_id']; ?>" onclick="return confirm('คุณแน่ใจหรือไม่ว่าต้องการลบสินค้านี้?');">ลบ</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <br>
    
    <!-- 🏠 ปุ่มกลับไปหน้า Dashboard -->
    <a href="dashboard.php">กลับไปหน้า Dashboard</a>
</body>
</html>