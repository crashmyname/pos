<div class="container-xl py-4">

    <!-- PAGE HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title fw-bold">
                Daily Cashier Report
            </h2>
            <div class="text-secondary">
                Daily sales summary
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <button class="btn btn-primary" id="printBtn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 6 2 18 2 18 9"/>
                        <path d="M6 12H4a2 2 0 0 0-2 2v4a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-4a2 2 0 0 0-2-2h-2"/>
                        <rect x="6" y="14" width="12" height="8"/>
                    </svg>
                    PRINT REPORT
                </button>
            </div>
            <div class="col-md-6">
                <button class="btn btn-danger" id="closingBtn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    CLOSING TRANSACTION
                </button>
                <button class="btn btn-danger" style="display: none;" disabled id="loadingclosingBtn">
                    <span class="spinner-border spinner-border-sm me-2"></span>
                    CLOSING TRANSACTION
                </button>
            </div>
        </div>
    </div>

    <!-- SUMMARY -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="small text-secondary">
                        Total Transaction
                    </div>
                    <div class="fs-1 fw-bold text-primary" id="totalTransaction">
                        <div class="spinner-border spinner-border-sm text-secondary"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="small text-secondary">
                        Gross Income
                    </div>
                    <div class="fs-2 fw-bold text-success" id="grossIncome">
                        <div class="spinner-border spinner-border-sm text-secondary"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="small text-secondary">
                        Return Total
                    </div>
                    <div class="fs-2 fw-bold text-danger" id="returnTotal">
                        <div class="spinner-border spinner-border-sm text-secondary"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="small text-secondary">
                        Net Income
                    </div>
                    <div class="fs-2 fw-bold text-primary" id="netIncome">
                        <div class="spinner-border spinner-border-sm text-secondary"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- PAYMENT SUMMARY -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="card-title">
                Payment Summary
            </h3>
            <small class="text-secondary" id="paymentDate"></small>
        </div>
        <div class="table-responsive">
            <table class="table table-vcenter">
                <thead>
                    <tr>
                        <th>Payment Method</th>
                        <th>Total Transaction</th>
                        <th>Total Amount</th>
                    </tr>
                </thead>
                <tbody id="paymentSummaryBody">
                    <tr>
                        <td colspan="3" class="text-center">
                            <div class="spinner-border spinner-border-sm text-secondary"></div>
                            Loading data...
                        </td>
                    </tr>
                </tbody>
                <tfoot class="table-light" id="paymentSummaryFoot" style="display: none;">
                    <!-- Footer akan diisi dinamis -->
                </tfoot>
            </table>
        </div>
    </div>

    <!-- CASHIER ACTIVITY -->
    <div class="card border-0 shadow-sm">
        <div class="card-header">
            <h3 class="card-title">
                Cashier Activity
            </h3>
        </div>
        <div class="table-responsive">
            <table class="table table-vcenter">
                <thead>
                    <tr>
                        <th>Cashier</th>
                        <th>Transaction</th>
                        <th>Sales</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="cashierActivityBody">
                    <tr>
                        <td colspan="4" class="text-center">
                            <div class="spinner-border spinner-border-sm text-secondary"></div>
                            Loading data...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- TRANSACTION RECORDS -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <!-- Toggle Button -->
                <button class="btn btn-sm btn-outline-secondary border-0" id="toggleTransactionBtn" type="button">
                    <svg id="toggleIcon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"/>
                    </svg>
                </button>
                <h3 class="card-title mb-0">
                    Transaction Records
                </h3>
            </div>
            <div class="d-flex align-items-center gap-2">
                <small class="text-secondary" id="recordDate"></small>
                <!-- Download PDF Button -->
                <button class="btn btn-danger btn-sm" id="downloadPdfBtn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                        <polyline points="14 2 14 8 20 8"/>
                        <line x1="16" y1="13" x2="8" y2="13"/>
                        <line x1="16" y1="17" x2="8" y2="17"/>
                        <polyline points="10 9 9 9 8 9"/>
                    </svg>
                    Download PDF
                </button>
                <!-- Download Excel Button -->
                <button class="btn btn-success btn-sm" id="downloadExcelBtn">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="7 10 12 15 17 10"/>
                        <line x1="12" y1="15" x2="12" y2="3"/>
                    </svg>
                    Download Excel
                </button>
            </div>
        </div>
        
        <!-- Wrapper untuk konten yang bisa di-collapse -->
        <div id="transactionContent">
            <div class="table-responsive">
                <table class="table table-vcenter table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Invoice</th>
                            <th>Time</th>
                            <th>Payment Method</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="transactionRecordsBody">
                        <tr>
                            <td colspan="5" class="text-center">
                                <div class="spinner-border spinner-border-sm text-secondary"></div>
                                Loading records...
                            </td>
                        </tr>
                    </tbody>
                    <tfoot class="table-light" id="transactionRecordsFoot" style="display: none;">
                        <!-- Footer akan diisi dinamis -->
                    </tfoot>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="card-footer d-flex justify-content-between align-items-center" id="paginationContainer" style="display: none;">
                <div class="text-secondary" id="paginationInfo">
                    Showing 0 to 0 of 0 entries
                </div>
                <nav>
                    <ul class="pagination pagination-sm mb-0" id="paginationNav">
                        <!-- Pagination buttons akan diisi dinamis -->
                    </ul>
                </nav>
            </div>
        </div>
    </div>

