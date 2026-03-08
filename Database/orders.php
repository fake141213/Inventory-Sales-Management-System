<?php
// ✅ เชื่อมต่อฐานข้อมูล
include "db.php";

// ❌ เช็คว่า login แล้วหรือไม่
if (!isset($_SESSION['user'])) {
    header("Location: Login.php");
    exit();
}

// ✅ ดึงสินค้าทั้งหมดที่มีสต็อก (stock > 0)
$result = $conn->query("SELECT * FROM products WHERE stock > 0");
?>
<!DOCTYPE html>
<html lang="th">
<head>
<meta charset="UTF-8">
<title>จัดการคำสั่งซื้อ</title>
</head>
<body>

<!-- 🛒 หัวข้อหลัก -->
<h2>จัดการคำสั่งซื้อ</h2>

<!-- 📤 ฟอร์มสั่งซื้อ ส่งไปหน้า save_order.php -->
<form id="order-form" action="save_order.php" method="post">
  <!-- 🔑 Hidden field เก็บวิธีชำระเงิน (จะเต็มค่าผ่าน JavaScript) -->
  <input type="hidden" name="payment_method" id="payment_method" value="">
  
  <!-- 📊 ตารางแสดงสินค้า -->
  <table border="1">
    <tr>
      <!-- 🏷️ หัวตารางแต่ละคอลัมน์ -->
      <th>สินค้า</th>
      <th>ราคา/ชิ้น</th>
      <th>สต็อก</th>
      <th>จำนวน</th>
      <th>ราคารวม</th>
    </tr>
    
    <!-- ✅ วนลูปแสดงสินค้าแต่ละรายการ -->
    <?php while ($row = $result->fetch_assoc()): ?>
    <tr>
      <!-- 📛 ชื่อสินค้า -->
      <td><?php echo htmlspecialchars($row['product_name']); ?></td>
      <!-- 💵 ราคาต่อชิ้น -->
      <td><?php echo number_format($row['price'], 2); ?></td>
      <!-- 📦 จำนวนสต็อกที่พร้อมขาย -->
      <td><?php echo $row['stock']; ?></td>
      <!-- 🔢 กรอกจำนวนที่ต้องการสั่ง -->
      <td>
        <!-- 📝 Input กรอกปริมาณ (ต้องไม่เกิน stock, เมื่อเปลี่ยนจะคำนวณราคารวม) -->
        <input
          type="number"
          name="quantity[<?php echo $row['product_id']; ?>]"
          min="0"
          max="<?php echo $row['stock']; ?>"
          value="0"
          data-price="<?php echo $row['price']; ?>"
          oninput="calcTotal()"
        >
      </td>
      <!-- 💰 แสดงราคารวมของสินค้านี้ (ราคา × จำนวน) -->
      <td class="row-total">฿0.00</td>
    </tr>
    <?php endwhile; ?>
  </table>
  <br>
  
  <!-- 💰 แสดงราคาที่เกี่ยวข้อง -->
  <!-- 📊 ราคาสินค้ารวม (ยังไม่บวก VAT) -->
  <p>ราคาสินค้ารวม: <span id="subtotal-display">฿0.00</span></p>
  <!-- 📈 ราคา VAT 7% -->
  <p>VAT 7%: <span id="vat-display">฿0.00</span></p>
  <!-- 📌 ยอดรวมทั้งหมด (รวม VAT) -->
  <p>ยอดรวมทั้งหมด: <span id="total-display">฿0.00</span></p>

  <br>
  <!-- 💳 ปุ่มวิธีชำระเงิน -->
  <!-- 📱 ปุ่มชำระผ่าน QR Code -->
  <button type="button" onclick="openPayment('qr')">จ่าย QR Code</button>
  <!-- 💵 ปุ่มชำระผ่านเงินสด -->
  <button type="button" onclick="openPayment('cash')">จ่ายเงินสด</button>
</form>
<br>

<!-- 🏠 ลิงก์กลับไปหน้า Dashboard -->
<a href="dashboard.php">กลับหน้า Dashboard</a>

<!-- 📱 Modal QR Code Payment -->
<div id="modal-qr" style="display:none;">
  <hr>
  <h3>สแกน QR Code</h3>
  <!-- 📲 ข้อมูล QR Code (PromptPay) -->
  <p>[ QR Code PromptPay ]</p>
  <!-- 💰 แสดงยอดที่ต้องชำระ -->
  <p>ยอดที่ต้องชำระ: <strong id="qr-amount"></strong></p>
  <!-- ✅ ปุ่มยืนยันการชำระเงิน -->
  <button onclick="confirmPayment()">ยืนยันการชำระเงิน</button>
  <!-- ❌ ปุ่มยกเลิก -->
  <button onclick="closePayment()">ยกเลิก</button>
</div>

<!-- 💵 Modal เงินสด -->
<div id="modal-cash" style="display:none;">
  <hr>
  <h3>ชำระเงินสด</h3>
  <!-- 💰 แสดงยอดที่ต้องชำระ -->
  <p>ยอดที่ต้องชำระ: <strong id="cash-amount"></strong></p>
  <!-- 💵 กรอกจำนวนเงินที่รับมา (จะคำนวณเงินทอนอัตโนมัติ) -->
  <label>รับเงินมา: <input type="number" id="received" placeholder="0.00" oninput="calcChange()"></label>
  <!-- 💸 แสดงผลเงินทอนหรือเงินไม่พอ -->
  <p id="change-result"></p>
  <!-- ✅ ปุ่มยืนยันการชำระเงิน -->
  <button onclick="confirmPayment()">ยืนยันการชำระเงิน</button>
  <!-- ❌ ปุ่มยกเลิก -->
  <button onclick="closePayment()">ยกเลิก</button>
