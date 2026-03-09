<?php
// ========================================
// FILE: orders.php - ฟอร์มสั่งซื้อสินค้า
// ========================================
// ไฟล์นี้ใช้สำหรับสั่งซื้อสินค้า และคำนวณยอดรวม + VAT 7%
// มีการคำนวณจำนวนสต็อกและปรับราคาตามจำนวน
// ใช้ Modal Dialog เพื่อเลือกวิธีการชำระเงิน (QR Code หรือ เงินสด)
// ส่งข้อมูลคำสั่งซื้อไปยัง save_order.php

// เชื่อมต่อฐานข้อมูล
include "db.php";

// เช็คว่า login แล้วหรือไม่
if (!isset($_SESSION['user'])) { header("Location: Login.php"); exit(); }

// ดึงข้อมูลผู้ใช้ปัจจุบันจาก session
$user   = $_SESSION['user'];

// ดึงรายการสินค้าไหล่มวันนี้ (stock > 0 = มีสตัก)
// - WHERE stock > 0 = ดึงเฉพาะ = ไม่ลบสินค้าที่ไม่มีความมืมขมุ
$result = $conn->query("SELECT * FROM products WHERE stock > 0 ORDER BY product_name ASC");
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สั่งซื้อสินค้า — Inventory</title>
    <link rel="stylesheet" href="../Front/style.css">
</head>
<body class="dashboard-body">

<!-- ⬅️ แถบด้านข้าง (Sidebar) -->
<aside class="sidebar">
    <div class="sidebar-brand">
        <div class="brand-icon">📦</div>
        <div class="brand-title">Inventory System</div>
        <div class="brand-sub">Management Dashboard</div>
    </div>
    <div class="sidebar-user">
        <div class="user-avatar"><?php echo strtoupper(mb_substr($user['name'],0,1)); ?></div>
        <div>
            <div class="user-name"><?php echo htmlspecialchars($user['name']); ?></div>
            <div class="user-role">👑 <?php echo htmlspecialchars($user['role']); ?></div>
        </div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-label">เมนูหลัก</div>
        <a href="dashboard.php"   class="nav-item"><span class="nav-icon">🏠</span> Dashboard</a>
        <a href="products.php"    class="nav-item"><span class="nav-icon">📦</span> จัดการสินค้า</a>
        <a href="orders.php"      class="nav-item active"><span class="nav-icon">🛒</span> สั่งซื้อสินค้า</a>
        <a href="order_list.php"  class="nav-item"><span class="nav-icon">📋</span> รายการคำสั่งซื้อ</a>
        <a href="staff_sales.php" class="nav-item"><span class="nav-icon">📊</span> ยอดขายพนักงาน</a>
    </nav>
    <div class="sidebar-logout">
        <a href="logout.php" class="logout-btn"><span>🚪</span> ออกจากระบบ</a>
    </div>
</aside>