</div>
<style>
#transactionContent {
    transition: all 0.3s ease-in-out;
}

/* Styling untuk tombol toggle */
#toggleTransactionBtn {
    padding: 4px;
    line-height: 1;
    transition: all 0.2s ease;
}

#toggleTransactionBtn:hover {
    background-color: rgba(0,0,0,0.05);
}

/* Rotasi icon dengan smooth */
#toggleIcon {
    transition: transform 0.3s ease;
}

/* Optional: Animasi collapse seperti Bootstrap */
.collapsing {
    height: 0;
    overflow: hidden;
    transition: height 0.35s ease;
}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
<script src="<?= asset_v('js/custom-alert.js')?>"></script>
<script src="<?= asset_v('js/api.js')?>"></script>
<script>
const Alert = new CustomAlert();

// ============================================
// Helper Functions
// ============================================

function formatRupiah(amount) {
    if (!amount) return 'Rp 0';
    return 'Rp ' + Number(amount).toLocaleString('id-ID');
}

function formatNumber(number) {
    if (!number) return '0';
    return Number(number).toLocaleString('id-ID');
}

function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    const options = { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
    };
    return date.toLocaleDateString('id-ID', options);
}

function formatTime(dateString) {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleTimeString('id-ID', {
        hour: '2-digit', minute: '2-digit', second: '2-digit'
    });
}

function getPaymentIcon(method) {
    const icons = {
        'CASH': '💵', 'QRIS': '📱', 'CREDIT': '💳', 
        'DEBIT': '🏦', 'TRANSFER': '🏧', 'EWALLET': '📲'
    };
    return icons[method?.toUpperCase()] || '💰';
}

function getInitials(userId) {
    return userId ? String(userId).substring(0, 2).toUpperCase() : '?';
}

async function fetchReportData() {
    try {
        const response = await api.get('<?= route('data.report')?>');
        
        const reportData = response.data;
        if (!reportData) {
            showError('Data tidak ditemukan');
            return;
        }
        
        // Render summary
        renderSummary(reportData.transaction);
        renderPaymentSummary(reportData.payment_summary);
        renderCashierActivity(reportData.cashier_activity);
        
        // Update date
        if (reportData.transaction?.transaction_date) {
            const dateStr = formatDate(reportData.transaction.transaction_date);
            document.getElementById('paymentDate').textContent = dateStr;
            document.getElementById('recordDate').textContent = dateStr;
        }
        
        // Load records (page 1)
        loadRecords(1);
        
    } catch (error) {
        showError('Gagal memuat data laporan');
    }
}

// ============================================
// LOAD RECORDS (Dengan Pagination)
// ============================================

