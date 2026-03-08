<?php
// ✅ เชื่อมต่อฐานข้อมูล
include "db.php";

// ❌ เช็คว่า login แล้วหรือไม่
if (!isset($_SESSION['user'])) {
    header("Location: Login.php");
    exit();
}

// ✅ ดึง id จาก URL (?id=...)
$id     = (int)$_GET['id'];

// 🔍 ค้นหาสินค้าที่มี product_id = id
$result = $conn->query("SELECT * FROM products WHERE product_id = $id");
$row    = $result->fetch_assoc();

// ❌ ถ้าไม่พบสินค้า ให้แสดงข้อความแล้วออก
if (!$row) {
    echo "ไม่พบสินค้า";
    exit();
}
?>
<!-- ✏️ หน้าแก้ไขสินค้า -->
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>แก้ไขสินค้า</title>
</head>
<body>

<h2>แก้ไขสินค้า</h2>

<!-- 📤 ฟอร์มส่งข้อมูลไปยัง update_product.php -->
<form action="update_product.php" method="post">
  <!-- 🔑 ส่ง product_id แบบ hidden (ไม่แสดงในหน้า) -->
  <input type="hidden" name="id" value="<?php echo $row['product_id']; ?>">

  <!-- 📝 กรอกชื่อสินค้า (ใช้ htmlspecialchars เพื่อป้องกัน XSS) -->
  <label>ชื่อสินค้า:</label><br>
  <input type="text" name="name" value="<?php echo htmlspecialchars($row['product_name']); ?>" required><br><br>

  <!-- 💰 กรอกราคา (ทศนิยม 2 ตำแหน่ง) -->
  <label>ราคา:</label><br>
  <input type="number" name="price" step="0.01" value="<?php echo $row['price']; ?>" required><br><br>

  <!-- 📦 กรอกจำนวนคงคลัง -->
  <label>จำนวนสต็อก:</label><br>
  <input type="number" name="quantity" value="<?php echo $row['stock']; ?>" required><br><br>

  <!-- ✅ ปุ่มบันทึก -->
  <input type="submit" value="บันทึก">
  <!-- ⬅️ ลิงก์ยกเลิกและกลับไปหน้า products.php -->
  <a href="products.php">ยกเลิก</a>
</form>

</body>
</html>