<!-- ➡️ เนื้อหาหลัก -->
<div class="dash-main">

    <!-- 🔝 แท็บนำทางด้านบน -->
    <nav class="topnav">
        <a href="dashboard.php"   class="topnav-tab">🏠 Dashboard</a>
        <a href="products.php"    class="topnav-tab">📦 จัดการสินค้า</a>
        <a href="orders.php"      class="topnav-tab active">🛒 สั่งซื้อสินค้า</a>
        <a href="order_list.php"  class="topnav-tab">📋 รายการคำสั่งซื้อ</a>
        <a href="staff_sales.php" class="topnav-tab">📊 ยอดขายพนักงาน</a>
    </nav>

    <div class="page-content">

        <!-- 🗺️ เส้นทางการนำทาง -->
        <div class="breadcrumb">
            <a href="dashboard.php">หน้าแรก</a>
            <span class="sep">›</span>
            <span class="current">สั่งซื้อสินค้า</span>
        </div>

        <!-- 💰 แถบสรุปยอด -->
        <div class="order-summary">
            <div class="sum-item">
                <div class="sum-label">ราคาสินค้า</div>
                <div class="sum-value" id="summary-subtotal">฿0.00</div>
            </div>
            <div class="sum-divider"></div>
            <div class="sum-item">
                <div class="sum-label">VAT 7%</div>
                <div class="sum-value" id="summary-vat">฿0.00</div>
            </div>
            <div class="sum-divider"></div>
            <div class="sum-item">
                <div class="sum-label">ยอดรวมทั้งหมด</div>
                <div class="sum-value total" id="summary-total">฿0.00</div>
            </div>
        </div>

        <!-- 📝 ฟอร์มสั่งซื้อ -->
        <form id="order-form" action="save_order.php" method="post">
            <input type="hidden" name="payment_method" id="payment_method" value="">

            <div class="card">
                <div class="card-header">
                    <h2>🛒 เลือกสินค้าที่ต้องการซื้อ</h2>
                </div>
                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>สินค้า</th>
                                <th>ราคา/ชิ้น</th>
                                <th>สต็อก</th>
                                <th>จำนวน</th>
                                <th>ราคารวม</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr data-row>
                                <td class="col-name"><?php echo htmlspecialchars($row['product_name']); ?></td>
                                <td class="col-price"><?php echo number_format($row['price'], 2); ?></td>
                                <td class="col-stock <?php echo $row['stock'] <= 5 ? 'stock-low' : 'stock-ok'; ?>">
                                    <?php echo $row['stock']; ?>
                                </td>
                                <td>
                                    <input class="qty-input"
                                           type="number"
                                           name="quantity[<?php echo $row['product_id']; ?>]"
                                           min="0"
                                           max="<?php echo $row['stock']; ?>"
                                           value="0"
                                           data-price="<?php echo $row['price']; ?>"
                                           oninput="calcTotal()">
                                </td>
                                <td class="col-subtotal">฿0.00</td>
                            </tr>
                        <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
                <div class="card-body" style="border-top:1px solid var(--border-soft);">
                    <div class="pay-buttons">
                        <button type="button" class="btn btn-primary btn-lg" onclick="openPayment('qr')">
                            📱 จ่าย QR Code
                        </button>
                        <button type="button" class="btn btn-success btn-lg" onclick="openPayment('cash')">
                            💵 จ่ายเงินสด
                        </button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>

<!-- 📱 หน้าต่างเลือกการชำระ QR Code -->
<div class="modal-overlay" id="modal-qr">
    <div class="modal-box">
        <h3>📱 ชำระผ่าน QR Code</h3>
        <div class="modal-amount">
            <div class="modal-amount-label">ยอดที่ต้องชำระ</div>
            <div class="modal-amount-value" id="qr-amount">฿0.00</div>
        </div>
        <div class="qr-placeholder">
            <div class="qr-icon">▦</div>
            <div>QR Code PromptPay</div>
        </div>
        <div class="modal-actions">
            <button class="btn btn-primary" style="flex:1" onclick="confirmPayment()">✅ ยืนยันการชำระเงิน</button>
            <button class="btn btn-ghost" onclick="closePayment()">ยกเลิก</button>
        </div>
    </div>
</div>

<!-- 💵 หน้าต่างเลือกการชำระเงินสด -->
<div class="modal-overlay" id="modal-cash">
    <div class="modal-box">
        <h3>💵 ชำระด้วยเงินสด</h3>
        <div class="modal-amount">
            <div class="modal-amount-label">ยอดที่ต้องชำระ</div>
            <div class="modal-amount-value" id="cash-amount">฿0.00</div>
        </div>
        <div class="form-group">
            <label>รับเงินมา (บาท)</label>
            <input class="form-control" type="number" id="received"
                   placeholder="0.00" oninput="calcChange()">
        </div>
        <div id="change-result" class="change-result"></div>
        <div class="modal-actions">
            <button class="btn btn-success" style="flex:1" onclick="confirmPayment()">✅ ยืนยันการชำระเงิน</button>
            <button class="btn btn-ghost" onclick="closePayment()">ยกเลิก</button>
        </div>
    </div>
</div>

<script src="../Front/main.js"></script>
</body>
</html>