<?php
// File: app/Views/pos/labels/print-form.php

$title = $title ?? 'Print Label Barcode';
$products = $products ?? ['data' => [], 'pagination' => []];
$categories = $categories ?? [];
$search = $search ?? '';
$selectedCategory = $selectedCategory ?? '';
$csrf_token = $csrf_token ?? '';

$error = $_SESSION['error'] ?? null;
$success = $_SESSION['success'] ?? null;
unset($_SESSION['error'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?></title>
    
    <style>
        /* ============================================
           CSS NATIVE TANPA BOOTSTRAP - FULL STYLING
           ============================================ */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
            background: #f4f6f9;
            color: #333;
            line-height: 1.6;
        }

        /* Navbar */
        .navbar {
            background: #007bff;
            padding: 12px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        .navbar-brand {
            color: white;
            font-size: 18px;
            font-weight: bold;
            text-decoration: none;
        }
        .navbar-nav {
            display: flex;
            gap: 15px;
        }
        .nav-link {
            color: rgba(255,255,255,0.9);
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 4px;
            transition: background 0.3s;
        }
        .nav-link:hover {
            background: rgba(255,255,255,0.2);
            color: white;
        }

        /* Container */
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Alert */
        .alert {
            padding: 15px 20px;
            border-radius: 6px;
            margin-bottom: 20px;
            position: relative;
        }
        .alert-danger {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-warning {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeaa7;
        }
        .alert .close {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            background: none;
            border: none;
            font-size: 20px;
            color: inherit;
        }

        /* Card */
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        .card-header {
            padding: 15px 20px;
            border-bottom: 2px solid #f0f0f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-body {
            padding: 20px;
        }

        /* Info Box */
        .info-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .info-box h5 {
            margin-bottom: 10px;
        }
        .info-box .row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .info-box .col {
            flex: 1;
            min-width: 250px;
        }
        .info-box ul {
            list-style: none;
            padding: 0;
        }
        .info-box li {
            margin-bottom: 5px;
            font-size: 14px;
        }

        /* Buttons */
        .btn {
            display: inline-block;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 13px;
            text-decoration: none;
            transition: all 0.3s;
            margin-right: 5px;
        }
        .btn:hover {
            opacity: 0.85;
            transform: translateY(-1px);
        }
        .btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .btn-outline-primary {
            background: white;
            color: #007bff;
            border: 1px solid #007bff;
        }
        .btn-outline-primary:hover {
            background: #007bff;
            color: white;
        }
        .btn-outline-secondary {
            background: white;
            color: #6c757d;
            border: 1px solid #6c757d;
        }
        .btn-outline-secondary:hover {
            background: #6c757d;
            color: white;
        }
        .btn-success {
            background: #28a745;
            color: white;
        }
        .btn-info {
            background: #17a2b8;
            color: white;
        }
        .btn-primary {
            background: #007bff;
            color: white;
        }
        .btn-secondary {
            background: #6c757d;
            color: white;
        }
        .btn-warning {
            background: #ffc107;
            color: #333;
        }
        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }
        .btn-block {
            display: block;
            width: 100%;
        }
        .btn-action {
            margin-bottom: 5px;
        }

        /* Selected Count Badge */
        .selected-count {
            display: inline-block;
            background: white;
            color: #007bff;
            padding: 2px 8px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: bold;
            margin-left: 3px;
        }

        /* Form Elements */
        .form-group {
            margin-bottom: 15px;
        }
        .input-group {
            display: flex;
            align-items: stretch;
        }
        .input-group-prepend {
            display: flex;
        }
        .input-group-text {
            background: #e9ecef;
            border: 1px solid #ced4da;
            padding: 8px 12px;
            border-radius: 4px 0 0 4px;
            font-size: 14px;
        }
        .form-control {
            flex: 1;
            padding: 8px 12px;
            border: 1px solid #ced4da;
            border-radius: 0 4px 4px 0;
            font-size: 14px;
            width: 100%;
        }
        select.form-control {
            border-radius: 4px;
        }

        /* Table */
        .table-responsive {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th {
            background: #f8f9fa;
            padding: 12px;
            text-align: left;
            border-bottom: 2px solid #dee2e6;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #666;
        }
        td {
            padding: 10px 12px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }
        tr:hover {
            background: #f8f9fa;
        }

        /* Badge */
        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 11px;
            font-weight: bold;
        }
        .badge-info {
            background: #17a2b8;
            color: white;
        }
        .badge-success {
            background: #28a745;
            color: white;
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            list-style: none;
            gap: 5px;
            margin-top: 20px;
        }
        .page-item {
            display: inline-block;
        }
        .page-link {
            display: block;
            padding: 8px 14px;
            background: white;
            border: 1px solid #dee2e6;
            color: #007bff;
            text-decoration: none;
            border-radius: 4px;
            transition: all 0.3s;
        }
        .page-link:hover {
            background: #e9ecef;
        }
        .page-item.active .page-link {
            background: #007bff;
            color: white;
            border-color: #007bff;
        }
        .page-item.disabled .page-link {
            color: #6c757d;
            pointer-events: none;
            opacity: 0.5;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 9999;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
            overflow: auto;
        }
        .modal.show {
            display: block;
        }
        .modal-dialog {
            position: relative;
            margin: 30px auto;
            max-width: 1100px;
            width: 95%;
        }
        .modal-content {
            background: white;
            border-radius: 8px;
            overflow: hidden;
        }
        .modal-header {
            padding: 15px 20px;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-header .close {
            background: none;
            border: none;
            font-size: 28px;
            cursor: pointer;
            color: #999;
        }
        .modal-header .close:hover {
            color: #333;
        }
        .modal-body {
            padding: 0;
            max-height: 70vh;
            overflow-y: auto;
        }
        .modal-footer {
            padding: 15px 20px;
            border-top: 1px solid #e9ecef;
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        /* Loading Overlay */
        .loading-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 10000;
        }
        .loading-overlay.show {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .loading-content {
            background: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
        }

        /* Spinner */
        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #e9ecef;
            border-top: 4px solid #007bff;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 0 auto 15px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Row & Col */
        .row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -10px;
        }
        .col-md-2 { flex: 0 0 16.666%; padding: 0 10px; }
        .col-md-3 { flex: 0 0 25%; padding: 0 10px; }
        .col-md-4 { flex: 0 0 33.333%; padding: 0 10px; }
        .col-md-5 { flex: 0 0 41.666%; padding: 0 10px; }
        .col-md-6 { flex: 0 0 50%; padding: 0 10px; }

        @media (max-width: 768px) {
            .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6 {
                flex: 0 0 100%;
                margin-bottom: 10px;
            }
            .navbar {
                flex-direction: column;
                gap: 10px;
            }
            .navbar-nav {
                flex-wrap: wrap;
            }
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }

        /* Utility */
        .text-center { text-align: center; }
        .text-muted { color: #6c757d; }
        .text-white { color: white; }
        .mr-2 { margin-right: 10px; }
        .mb-0 { margin-bottom: 0; }
        .mb-3 { margin-bottom: 15px; }
        .mt-2 { margin-top: 10px; }
        .mt-3 { margin-top: 15px; }
        .p-3 { padding: 15px; }
        .p-5 { padding: 30px; }
        .w-100 { width: 100%; }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar no-print">
        <a href="/pos/labels" class="navbar-brand">
            🏷️ Print Label Barcode
        </a>
        <div class="navbar-nav">
            <a href="<?=  route('view.admin.home')?>" class="nav-link">Back to Admin</a>
            <a href="/pos/labels" class="nav-link">🔄 Reset</a>
            <a href="/pos/labels/print-all" class="nav-link" target="_blank">🖨️ Print Semua</a>
        </div>
    </nav>

    <div class="container">
        <!-- Alert Messages -->
        <?php if ($error): ?>
        <div class="alert alert-danger" id="errorAlert">
            ⚠️ <?= htmlspecialchars($error) ?>
            <button class="close" onclick="this.parentElement.remove()">&times;</button>
        </div>
        <?php endif; ?>
        
        <?php if ($success): ?>
        <div class="alert alert-success" id="successAlert">
            ✅ <?= htmlspecialchars($success) ?>
            <button class="close" onclick="this.parentElement.remove()">&times;</button>
        </div>
        <?php endif; ?>

        <!-- Info Box -->
        <div class="info-box no-print">
            <h5>ℹ️ Informasi Layout Label</h5>
            <div class="row">
                <div class="col">
                    <ul>
                        <li>Ukuran Label: <strong>6cm × 4cm</strong></li>
                        <li>Kertas: <strong>A4</strong> (21cm × 29.7cm)</li>
                        <li>Layout: <strong>3 Kolom × 7 Baris</strong></li>
                        <li>Total: <strong>21 Label/Halaman</strong></li>
                    </ul>
                </div>
                <div class="col">
                    <ul>
                        <li>Isi: Logo, Nama, Barcode, Harga, Tanggal</li>
                        <li>Tipe Barcode: <strong>Code 128</strong></li>
                        <li>Auto page break setiap 21 label</li>
                        <li>Print dengan <strong>Ctrl+P</strong></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Filter & Search -->
        <div class="card no-print">
            <div class="card-body">
                <form method="GET" action="/pos/labels" id="filterForm">
                    <div class="row">
                        <div class="col-md-5">
                            <div class="input-group">
                                <span class="input-group-text">🔍</span>
                                <input type="text" 
                                       name="search" 
                                       class="form-control" 
                                       placeholder="Cari nama produk atau barcode..."
                                       value="<?= htmlspecialchars($search) ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <select name="category" class="form-control" onchange="document.getElementById('filterForm').submit()">
                                <option value="">Semua Kategori</option>
                                <?php foreach ($categories as $cat): ?>
                                <option value="<?= htmlspecialchars($cat) ?>" 
                                    <?= $selectedCategory == $cat ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($cat) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary btn-block">🔍 Filter</button>
                        </div>
                        <div class="col-md-2">
                            <?php if ($search || $selectedCategory): ?>
                            <a href="/pos/labels" class="btn btn-outline-secondary btn-block">✕ Reset</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Products Table -->
        <div class="card no-print">
            <div class="card-header">
                <h3>📋 Daftar Produk</h3>
                <small class="text-muted">
                    Total: <?= $products['pagination']['total'] ?? 0 ?> produk | 
                    Hal: <?= $products['pagination']['current_page'] ?? 1 ?>/<?= $products['pagination']['last_page'] ?? 1 ?>
                </small>
            </div>
            
            <div class="card-body">
                <!-- Action Buttons -->
                <div class="mb-3">
                    <button type="button" class="btn btn-outline-primary btn-sm btn-action" id="btnSelectAll">
                        ☑️ Pilih Semua
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-sm btn-action" id="btnDeselectAll">
                        ☐ Hapus Pilihan
                    </button>
                    <button type="button" class="btn btn-success btn-sm btn-action" id="btnPrintSelected">
                        🖨️ Print Terpilih <span class="selected-count" id="countSelected">0</span>
                    </button>
                    <button type="button" class="btn btn-info btn-sm btn-action" id="btnPreview">
                        👁️ Preview
                    </button>
                </div>

                <form id="printForm" method="POST" action="/pos/labels/print-selected" target="_blank">
                    <?=  csrf() ?>
                    
                    <div class="table-responsive">
                        <table>
                            <thead>
                                <tr>
                                    <th width="30"><input type="checkbox" id="checkAll"></th>
                                    <th width="40">No</th>
                                    <th>Nama Produk</th>
                                    <th>Barcode</th>
                                    <th>Kategori</th>
                                    <th>Harga Jual</th>
                                    <th width="80">Status</th>
                                    <th width="80">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($products['data'])): ?>
                                <tr>
                                    <td colspan="8" class="text-center">
                                        <div class="alert alert-warning mb-0">
                                            ⚠️ Tidak ada produk ditemukan
                                        </div>
                                    </td>
                                </tr>
                                <?php else: ?>
                                    <?php 
                                    $no = ($products['pagination']['from'] ?? 1);
                                    foreach ($products['data'] as $product): 
                                        $id = is_array($product) ? ($product['id'] ?? '') : ($product->id ?? '');
                                        $name = is_array($product) ? ($product['name'] ?? '') : ($product->name ?? '');
                                        $barcode = is_array($product) ? ($product['qrcode'] ?? '') : ($product->qrcode ?? '');
                                        $category = is_array($product) ? ($product['category'] ?? '') : ($product->category ?? '');
                                        $company = is_array($product) ? ($product['company'] ?? '') : ($product->company ?? '');
                                        $price = is_array($product) ? ($product['sell_price'] ?? 0) : ($product->selling_price ?? 0);
                                    ?>
                                    <tr>
                                        <td>
                                            <input type="checkbox" 
                                                   name="selected_products[]" 
                                                   value="<?= htmlspecialchars($id) ?>" 
                                                   class="product-checkbox">
                                        </td>
                                        <td><?= $no++ ?></td>
                                        <td>
                                            <strong><?= htmlspecialchars($name) ?></strong>
                                            <?php if ($company): ?>
                                            <br><small class="text-muted"><?= htmlspecialchars($company) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td><code><?= htmlspecialchars($barcode) ?></code></td>
                                        <td>
                                            <span class="badge badge-info"><?= htmlspecialchars($category ?: 'Umum') ?></span>
                                        </td>
                                        <td>
                                            <strong>Rp <?= number_format($price, 0, ',', '.') ?></strong>
                                        </td>
                                        <td>
                                            <span class="badge badge-success">Active</span>
                                        </td>
                                        <td>
                                            <button type="button" 
                                                    class="btn btn-outline-primary btn-sm btn-print-single"
                                                    data-id="<?= htmlspecialchars($id) ?>"
                                                    title="Print satu label">
                                                🖨️
                                            </button>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </form>

                <!-- Pagination -->
                <?php if (!empty($products['pagination']) && $products['pagination']['last_page'] > 1): ?>
                <nav class="mt-3">
                    <ul class="pagination">
                        <?php 
                        $currentPage = $products['pagination']['current_page'];
                        $lastPage = $products['pagination']['last_page'];
                        $queryString = http_build_query(['search' => $search, 'category' => $selectedCategory]);
                        ?>
                        
                        <li class="page-item <?= $currentPage <= 1 ? 'disabled' : '' ?>">
                            <a class="page-link" href="/pos/labels?<?= $queryString ?>&page=<?= $currentPage - 1 ?>">« Prev</a>
                        </li>
                        
                        <?php 
                        $start = max(1, $currentPage - 2);
                        $end = min($lastPage, $currentPage + 2);
                        for ($i = $start; $i <= $end; $i++): 
                        ?>
                        <li class="page-item <?= $i == $currentPage ? 'active' : '' ?>">
                            <a class="page-link" href="/pos/labels?<?= $queryString ?>&page=<?= $i ?>"><?= $i ?></a>
                        </li>
                        <?php endfor; ?>
                        
                        <li class="page-item <?= $currentPage >= $lastPage ? 'disabled' : '' ?>">
                            <a class="page-link" href="/pos/labels?<?= $queryString ?>&page=<?= $currentPage + 1 ?>">Next »</a>
                        </li>
                    </ul>
                </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Preview Modal -->
    <div class="modal" id="previewModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>👁️ Preview Label</h5>
                    <button class="close" id="btnCloseModal">&times;</button>
                </div>
                <div class="modal-body" id="previewContent" style="padding: 15px;">
                    <div class="text-center p-5">
                        <div class="spinner"></div>
                        <p>Memuat preview...</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" id="btnCloseModal2">Tutup</button>
                    <button class="btn btn-primary" id="btnPrintPreview">🖨️ Print</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="loading-overlay" id="loadingOverlay">
        <div class="loading-content">
            <div class="spinner"></div>
            <h4>Memproses Label...</h4>
            <p class="text-muted">Mohon tunggu sebentar</p>
        </div>
    </div>

    <!-- ============================================
         VANILLA JAVASCRIPT (NO JQUERY)
         ============================================ -->
    <script>
    (function() {
        'use strict';
        
        // ============ DOM ELEMENTS ============
        var checkAll = document.getElementById('checkAll');
        var productCheckboxes = document.querySelectorAll('.product-checkbox');
        var countSelected = document.getElementById('countSelected');
        var printForm = document.getElementById('printForm');
        var loadingOverlay = document.getElementById('loadingOverlay');
        var previewModal = document.getElementById('previewModal');
        var previewContent = document.getElementById('previewContent');
        var btnPrintSelected = document.getElementById('btnPrintSelected');
        
        // ============ UPDATE COUNTER ============
        function updateCount() {
            var checked = document.querySelectorAll('.product-checkbox:checked');
            var count = checked.length;
            
            if (countSelected) {
                countSelected.textContent = count;
            }
            
            // Disable print button if 0 selected
            if (btnPrintSelected) {
                btnPrintSelected.disabled = (count === 0);
            }
            
            // Update checkAll state
            if (checkAll) {
                checkAll.checked = (count === productCheckboxes.length && count > 0);
                checkAll.indeterminate = (count > 0 && count < productCheckboxes.length);
            }
        }
        
        // ============ SELECT ALL ============
        window.selectAll = function() {
            productCheckboxes.forEach(function(cb) {
                cb.checked = true;
            });
            updateCount();
        };
        
        // ============ DESELECT ALL ============
        window.deselectAll = function() {
            productCheckboxes.forEach(function(cb) {
                cb.checked = false;
            });
            updateCount();
        };
        
        // ============ TOGGLE ALL ============
        window.toggleAll = function(source) {
            productCheckboxes.forEach(function(cb) {
                cb.checked = source.checked;
            });
            updateCount();
        };
        
        // ============ PRINT SELECTED ============
        window.printSelected = function() {
            var checked = document.querySelectorAll('.product-checkbox:checked');
            var count = checked.length;
            
            if (count === 0) {
                alert('Pilih minimal 1 produk untuk diprint!');
                return;
            }
            
            if (count > 50) {
                var pages = Math.ceil(count / 21);
                if (!confirm('Anda akan mencetak ' + count + ' produk (' + pages + ' halaman). Lanjutkan?')) {
                    return;
                }
            }
            
            // Show loading
            if (loadingOverlay) {
                loadingOverlay.classList.add('show');
            }
            
            // Submit form
            if (printForm) {
                printForm.submit();
            }
            
            // Hide loading after delay
            setTimeout(function() {
                if (loadingOverlay) {
                    loadingOverlay.classList.remove('show');
                }
            }, 3000);
        };
        
        // ============ PRINT SINGLE ============
        window.printSingle = function(productId) {
            if (!productId) return;
            window.open('/pos/labels/print-single/' + productId, '_blank');
        };
        
        // ============ PREVIEW LABELS ============
        window.previewLabels = function() {
            var selectedIds = [];
            document.querySelectorAll('.product-checkbox:checked').forEach(function(cb) {
                selectedIds.push(cb.value);
            });
            
            if (selectedIds.length === 0) {
                alert('Pilih minimal 1 produk untuk preview!');
                return;
            }
            
            // Show modal
            if (previewModal) {
                previewModal.classList.add('show');
            }
            
            if (previewContent) {
                previewContent.innerHTML = '<div class="text-center p-5"><div class="spinner"></div><p>Memuat preview...</p></div>';
            }
            
            // Fetch preview via AJAX
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/pos/labels/preview', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            
            xhr.onload = function() {
                if (xhr.status === 200) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.html && previewContent) {
                            previewContent.innerHTML = response.html;
                        } else if (response.error && previewContent) {
                            previewContent.innerHTML = '<div class="alert alert-danger">' + response.error + '</div>';
                        }
                    } catch(e) {
                        if (previewContent) {
                            previewContent.innerHTML = '<div class="alert alert-danger">Gagal parsing response</div>';
                        }
                    }
                } else {
                    if (previewContent) {
                        previewContent.innerHTML = '<div class="alert alert-danger">Error: ' + xhr.status + '</div>';
                    }
                }
            };
            
            xhr.onerror = function() {
                if (previewContent) {
                    previewContent.innerHTML = '<div class="alert alert-danger">Gagal terhubung ke server</div>';
                }
            };
            
            // Build form data
            var formData = '_token=<?= urlencode($csrf_token) ?>';
            selectedIds.forEach(function(id) {
                formData += '&selected_products[]=' + encodeURIComponent(id);
            });
            
            xhr.send(formData);
        };
        
        // ============ PRINT FROM PREVIEW ============
        window.printFromPreview = function() {
            window.print();
        };
        
        // ============ CLOSE MODAL ============
        function closeModal() {
            if (previewModal) {
                previewModal.classList.remove('show');
            }
        }
        
        // ============ EVENT LISTENERS ============
        
        // CheckAll toggle
        if (checkAll) {
            checkAll.addEventListener('change', function() {
                productCheckboxes.forEach(function(cb) {
                    cb.checked = checkAll.checked;
                });
                updateCount();
            });
        }
        
        // Product checkboxes change
        productCheckboxes.forEach(function(cb) {
            cb.addEventListener('change', updateCount);
        });
        
        // Select All button
        var btnSelectAll = document.getElementById('btnSelectAll');
        if (btnSelectAll) {
            btnSelectAll.addEventListener('click', selectAll);
        }
        
        // Deselect All button
        var btnDeselectAll = document.getElementById('btnDeselectAll');
        if (btnDeselectAll) {
            btnDeselectAll.addEventListener('click', deselectAll);
        }
        
        // Print Selected button
        if (btnPrintSelected) {
            btnPrintSelected.addEventListener('click', printSelected);
        }
        
        // Preview button
        var btnPreview = document.getElementById('btnPreview');
        if (btnPreview) {
            btnPreview.addEventListener('click', previewLabels);
        }
        
        // Print single buttons
        document.querySelectorAll('.btn-print-single').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var productId = this.getAttribute('data-id');
                printSingle(productId);
            });
        });
        
        // Close modal buttons
        var btnCloseModal = document.getElementById('btnCloseModal');
        var btnCloseModal2 = document.getElementById('btnCloseModal2');
        if (btnCloseModal) btnCloseModal.addEventListener('click', closeModal);
        if (btnCloseModal2) btnCloseModal2.addEventListener('click', closeModal);
        
        // Print from preview
        var btnPrintPreview = document.getElementById('btnPrintPreview');
        if (btnPrintPreview) {
            btnPrintPreview.addEventListener('click', printFromPreview);
        }
        
        // Close modal on background click
        if (previewModal) {
            previewModal.addEventListener('click', function(e) {
                if (e.target === previewModal) {
                    closeModal();
                }
            });
        }
        
        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            // Ctrl+A
            if (e.ctrlKey && e.key === 'a') {
                e.preventDefault();
                selectAll();
            }
            
            // Ctrl+P
            if (e.ctrlKey && e.key === 'p' && !e.shiftKey) {
                e.preventDefault();
                printSelected();
            }
            
            // Escape to close modal
            if (e.key === 'Escape') {
                closeModal();
            }
        });
        
        // Auto-hide alerts
        var alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            setTimeout(function() {
                if (alert.parentElement) {
                    alert.style.opacity = '0';
                    alert.style.transition = 'opacity 0.5s';
                    setTimeout(function() {
                        if (alert.parentElement) {
                            alert.remove();
                        }
                    }, 500);
                }
            }, 5000);
        });
        
        // Initialize
        updateCount();
        
        // Expose functions to global scope (for onclick attributes)
        console.log('✅ Label Print System Ready!');
        console.log('   - selectAll()');
        console.log('   - deselectAll()');
        console.log('   - toggleAll()');
        console.log('   - printSelected()');
        console.log('   - printSingle(id)');
        console.log('   - previewLabels()');
        console.log('   - printFromPreview()');
        
    })();
    </script>
</body>
</html>