</div>

<!-- 📜 JavaScript สำหรับคำนวณราคา -->
<script>
// 💰 ตัวแปรเก็บยอดรวมทั้งหมด
let grandTotal = 0;

// 📊 ฟังก์ชันคำนวณราคารวม
function calcTotal() {
  const rows = document.querySelectorAll('tr');
  let subtotal = 0;
  rows.forEach(row => {
    // ✅ ค้นหา input ที่เก็บราคา
    const input = row.querySelector('input[data-price]');
    // ✅ ค้นหา cell ที่เก็บราคารวมของแถว
    const cell  = row.querySelector('.row-total');
    if (!input || !cell) return;
    
    // 🔢 ดึงค่าจำนวนและราคา
    const qty      = parseFloat(input.value) || 0;
    const price    = parseFloat(input.dataset.price) || 0;
    
    // 📈 คำนวณราคารวมของสินค้านี้
    const rowTotal = qty * price;
    
    // 📊 อัปเดตแสดงราคารวมของแถว
    cell.textContent = '฿' + rowTotal.toFixed(2);
    
    // ➕ รวมเข้า subtotal
    subtotal += rowTotal;
  });
  
  // 📊 คำนวณ VAT 7%
  const vat  = subtotal * 0.07;
  
  // 💰 คำนวณยอดรวมทั้งหมด
  grandTotal = subtotal + vat;

  // 📌 อัปเดตแสดงในหน้า
  document.getElementById('subtotal-display').textContent = '฿' + subtotal.toFixed(2);
  document.getElementById('vat-display').textContent      = '฿' + vat.toFixed(2);
  document.getElementById('total-display').textContent    = '฿' + grandTotal.toFixed(2);
}

// 💳 ฟังก์ชันเปิด Modal ชำระเงิน
function openPayment(method) {
  // ❌ ถ้ายอดรวม = 0 ให้แสดงข้อความเตือน
  if (grandTotal <= 0) {
    alert('กรุณาเลือกสินค้าก่อนชำระเงิน');
    return;
  }
  
  // ✅ บันทึกวิธีชำระเงินไปยัง hidden field
  document.getElementById('payment_method').value = method;
  
  // ❌ ปิด Modal ทั้งสอง Modal ก่อน
  document.getElementById('modal-qr').style.display   = 'none';
  document.getElementById('modal-cash').style.display = 'none';
  
  // 💰 เตรียมข้อความราคา
  const fmt = '฿' + grandTotal.toFixed(2);
  
  // 📱 ถ้าเลือก QR → เปิด Modal QR Code
  if (method === 'qr') {
    document.getElementById('qr-amount').textContent = fmt;
    document.getElementById('modal-qr').style.display = 'block';
  } else {
    // 💵 ถ้าเลือก Cash → เปิด Modal เงินสด
    document.getElementById('cash-amount').textContent = fmt;
    document.getElementById('modal-cash').style.display = 'block';
    document.getElementById('received').value = '';
    document.getElementById('change-result').textContent = '';
  }
}

// ❌ ฟังก์ชันปิด Modal
function closePayment() {
  document.getElementById('modal-qr').style.display   = 'none';
  document.getElementById('modal-cash').style.display = 'none';
}

// 💸 ฟังก์ชันคำนวณเงินทอน
function calcChange() {
  // ✅ ดึงจำนวนเงินที่รับมา
  const received = parseFloat(document.getElementById('received').value) || 0;
  
  // 💸 คำนวณเงินทอน
  const change   = received - grandTotal;
  const el       = document.getElementById('change-result');
  
  // ⏭️ ถ้ายังไม่ได้กรอกเงิน ให้ไม่แสดงอะไร
  if (received <= 0) { el.textContent = ''; return; }
  
  // ❌ ถ้าเงินไม่พอ → แสดงข้อความขาดเงิน
  if (change < 0) {
    el.textContent = 'เงินไม่พอ ขาดอีก ฿' + Math.abs(change).toFixed(2);
  } else {
    // ✅ ถ้าเงินเพียงพอ → แสดงเงินทอน
    el.textContent = 'เงินทอน ฿' + change.toFixed(2);
  }
}

// ✅ ฟังก์ชันยืนยันการชำระเงิน
function confirmPayment() {
  // 💵 ถ้าเลือกชำระแบบเงินสด
  if (document.getElementById('payment_method').value === 'cash') {
    // ✅ ดึงจำนวนเงินที่รับมา
    const received = parseFloat(document.getElementById('received').value) || 0;
    
    // ❌ เช็คว่าเงินเพียงพอหรือไม่
    if (received < grandTotal) {
      alert('เงินไม่พอครับ!');
      return;
    }
  }
  
  // 📤 ส่งฟอร์มไปยัง save_order.php
  document.getElementById('order-form').submit();
}
</script>
</body>
</html>