async function loadRecords(page = 1) {
    const tbody = document.getElementById('transactionRecordsBody');
    const tfoot = document.getElementById('transactionRecordsFoot');
    const paginationContainer = document.getElementById('paginationContainer');
    
    // Show loading
    tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4"><div class="spinner-border spinner-border-sm text-secondary"></div> Loading page ${page}...</td></tr>`;
    tfoot.style.display = 'none';
    paginationContainer.style.display = 'none';
    
    try {
        const url = `<?= route('records.transaction')?>?page=${page}&per_page=2`;
        const response = await fetch(url, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        const result = await response.json();
        
        const recordData = result.data;
        let records = [];
        let pagination = null;
        
        if (recordData?.data) {
            records = recordData.data;
            pagination = recordData.pagination;
        }
        
        // Render table
        if (!records || records.length === 0) {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center text-secondary py-4">📋 No records for today</td></tr>`;
            return;
        }
        
        const startNumber = pagination ? (pagination.current_page - 1) * pagination.per_page : 0;
        
        tbody.innerHTML = records.map((record, index) => `
            <tr>
                <td class="text-secondary">${startNumber + index + 1}</td>
                <td><span class="badge bg-primary-lt">#${record.invoice_number || '-'}</span></td>
                <td>
                    <div class="d-flex align-items-center">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" class="me-2 text-secondary">
                            <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
                        </svg>
                        ${formatTime(record.transaction_date)}
                    </div>
                </td>
                <td>
                    <span class="badge bg-info-lt me-2">${getPaymentIcon(record.payment_method)}</span>
                    ${record.payment_method || '-'}
                </td>
                <td class="fw-bold">${formatRupiah(record.grand_total || 0)}</td>
            </tr>
        `).join('');
        
        // Footer
        const pageTotal = records.reduce((sum, r) => sum + (Number(r.grand_total) || 0), 0);
        tfoot.innerHTML = `
            <tr>
                <td colspan="4" class="fw-bold text-end">Page ${pagination?.current_page || 1} Total (${records.length} items)</td>
                <td class="fw-bold fs-5">${formatRupiah(pageTotal)}</td>
            </tr>
        `;
        tfoot.style.display = '';
        
        // Pagination buttons
        if (pagination && pagination.last_page > 1) {
            renderPagination(pagination);
        }
        
    } catch (error) {
        tbody.innerHTML = `<tr><td colspan="5" class="text-center text-danger py-4">⚠️ Gagal memuat data</td></tr>`;
    }
}

// ============================================
// RENDER PAGINATION
// ============================================

function renderPagination(pagination) {
    const container = document.getElementById('paginationContainer');
    const info = document.getElementById('paginationInfo');
    const nav = document.getElementById('paginationNav');
    
    container.style.display = 'flex';
    
    const { total, per_page, current_page, last_page } = pagination;
    const from = (current_page - 1) * per_page + 1;
    const to = Math.min(current_page * per_page, total);
    
    info.textContent = `Showing ${from} to ${to} of ${total} entries`;
    
    let html = '';
    
    // Previous
    if (current_page > 1) {
        html += `<li class="page-item">
            <button class="page-link" onclick="loadRecords(${current_page - 1})">«</button>
        </li>`;
    } else {
        html += `<li class="page-item disabled"><button class="page-link" disabled>«</button></li>`;
    }
    
    // Page numbers
    for (let i = 1; i <= last_page; i++) {
        html += `<li class="page-item ${i === current_page ? 'active' : ''}">
            <button class="page-link" onclick="loadRecords(${i})">${i}</button>
        </li>`;
    }
    
    // Next
    if (current_page < last_page) {
        html += `<li class="page-item">
            <button class="page-link" onclick="loadRecords(${current_page + 1})">»</button>
        </li>`;
    } else {
        html += `<li class="page-item disabled"><button class="page-link" disabled>»</button></li>`;
    }
    
    nav.innerHTML = html;
    
    // Scroll to top of table after click
    document.querySelector('#transactionRecordsBody').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

// ============================================
// RENDER SUMMARY
// ============================================

function renderSummary(transaction) {
    if (!transaction) {
        document.getElementById('totalTransaction').textContent = '0';
        document.getElementById('grossIncome').textContent = 'Rp 0';
        document.getElementById('returnTotal').textContent = 'Rp 0';
        document.getElementById('netIncome').textContent = 'Rp 0';
        return;
    }
    
    document.getElementById('totalTransaction').textContent = formatNumber(transaction.jumlah_transaction || 0);
    document.getElementById('grossIncome').textContent = formatRupiah(transaction.total_transaction || 0);
    document.getElementById('returnTotal').textContent = formatRupiah(transaction.return_total || 0);
    
    const gross = Number(transaction.total_transaction || 0);
    const returns = Number(transaction.return_total || 0);
    document.getElementById('netIncome').textContent = formatRupiah(gross - returns);
}

function renderPaymentSummary(paymentData) {
    const tbody = document.getElementById('paymentSummaryBody');
    const tfoot = document.getElementById('paymentSummaryFoot');
    
    if (!paymentData || !Array.isArray(paymentData) || paymentData.length === 0) {
        tbody.innerHTML = `<tr><td colspan="3" class="text-center text-secondary py-4">💰 No payment data</td></tr>`;
        tfoot.style.display = 'none';
        return;
    }
    
    tbody.innerHTML = paymentData.map(p => `
        <tr>
            <td><span class="badge bg-primary-lt me-2">${getPaymentIcon(p.payment_method)}</span>${p.payment_method || '-'}</td>
            <td>${formatNumber(p.jumlah_transaction || 0)}</td>
            <td class="fw-bold">${formatRupiah(p.total_transaction || 0)}</td>
        </tr>
    `).join('');
    
    const totalTx = paymentData.reduce((s, p) => s + (Number(p.jumlah_transaction) || 0), 0);
    const totalAmt = paymentData.reduce((s, p) => s + (Number(p.total_transaction) || 0), 0);
    
    tfoot.innerHTML = `
        <tr><td class="fw-bold">TOTAL</td><td class="fw-bold">${formatNumber(totalTx)}</td><td class="fw-bold fs-5">${formatRupiah(totalAmt)}</td></tr>
    `;
    tfoot.style.display = '';
}

function renderCashierActivity(cashierData) {
    const tbody = document.getElementById('cashierActivityBody');
    
    if (!cashierData || !cashierData.user_id) {
        tbody.innerHTML = `<tr><td colspan="4" class="text-center text-secondary py-4">👤 No cashier data</td></tr>`;
        return;
    }
    
    const userName = cashierData.users?.[0]?.name || `Cashier #${cashierData.user_id}`;
    
    tbody.innerHTML = `
        <tr>
            <td>
                <div class="d-flex align-items-center">
                    <span class="avatar avatar-sm me-2" style="background:#e3f2fd;color:#1976d2;width:32px;height:32px;display:inline-flex;align-items:center;justify-content:center;border-radius:50%;font-weight:bold;font-size:14px;">
                        ${getInitials(cashierData.user_id)}
                    </span>
                    ${userName}
                </div>
            </td>
            <td>${formatNumber(cashierData.jumlah_transaction || 0)}</td>
            <td class="fw-bold text-primary">${formatRupiah(cashierData.total_transaction || 0)}</td>
            <td><span class="badge bg-success-lt">ACTIVE</span></td>
        </tr>
    `;
}

function showError(message) {
    document.getElementById('totalTransaction').textContent = '---';
    document.getElementById('grossIncome').textContent = '---';
    document.getElementById('returnTotal').textContent = '---';
    document.getElementById('netIncome').textContent = '---';
    document.getElementById('paymentSummaryBody').innerHTML = `<tr><td colspan="3" class="text-center text-danger py-4">⚠️ ${message}</td></tr>`;
    document.getElementById('paymentSummaryFoot').style.display = 'none';
    document.getElementById('cashierActivityBody').innerHTML = `<tr><td colspan="4" class="text-center text-danger py-4">⚠️ ${message}</td></tr>`;
}

// ============================================
// DOWNLOAD EXCEL
// ============================================

document.getElementById('downloadExcelBtn').addEventListener('click', async function() {
    const btn = this;
    const origHTML = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Downloading...';
    btn.disabled = true;
    
    try {
        // Ambil semua data (per_page besar)
        const url = `<?= route('records.transaction')?>?page=1&per_page=1000`;
        const response = await fetch(url, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        const result = await response.json();
        const records = result.data?.data || [];
        
        if (records.length === 0) {
            Alert.toast('Tidak ada data', 'warning');
            return;
        }
        
        let csv = '\uFEFFNo,Invoice,Date,Time,Total Items,Grand Total,Payment Method\n';
        records.forEach((r, i) => {
            const date = r.transaction_date ? new Date(r.transaction_date).toLocaleDateString('id-ID') : '-';
            const time = r.transaction_date ? new Date(r.transaction_date).toLocaleTimeString('id-ID') : '-';
            csv += `${i+1},"${r.invoice_number||'-'}","${date}","${time}","${r.total_item||0}","${r.grand_total||0}","${r.payment_method||'-'}"\n`;
        });
        
        const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
        const url_blob = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url_blob;
        a.download = `transaction_records_${new Date().toISOString().split('T')[0]}.csv`;
        a.click();
        URL.revokeObjectURL(url_blob);
        
        Alert.toast('Excel berhasil di-download!', 'success');
    } catch (error) {
        Alert.toast('Gagal download Excel', 'error');
    } finally {
        btn.innerHTML = origHTML;
        btn.disabled = false;
    }
});

document.getElementById('downloadPdfBtn').addEventListener('click', async function() {
    const btn = this;
    const origHTML = btn.innerHTML;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Generating PDF...';
    btn.disabled = true;
    
    try {
        // Fetch semua data
        const url = `<?= route('records.transaction')?>?page=1&per_page=1000`;
        const response = await fetch(url, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        const result = await response.json();
        const records = result.data?.data || [];
        
        if (records.length === 0) {
            Alert.toast('Tidak ada data untuk di-export', 'warning');
            return;
        }
        
        // Generate PDF
        generatePDF(records);
        
        Alert.toast('PDF berhasil di-download!', 'success');
        
    } catch (error) {
        Alert.toast('Gagal generate PDF', 'error');
    } finally {
        btn.innerHTML = origHTML;
        btn.disabled = false;
    }
});

function generatePDF(records) {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('p', 'mm', 'a4');
    
    // ============================================
    // HEADER
    // ============================================
    
    // Company name
    doc.setFontSize(18);
    doc.setFont('helvetica', 'bold');
    doc.text('Daily Transaction Report', 14, 20);
    
    // Date
    const today = new Date().toLocaleDateString('id-ID', {
        weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
    });
    doc.setFontSize(10);
    doc.setFont('helvetica', 'normal');
    doc.text(`Date: ${today}`, 14, 28);
    
    // Summary info
    const totalTransaction = document.getElementById('totalTransaction').textContent;
    const grossIncome = document.getElementById('grossIncome').textContent;
    const netIncome = document.getElementById('netIncome').textContent;
    
    doc.setFontSize(9);
    doc.text(`Total Transactions: ${totalTransaction}`, 14, 35);
    doc.text(`Gross Income: ${grossIncome}`, 80, 35);
    doc.text(`Net Income: ${netIncome}`, 140, 35);
    
    // ============================================
    // TABLE
    // ============================================
    
    const tableColumn = ['No', 'Invoice', 'Date', 'Time', 'Items', 'Payment Method', 'Total'];
    const tableRows = [];
    
    records.forEach((record, index) => {
        const date = record.transaction_date 
            ? new Date(record.transaction_date).toLocaleDateString('id-ID')
            : '-';
        const time = record.transaction_date 
            ? new Date(record.transaction_date).toLocaleTimeString('id-ID', {
                hour: '2-digit', minute: '2-digit', second: '2-digit'
            })
            : '-';
        
        const rowData = [
            index + 1,
            record.invoice_number || '-',
            date,
            time,
            record.total_item || 0,
            record.payment_method || '-',
            formatRupiahPDF(record.grand_total || 0)
        ];
        tableRows.push(rowData);
    });
    
    // Generate auto table
    doc.autoTable({
        head: [tableColumn],
        body: tableRows,
        startY: 40,
        theme: 'grid',
        styles: {
            fontSize: 8,
            cellPadding: 2,
            halign: 'center',
            valign: 'middle'
        },
        headStyles: {
            fillColor: [41, 98, 255],
            textColor: 255,
            fontStyle: 'bold',
            fontSize: 9
        },
        columnStyles: {
            0: { cellWidth: 10 },  // No
            1: { cellWidth: 30 },  // Invoice
            2: { cellWidth: 25 },  // Date
            3: { cellWidth: 25 },  // Time
            4: { cellWidth: 15 },  // Items
            5: { cellWidth: 30 },  // Payment Method
            6: { cellWidth: 30, halign: 'right' }  // Total
        },
        alternateRowStyles: {
            fillColor: [245, 247, 250]
        },
        margin: { top: 40 }
    });

    
    const pageCount = doc.internal.getNumberOfPages();
    const totalAmount = records.reduce((sum, r) => sum + (Number(r.grand_total) || 0), 0);
    
    const finalY = doc.lastAutoTable.finalY + 10;
    doc.setFontSize(10);
    doc.setFont('helvetica', 'bold');
    doc.text(`Grand Total: Rp ${totalAmount.toLocaleString('id-ID')}`, 140, finalY);
    
    for (let i = 1; i <= pageCount; i++) {
        doc.setPage(i);
        doc.setFontSize(8);
        doc.setFont('helvetica', 'normal');
        doc.text(
            `Page ${i} of ${pageCount} | Generated: ${new Date().toLocaleString('id-ID')}`,
            14,
            doc.internal.pageSize.height - 10
        );
    }
    
    const fileName = `transaction_report_${new Date().toISOString().split('T')[0]}.pdf`;
    doc.save(fileName);
}

function formatRupiahPDF(amount) {
    return 'Rp ' + Number(amount).toLocaleString('id-ID');
}

document.getElementById('printBtn').addEventListener('click', () => window.print());

document.getElementById('closingBtn').addEventListener('click', async function(e) {
    e.preventDefault();
    
    const result = await Swal.fire({
        title: 'Closing Transaksi?',
        text: "Pastikan data sudah benar. Transaksi akan ditutup!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Ya, Closing!',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#d33',
        reverseButtons: true
    });

    if (!result.isConfirmed) return;

    document.getElementById('loadingclosingBtn').style.display = 'block';
    document.getElementById('closingBtn').style.display = 'none';

    try {
        const response = await fetch('<?= route('closing.transaction')?>', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '<?= csrfHeader()?>',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const data = await response.json();

        document.getElementById('loadingclosingBtn').style.display = 'none';
        document.getElementById('closingBtn').style.display = 'block';

        if (response.status === 201 || data.statusCode === 201 || data.success) {
            await Swal.fire({
                title: 'Success!',
                icon: 'success',
                text: data.message || 'Closing Transaksi sukses',
                confirmButtonText: 'OK'
            });
            window.location.href = '<?= route('view.report')?>';
        } else {
            throw new Error(data.message || 'Gagal closing transaksi');
        }
    } catch (error) {
        document.getElementById('loadingclosingBtn').style.display = 'none';
        document.getElementById('closingBtn').style.display = 'block';
        Swal.fire({ title: 'Error', icon: 'error', text: error.message || 'Terjadi kesalahan' });
    }
});

// Toggle Transaction Records
let isTransactionCollapsed = false;

document.getElementById('toggleTransactionBtn').addEventListener('click', function() {
    const content = document.getElementById('transactionContent');
    const toggleIcon = document.getElementById('toggleIcon');
    
    isTransactionCollapsed = !isTransactionCollapsed;
    
    if (isTransactionCollapsed) {
        // Collapse - sembunyikan konten
        content.style.display = 'none';
        // Rotasi icon ke kanan (chevron right)
        toggleIcon.innerHTML = '<polyline points="9 18 15 12 9 6"/>';
        // Simpan status ke localStorage
        localStorage.setItem('transactionCollapsed', 'true');
    } else {
        // Expand - tampilkan konten
        content.style.display = 'block';
        // Rotasi icon ke bawah (chevron down)
        toggleIcon.innerHTML = '<polyline points="6 9 12 15 18 9"/>';
        // Simpan status ke localStorage
        localStorage.setItem('transactionCollapsed', 'false');
    }
});

document.addEventListener('DOMContentLoaded', function() {
    fetchReportData();
    const savedState = localStorage.getItem('transactionCollapsed');
    const content = document.getElementById('transactionContent');
    const toggleIcon = document.getElementById('toggleIcon');
    
    if (savedState === 'true') {
        isTransactionCollapsed = true;
        content.style.display = 'none';
        toggleIcon.innerHTML = '<polyline points="9 18 15 12 9 6"/>';
    } else {
        isTransactionCollapsed = false;
        content.style.display = 'block';
        toggleIcon.innerHTML = '<polyline points="6 9 12 15 18 9"/>';
    }
});
</script>