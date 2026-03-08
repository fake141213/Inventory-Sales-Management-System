<?php
// ✅ เชื่อมต่อฐานข้อมูล
require 'db.php';

// ✅ ดึง user_id จาก URL (?id=...)
$user_id = $_GET['id'];

// 📊 SQL ดึงข้อมูลยอดขายของพนักงาน
// ใช้ JOIN เพื่อรวมข้อมูลจากตาราง orders, users, order_items, products
$sql = "
SELECT 
    users.name,
    products.product_name,
    order_items.qty,
    order_items.unit_price,
    order_items.subtotal,
    orders.order_date
FROM orders
JOIN users ON orders.user_id = users.user_id
JOIN order_items ON orders.order_id = order_items.order_id
JOIN products ON order_items.product_id = products.product_id
WHERE users.user_id = $user_id
ORDER BY orders.order_date DESC
";

// ✅ ส่งคำสั่ง SQL และรับผลลัพธ์
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>ยอดขายพนักงาน</title>
</head>
<body>

<!-- 📊 หัวข้อหลัก -->
<h2>ยอดขายของพนักงาน</h2>

<!-- 📋 ตารางแสดงรายละเอียดการขาย -->
<table border="1" cellpadding="10">
<tr>
    <!-- 🏷️ หัวตารางแต่ละคอลัมน์ -->
    <th>สินค้า</th>
    <th>จำนวน</th>
    <th>ราคา</th>
    <th>รวม</th>
    <th>วันที่ขาย</th>
</tr>

<!-- ✅ วนลูปแสดงรายการขายแต่ละรายการ -->
<?php $total = 0;
while($row = $result->fetch_assoc()){
// ➕ เพิ่มค่า subtotal เข้า total
$total += $row['subtotal'];
?>
<tr>
    <!-- 📛 ชื่อสินค้า -->
    <td><?php echo $row['product_name']; ?></td>
    <!-- 🔢 จำนวนที่ขาย -->
    <td><?php echo $row['qty']; ?></td>
    <!-- 💰 ราคาต่อหน่วย -->
    <td><?php echo $row['unit_price']; ?></td>
    <!-- 💵 ราคารวม (ราคา × จำนวน) -->
    <td><?php echo $row['subtotal']; ?></td>
    <!-- 📅 วันที่ทำการขาย -->
    <td><?php echo $row['order_date']; ?></td>
</tr>
<?php } ?>
</table>

<!-- 💰 แสดงยอดขายรวมทั้งหมด -->
<h3>ยอดขายรวม: <?php echo $total; ?> บาท</h3>
<br>

<!-- 🏠 ปุ่มย้อนกลับไปหน้า staff_sales.php -->
<a href="staff_sales.php">
<button>ย้อนกลับ</button>
</a>
</body>
</html>