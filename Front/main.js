/* ============================================================
   main.js — Inventory System
   ธีม: Clean White + Blue
   ============================================================ */

'use strict';

/* ════════════════════════════
   LOGIN — Auto-dismiss error
════════════════════════════ */
document.addEventListener('DOMContentLoaded', () => {
  const err = document.querySelector('.login-error');
  if (err) {
    setTimeout(() => {
      err.style.transition = 'opacity .4s, max-height .4s, padding .4s, margin .4s';
      err.style.opacity    = '0';
      err.style.maxHeight  = '0';
      err.style.overflow   = 'hidden';
      err.style.padding    = '0';
      err.style.margin     = '0';
      setTimeout(() => err.remove(), 420);
    }, 4000);
  }
});
/* ════════════════════════════
   PRODUCTS PAGE
════════════════════════════ */
document.addEventListener('DOMContentLoaded', () => {

  // ── Search filter ──
  const searchInput = document.getElementById('search-products');
  const tableBody   = document.getElementById('products-tbody');
  if (searchInput && tableBody) {
    searchInput.addEventListener('input', () => {
      const q = searchInput.value.toLowerCase().trim();
      let visible = 0;
      tableBody.querySelectorAll('tr.product-row').forEach(row => {
        const match = row.textContent.toLowerCase().includes(q);
        row.style.display = match ? '' : 'none';
        if (match) visible++;
      });
      // empty state
      let emptyRow = tableBody.querySelector('.empty-search-row');
      if (visible === 0 && q) {
        if (!emptyRow) {
          emptyRow = document.createElement('tr');
          emptyRow.className = 'empty-search-row';
          emptyRow.innerHTML = `<td colspan="5" style="text-align:center;padding:36px;color:#94a3b8;font-size:13px;">
            🔍 ไม่พบสินค้าที่ตรงกับ "<strong>${q}</strong>"
          </td>`;
          tableBody.appendChild(emptyRow);
        }
      } else if (emptyRow) { emptyRow.remove(); }
    });
    // shortcut: / to focus search
    document.addEventListener('keydown', e => {
      if (e.key === '/' && !['INPUT','TEXTAREA'].includes(document.activeElement.tagName)) {
        e.preventDefault(); searchInput.focus(); searchInput.select();
      }
    });
  }

  // ── Stock badge colour ──
  document.querySelectorAll('.col-stock').forEach(td => {
    const val = parseInt(td.textContent);
    if (!isNaN(val)) {
      if (val <= 5) {
        td.classList.add('stock-low');
        td.innerHTML = `${val} <span style="font-size:11px">⚠️</span>`;
      } else {
        td.classList.add('stock-ok');
      }
    }
  });

  // ── Delete confirm ──
  document.querySelectorAll('.btn-delete-product').forEach(btn => {
    btn.addEventListener('click', e => {
      const name = btn.dataset.name || 'สินค้านี้';
      if (!confirm(`ลบ "${name}" ?\nไม่สามารถย้อนกลับได้`)) e.preventDefault();
    });
  });

  // ── Row stagger animation ──
  document.querySelectorAll('#products-tbody tr.product-row').forEach((tr, i) => {
    tr.style.opacity = '0';
    tr.style.animation = `fadeIn .3s ease ${i * 0.03}s forwards`;
  });

  // ── Format price columns ──
  document.querySelectorAll('.col-price').forEach(td => {
    const v = parseFloat(td.textContent.replace(/[฿,]/g, ''));
    if (!isNaN(v)) td.textContent = '฿' + v.toLocaleString('th-TH', {minimumFractionDigits:2});
  });

});
/* ════════════════════════════
   ORDERS PAGE
════════════════════════════ */
let grandTotal = 0;

function calcTotal() {
  const rows = document.querySelectorAll('tr[data-row]');
  let subtotal = 0;
  rows.forEach(row => {
    const input = row.querySelector('.qty-input');
    const cell  = row.querySelector('.col-subtotal');
    if (!input || !cell) return;
    const qty   = parseFloat(input.value) || 0;
    const price = parseFloat(input.dataset.price) || 0;
    const total = qty * price;
    cell.textContent = '฿' + total.toLocaleString('th-TH', {minimumFractionDigits:2});
    row.classList.toggle('row-selected', qty > 0);
    subtotal += total;
  });
  const vat  = subtotal * 0.07;
  grandTotal = subtotal + vat;
  document.getElementById('summary-subtotal').textContent = '฿' + subtotal.toLocaleString('th-TH', {minimumFractionDigits:2});
  document.getElementById('summary-vat').textContent      = '฿' + vat.toLocaleString('th-TH', {minimumFractionDigits:2});
  document.getElementById('summary-total').textContent    = '฿' + grandTotal.toLocaleString('th-TH', {minimumFractionDigits:2});
}

function openPayment(method) {
  if (grandTotal <= 0) { alert('กรุณาเลือกสินค้าก่อนชำระเงิน'); return; }
  document.getElementById('payment_method').value = method;
  closeAllModals();
  const fmt = '฿' + grandTotal.toLocaleString('th-TH', {minimumFractionDigits:2});
  if (method === 'qr') {
    document.getElementById('qr-amount').textContent = fmt;
    openModal('modal-qr');
  } else {
    document.getElementById('cash-amount').textContent = fmt;
    document.getElementById('received').value = '';
    document.getElementById('change-result').textContent = '';
    document.getElementById('change-result').className = 'change-result';
    openModal('modal-cash');
    setTimeout(() => document.getElementById('received').focus(), 150);
  }
}

function openModal(id) {
  const el = document.getElementById(id);
  if (el) { el.classList.add('open'); document.body.style.overflow = 'hidden'; }
}
function closeAllModals() {
  document.querySelectorAll('.modal-overlay').forEach(m => m.classList.remove('open'));
  document.body.style.overflow = '';
}
function closePayment() { closeAllModals(); }

function calcChange() {
  const received = parseFloat(document.getElementById('received').value) || 0;
  const el = document.getElementById('change-result');
  if (received <= 0) { el.textContent = ''; el.className = 'change-result'; return; }
  const change = received - grandTotal;
  if (change < 0) {
    el.textContent = 'ขาดเงินอีก ฿' + Math.abs(change).toLocaleString('th-TH', {minimumFractionDigits:2});
    el.className = 'change-result change-err';
  } else {
    el.textContent = 'เงินทอน ฿' + change.toLocaleString('th-TH', {minimumFractionDigits:2});
    el.className = 'change-result change-ok';
  }
}

function confirmPayment() {
  if (document.getElementById('payment_method').value === 'cash') {
    const received = parseFloat(document.getElementById('received').value) || 0;
    if (received < grandTotal) { alert('เงินไม่เพียงพอ'); return; }
  }
  document.getElementById('order-form').submit();
}

document.addEventListener('click', e => { if (e.target.classList.contains('modal-overlay')) closeAllModals(); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') closeAllModals(); });