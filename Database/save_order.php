<?php
// ========================================
// FILE: save_order.php - บันทึกคำสั่งซื้อ
// ========================================
// ไฟล์นี้รับข้อมูลสินค้าจากฟอร์ม orders.php
// สร้าง order ใหม่ และบันทึกรายการสินค้าลงฐานข้อมูล

// ✅ เชื่อมต่อฐานข้อมูล
include "db.php";

// ❌ เช็คว่า login แล้วหรือไม่
if (!isset($_SESSION['user'])) {
    header("Location: Login.php");
    exit();
}

// 🔑 สร้าง Mapping Email → User ID (เป็นกัน session เก็บ email ไม่มี user_id)
// - session ไม่สามารถเก็บ user_id โดยตรง ต้องเปรียง email ไปที่ user_id
$user_map = [
    "admin@gmail.com"    => 1,    // admin ID = 1
    "umbrella@gmail.com" => 2,    // umbrella ID = 2
    "lucas@gmail.com"    => 3,    // lucas ID = 3
];

// ✅ ดึง user_id จาก email ใน session
$email  = $_SESSION['user']['email'];
$user_id = $user_map[$email] ?? 1;

// ✅ ดึง payment_method จากฟอร์ม (qr หรือ cash)
// - payment_method = วิธีการชำระเงิน (QR Code หรือ เงินสด)
$payment_method = $_POST['payment_method'] ?? 'cash';

// 📝 สร้าง Order ใหม่ในตาราง orders
// - INSERT INTO orders = เพิ่มคำสั่งลงตาราง orders
// - user_id, payment_method = ข้อมูลที่บันทึก
$conn->query("
    INSERT INTO orders (user_id, payment_method)
    VALUES ($user_id, '$payment_method')
");

// 🔑 ดึง order_id ที่เพิ่งสร้าง
// - insert_id = ID ที่เพิ่งสร้างจากการ INSERT
// - ใช้ order_id นี้เพื่อบันทึกรายการสินค้าเข้า order_items
$order_id = $conn->insert_id;

// 📦 บันทึกรายการสินค้าในคำสั่งนี้
// - $_POST['quantity'] = array ของ quantity โดยมี key = product_id
// - เช่น ['1' => '5', '2' => '10'] = สินค้า ID 1 จำนวน 5 ชิ้น, ID 2 จำนวน 10 ชิ้น
foreach ($_POST['quantity'] as $product_id => $quantity) {
    // ✅ แปลง quantity เป็นตัวเลข
    $quantity = (int)$quantity;
    
    // ⏭️ ถ้า quantity = 0 ให้ข้ามไป (ผู้ใช้ไม่ได้เลือกสินค้านี้)
    if ($quantity <= 0) continue;
    
    // ✅ แปลง product_id เป็นตัวเลข
    $product_id = (int)$product_id;
    
    // 🔍 ดึงราคาสินค้า
    // - SELECT price FROM products = ดึงราคาของสินค้า
    $r       = $conn->query("SELECT price FROM products WHERE product_id = $product_id");
    $product = $r->fetch_assoc();
    $price   = $product['price'];
    
    // 💰 คำนวณ subtotal
    $subtotal = $price * $quantity;

    // 💾 บันทึกรายการเข้า order_items
    $conn->query("
        INSERT INTO order_items (order_id, product_id, qty, unit_price, subtotal)
        VALUES ($order_id, $product_id, $quantity, $price, $subtotal)
    ");

    // 📉 ลดจำนวนสต็อกสินค้า
    $conn->query("
        UPDATE products
        SET stock = stock - $quantity
        WHERE product_id = $product_id
    ");

    // 📊 บันทึก Stock Log (ติดตามการเปลี่ยนแปลงสต็อก)
    $before = $conn->query("SELECT stock FROM products WHERE product_id = $product_id")->fetch_assoc()['stock'];
    $conn->query("
        INSERT INTO stock_logs (product_id, user_id, type, qty_change, qty_before, qty_after, note)
        VALUES ($product_id, $user_id, 'sell', -$quantity, " . ($before + $quantity) . ", $before, 'Order #$order_id')
    ");
}

// 💰 อัปเดต Total เงินในคำสั่งนี้
$conn->query("
    UPDATE orders
    SET total = (
        SELECT COALESCE(SUM(subtotal), 0) FROM order_items WHERE order_id = $order_id
    )
    WHERE order_id = $order_id
");

// ↩️ เปลี่ยนไปหน้า order_list.php เพื่อแสดงรายการคำสั่งซื้อที่อัปเดต
header("Location: order_list.php");
exit();
?>