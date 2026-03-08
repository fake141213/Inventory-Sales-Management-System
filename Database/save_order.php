<?php
// ✅ เชื่อมต่อฐานข้อมูล
include "db.php";

// ❌ เช็คว่า login แล้วหรือไม่
if (!isset($_SESSION['user'])) {
    header("Location: Login.php");
    exit();
}

// 🔑 Mapping Email → User ID (เพราะ session เก็บเฉพาะ email)
$user_map = [
    "admin@gmail.com"    => 1,
    "umbrella@gmail.com" => 2,
    "lucas@gmail.com"    => 3,
];

// ✅ ดึง user_id จาก email ใน session
$email  = $_SESSION['user']['email'];
$user_id = $user_map[$email] ?? 1;

// ✅ ดึง payment_method จากฟอร์ม (qr หรือ cash)
$payment_method = $_POST['payment_method'] ?? 'cash';

// 📝 สร้าง Order ใหม่ในตาราง orders
$conn->query("
    INSERT INTO orders (user_id, payment_method)
    VALUES ($user_id, '$payment_method')
");

// 🔑 ดึง order_id ที่เพิ่งสร้าง
$order_id = $conn->insert_id;

// 📦 บันทึกรายการสินค้าในคำสั่งนี้
foreach ($_POST['quantity'] as $product_id => $quantity) {
    // ✅ แปลง quantity เป็นตัวเลข
    $quantity = (int)$quantity;
    
    // ⏭️ ถ้า quantity = 0 ให้ข้ามไป
    if ($quantity <= 0) continue;
    
    // ✅ แปลง product_id เป็นตัวเลข
    $product_id = (int)$product_id;
    
    // 🔍 ดึงราคาสินค้า
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

// ↩️ เปลี่ยนไปหน้า orders_list.php เพื่อแสดงรายการคำสั่งซื้อที่อัปเดต
header("Location: orders_list.php");
exit();
?>