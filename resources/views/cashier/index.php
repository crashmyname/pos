<div class="container-xl py-4">

    <div class="row">

        <!-- LEFT -->
        <div class="col-lg-6">

            <!-- SEARCH PRODUCT -->
            <div class="card pos-card mb-3">

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                Scan Product
                            </label>
        
                            <input type="text"
                                   id="scan-product"
                                   class="form-control form-control"
                                   placeholder="Search product or barcode">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">
                                Search Product
                            </label>
        
                            <input type="text"
                                   id="search-product"
                                   class="form-control form-control"
                                   placeholder="Search product or barcode">
        
                            <div class="product-search-list"
                                 id="product-results">
        
                            </div>
                        </div>
                    </div>


                </div>

            </div>

            <!-- QUICK PRODUCT -->
            <div class="card pos-card">

                <div class="card-header">
                    <h3 class="card-title">
                        Quick Products
                    </h3>
                </div>

                <div class="card-body">

                    <div class="row g-2"
                         id="quick-products">

                    </div>

                </div>

            </div>

        </div>

        <!-- RIGHT -->
        <div class="col-lg-6">
            <!-- MEMBER -->
            <div class="card pos-card mb-2">
                <div class="card-body py-2">
                    <label class="form-label small fw-bold mb-1">
                        Member ID / NIK
                    </label>
                    <div class="position-relative">
                        <div class="input-group">
                            <input type="text"
                                id="member-input"
                                class="form-control"
                                placeholder="Scan member card / phone">
                            <button class="btn btn-outline-success" 
                                type="button" 
                                id="btn-search-member"
                                title="Search Member">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-users"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M5 7a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M3 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /><path d="M16 3.13a4 4 0 0 1 0 7.75" /><path d="M21 21v-2a4 4 0 0 0 -3 -3.85" /></svg>
                            </button>
                        </div>
                        <span class="member-status-icon"
                            id="member-status-icon">
                        </span>
                    </div>
                    <!-- MEMBER INFO -->
                    <div id="member-box"
                        class="member-mini d-none mt-2">
                    </div>
                </div>
            </div>

            <div class="card pos-card cart-card">

                <!-- CART HEADER -->
                <div class="card-header">

                    <div class="d-flex justify-content-between w-100">

                        <div>
                            <h3 class="card-title mb-1">
                                Shopping Cart
                            </h3>

                            <div class="text-secondary small">
                                Active transaction
                            </div>
                        </div>

                        <div>
                            <span class="badge bg-primary-lt fs-5"
                                  id="total-items">
                                0 Items
                            </span>
                        </div>

                    </div>

                </div>

                <!-- CART -->
                <div class="table-responsive">

                    <table class="table table-vcenter">

                        <thead>

                            <tr>
                                <th>Product</th>
                                <th width="120">Qty</th>
                                <th width="140">Price</th>
                                <th width="150">Subtotal</th>
                                <th width="50"></th>
                            </tr>

                        </thead>

                        <tbody id="cart-body">

                            <tr>
                                <td colspan="5">

                                    <div class="empty-cart">

                                        <div class="empty-cart-icon">
                                            🛒
                                        </div>

                                        <div class="fw-bold">
                                            Cart Empty
                                        </div>

                                        <div class="small text-secondary">
                                            Scan or search products
                                        </div>

                                    </div>

                                </td>
                            </tr>

                        </tbody>

                    </table>

                </div>

                <!-- FOOTER -->
                <div class="card-body border-top">

                    <div class="row">

                        <div class="col-md-5 ms-auto">

                            <table class="table table-sm table-borderless">

                                <tr>
                                    <td>Subtotal</td>

                                    <td class="text-end fw-bold"
                                        id="subtotal">
                                        Rp 0
                                    </td>
                                </tr>

                                <tr>
                                    <td>Discount</td>

                                    <td class="text-end text-danger"
                                        id="discount">
                                        Rp 0
                                    </td>
                                </tr>

                                <tr class="fs-2 fw-bold">

                                    <td>Total</td>

                                    <td class="text-end text-primary"
                                        id="grand-total">
                                        Rp 0
                                    </td>

                                </tr>

                            </table>

                            <div class="d-grid gap-2">

                                <button class="btn btn-success btn-lg"
                                        id="btn-payment">

                                    PAY NOW

                                </button>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<div class="modal modal-blur fade"
     id="paymentModal"
     tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header">

                <h5 class="modal-title">
                    Payment
                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <!-- BODY -->
            <div class="modal-body">

                <!-- TOTAL -->
                <div class="text-center mb-4">

                    <div class="small text-secondary">
                        Grand Total
                    </div>

                    <div class="display-5 fw-bold text-primary"
                         id="payment-total">
                        Rp 0
                    </div>

                </div>

                <!-- PAYMENT METHOD -->
                <div class="row g-3 mb-4">

                    <div class="col-md-4">

                        <div class="payment-method active"
                             onclick="selectPayment('cash')">

                            <div class="payment-icon">
                                💵
                            </div>

                            <div class="fw-bold">
                                Cash
                            </div>

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="payment-method"
                             onclick="selectPayment('qris')">

                            <div class="payment-icon">
                                📱
                            </div>

                            <div class="fw-bold">
                                QRIS
                            </div>

                        </div>

                    </div>

                    <div class="col-md-4">

                        <div class="payment-method"
                             onclick="selectPayment('credit')">

                            <div class="payment-icon">
                                🏦
                            </div>

                            <div class="fw-bold">
                                Credit
                            </div>

                        </div>

                    </div>

                </div>

                <!-- CASH -->
                <div id="cash-section">

                    <label class="form-label">
                        Cash Amount
                    </label>

                    <input type="number"
                           class="form-control"
                           id="cash-amount"
                           placeholder="Input cash">

                    <div class="mt-3">

                        <div class="small text-secondary">
                            Change
                        </div>

                        <div class="fs-2 fw-bold text-success"
                            id="cash-change">
                            Rp 0
                        </div>

                    </div>

                    <!-- QUICK CASH -->
                    <div class="row g-2 mt-3">

                        <div class="col-4">

                            <button class="btn btn-outline-primary w-100"
                                    onclick="setCash(50000)">

                                50K

                            </button>

                        </div>

                        <div class="col-4">

                            <button class="btn btn-outline-primary w-100"
                                    onclick="setCash(100000)">

                                100K

                            </button>

                        </div>

                        <div class="col-4">

                            <button class="btn btn-outline-success w-100"
                                    onclick="setCash(calculateTotal())">

                                UANG PAS

                            </button>

                        </div>

                    </div>

                </div>

                <!-- QRIS -->
                <div id="qris-section"
                     class="d-none">

                    <div class="text-center">

                        <img src="" id="qrisdinamis"
                             class="img-fluid rounded border" width="200px">

                        <div class="mt-3 text-secondary">
                            Scan QRIS to pay
                        </div>

                    </div>

                </div>

                <!-- CREDIT -->
                <div id="credit-section"
                     class="d-none">

                    <div class="alert alert-info">

                        <div class="fw-bold">
                            Cooperative Credit Limit
                        </div>

                        <div>
                            Monthly Limit : Rp 1.000.000
                        </div>

                        <div>
                            Used : Rp 650.000
                        </div>

                        <div>
                            Remaining : Rp 350.000
                        </div>

                    </div>

                    <div id="credit-status">

                    </div>

                </div>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer">
                <button class="btn btn-success btn-lg w-100"
                        id="btn-complete-payment">
                    COMPLETE PAYMENT
                </button>
            </div>
        </div>
    </div>
</div>
<!-- RECEIPT PAGE -->
<div id="receipt-page"
     class="d-none">
    <div class="receipt-wrapper">
        <div id="receipt">
            <!-- HEADER -->
            <div class="receipt-center">
                <div class="receipt-store">
                    KOPERASI KARYAWAN
                </div>
                <div>
                    Jl Industri No 88
                </div>
                <div>
                    Telp 0812-0000-0000
                </div>
            </div>
            <div class="receipt-divider"></div>
            <!-- INFO -->
            <table class="receipt-table">
                <tr>
                    <td>Invoice</td>
                    <td class="text-end"
                        id="receipt-invoice">
                    </td>
                </tr>
                <tr>
                    <td>Date</td>
                    <td class="text-end"
                        id="receipt-date">
                    </td>
                </tr>
                <tr>
                    <td>Payment</td>
                    <td class="text-end"
                        id="receipt-payment">
                    </td>
                </tr>
            </table>
            <div class="receipt-divider"></div>
            <!-- ITEMS -->
            <div id="receipt-items">
            </div>
            <div class="receipt-divider"></div>
            <!-- PAYMENT DETAIL -->
            <table class="receipt-table">
                <tr>
                    <td>Subtotal</td>
                    <td class="text-end"
                        id="receipt-subtotal">
                    </td>
                </tr>
                <tr>
                    <td>Discount</td>
                    <td class="text-end"
                        id="receipt-discount">
                        Rp 0
                    </td>
                </tr>
                <tr>
                    <td>Total</td>
                    <td class="text-end fw-bold"
                        id="receipt-total">
                    </td>
                </tr>
                <tr>
                    <td>Pay</td>
                    <td class="text-end"
                        id="receipt-pay">
                    </td>
                </tr>
                <tr>
                    <td>Change</td>
                    <td class="text-end"
                        id="receipt-change">
                    </td>
                </tr>
                <tr>
                    <td>Charge</td>
                    <td class="text-end"
                        id="receipt-charge">
                        Rp 0
                    </td>
                </tr>
            </table>
            <div class="receipt-divider"></div>
            <div id="receipt-member">
            </div>
            <!-- FOOTER -->
            <div class="receipt-center">
                <div class="fw-bold">
                    TERIMA KASIH
                </div>
                <div class="receipt-note">
                    BELANJA ANDA GRATIS
                    <br>
                    JIKA TIDAK MENERIMA STRUK
                </div>
            </div>
        </div>
        <!-- BUTTON -->
        <div class="receipt-action">
            <button class="btn btn-primary"
                    onclick="window.print()">
                PRINT RECEIPT
            </button>
            <button class="btn btn-success"
                    onclick="newTransaction()">
                NEW TRANSACTION
            </button>
        </div>
    </div>
</div>
<!-- HOLD BILL MODAL -->
<div class="modal modal-blur fade"
     id="holdModal"
     tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <!-- HEADER -->
            <div class="modal-header">
                <h5 class="modal-title">
                    Hold Bills
                </h5>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>
            <!-- BODY -->
            <div class="modal-body">
                <!-- SAVE HOLD -->
                <div class="card border-0 bg-light mb-4">
                    <div class="card-body">
                        <label class="form-label fw-bold">
                            Hold Name
                        </label>
                        <div class="d-flex gap-2">
                            <input type="text"
                                   class="form-control"
                                   id="hold-name"
                                   placeholder="Example : Table 1 / Customer Name">
                            <button class="btn btn-warning"
                                    onclick="saveHoldBill()">
                                SAVE HOLD
                            </button>
                        </div>
                    </div>
                </div>
                <!-- HOLD LIST -->
                <div>
                    <div class="fw-bold mb-3">
                        Saved Hold Bills
                    </div>
                    <div id="hold-list">
                        <div class="text-secondary text-center py-4">
                            No hold bills
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- TRANSACTION MODAL -->
<div class="modal modal-blur fade"
     id="transactionModal"
     tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <!-- HEADER -->
            <div class="modal-header">
                <h5 class="modal-title">
                    Transaction History
                </h5>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>
            <!-- BODY -->
            <div class="modal-body">
                <!-- FILTER -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <input type="text"
                               class="form-control"
                               id="search-transaction"
                               placeholder="Search invoice">
                    </div>
                    <div class="col-md-3">
                        <select class="form-select"
                                id="filter-payment">
                            <option value="">
                                All Payment
                            </option>
                            <option value="cash">
                                CASH
                            </option>
                            <option value="qris">
                                QRIS
                            </option>
                            <option value="credit">
                                CREDIT
                            </option>
                        </select>
                    </div>
                </div>
                <!-- TABLE -->
                <div class="table-responsive">
                    <table class="table table-vcenter">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Date</th>
                                <th>Payment</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th width="220">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody id="transaction-table">
                            <tr>
                                <td colspan="6"
                                    class="text-center text-secondary py-4">
                                    No transactions
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- RETURN MODAL -->
<div class="modal modal-blur fade"
     id="returnModal"
     tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <!-- HEADER -->
            <div class="modal-header">
                <h5 class="modal-title">
                    Return Transaction
                </h5>
                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>
            <!-- BODY -->
            <div class="modal-body">
                <!-- INFO -->
                <div class="card bg-light border-0 mb-4">
                    <div class="card-body">
                        <div class="small text-secondary">
                            Invoice
                        </div>
                        <div class="fw-bold fs-3"
                             id="return-invoice">
                        </div>
                    </div>
                </div>
                <!-- RETURN ITEMS -->
                <div id="return-items">
                </div>
                <!-- NOTE -->
                <div class="mt-4">
                    <label class="form-label fw-bold">
                        Return Reason
                    </label>
                    <textarea class="form-control"
                              id="return-note"
                              rows="3"
                              placeholder="Input return reason"></textarea>
                </div>
            </div>
            <!-- FOOTER -->
            <div class="modal-footer">
                <button class="btn btn-danger"
                        onclick="processReturn()">
                    PROCESS RETURN
                </button>
            </div>
        </div>
    </div>
</div>
<!-- RETURN MODAL -->
<div class="modal modal-blur fade"
     id="returnModal"
     tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-centered">

        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header">

                <h5 class="modal-title">

                    Return Transaction

                </h5>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <!-- BODY -->
            <div class="modal-body">

                <!-- INFO -->
                <div class="card bg-light border-0 mb-4">

                    <div class="card-body">

                        <div class="small text-secondary">
                            Invoice
                        </div>

                        <div class="fw-bold fs-3"
                             id="return-invoice">
                        </div>

                    </div>

                </div>

                <!-- RETURN ITEMS -->
                <div id="return-items">

                </div>

                <!-- NOTE -->
                <div class="mt-4">

                    <label class="form-label fw-bold">

                        Return Reason

                    </label>

                    <textarea class="form-control"
                              id="return-note"
                              rows="3"
                              placeholder="Input return reason"></textarea>

                </div>

            </div>

            <!-- FOOTER -->
            <div class="modal-footer">

                <button class="btn btn-danger"
                        onclick="processReturn()">

                    PROCESS RETURN

                </button>

            </div>

        </div>

    </div>

</div>
<div class="modal modal-blur fade"
     id="returnListModal"
     tabindex="-1">

    <div class="modal-dialog modal-xl modal-dialog-centered">

        <div class="modal-content">

            <!-- HEADER -->
            <div class="modal-header">

                <h3 class="modal-title">

                    Return Transaction

                </h3>

                <button type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                </button>

            </div>

            <!-- BODY -->
            <div class="modal-body">

                <div class="table-responsive">

                    <table class="table table-vcenter">

                        <thead>

                            <tr>

                                <th>Invoice</th>
                                <th>Date</th>
                                <th>Payment</th>
                                <th>Total</th>
                                <th width="120">Action</th>

                            </tr>

                        </thead>

                        <tbody id="return-transaction-table">

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

<style>

body{
    background:#f4f7fb;
}

.pos-card{
    border:0;
    border-radius:6px;
    box-shadow:0 10px 30px rgba(0,0,0,.06);
}

.form-control-lg{
    height:58px;
    border-radius:4px;
    border:1px solid #dce1ea;
}

.form-control-lg:focus{
    border-color:#206bc4;
    box-shadow:0 0 0 4px rgba(32,107,196,.1);
}

.product-search-list{
    max-height:300px;
    overflow:auto;
    margin-top:10px;
}

.product-item{
    padding:12px;
    border-radius:4px;
    cursor:pointer;
    transition:.2s;
    border:1px solid transparent;
}

.product-item:hover{
    background:#f8fbff;
    border-color:#206bc4;
}

.quick-product{
    border-radius:4px;
    padding:14px;
    background:#fff;
    border:1px solid #edf1f7;
    cursor:pointer;
    transition:.2s;
}

.quick-product:hover{
    transform:translateY(-3px);
    box-shadow:0 10px 25px rgba(0,0,0,.08);
}

.cart-card{
    min-height:760px;
}

.cart-item:hover{
    background:#f9fbff;
}

.qty-box{
    width:70px;
    border-radius:4px;
    text-align:center;
}

.empty-cart{
    text-align:center;
    padding:80px 20px;
}

.empty-cart-icon{
    font-size:70px;
    margin-bottom:10px;
}

.member-box{
    background:#e8fff1;
    border:1px solid #b7f0cd;
    border-radius:4px;
    padding:15px;
    margin-top:15px;
}

.remove-btn{
    width:34px;
    height:34px;
    border-radius:10px;
    border:0;
    background:#ffefef;
    color:#d63939;
}

.remove-btn:hover{
    background:#d63939;
    color:#fff;
}

.payment-method{
    border:2px solid #e9edf3;
    border-radius:14px;
    padding:20px;
    text-align:center;
    cursor:pointer;
    transition:.2s;
}

.payment-method:hover{
    border-color:#206bc4;
}

.payment-method.active{
    border-color:#206bc4;
    background:#f4f8ff;
}

.payment-icon{
    font-size:34px;
    margin-bottom:10px;
}

.receipt-wrapper{
    background:#f1f5f9;
    min-height:100vh;
    padding:40px;
    display:flex;
    flex-direction:column;
    align-items:center;
}

#receipt{

    width:40mm;
    background:#fff;

    padding:10px;

    font-family:monospace;
    font-size:10px;

    color:#000;

}

.receipt-center{
    text-align:center;
}

.receipt-store{
    font-size:13px;
    font-weight:bold;
}

.receipt-divider{
    border-top:1px dashed #000;
    margin:6px 0;
}

.receipt-table{
    width:100%;
}

.receipt-table td{
    padding:1px 0;
}

.receipt-item{
    margin-bottom:6px;
}

.receipt-note{
    margin-top:8px;
    font-size:9px;
    font-weight:bold;
}

.receipt-action{
    margin-top:20px;
    display:flex;
    gap:10px;
}

@media print{

    body *{
        visibility:hidden;
    }

    #receipt,
    #receipt *{
        visibility:visible;
    }

    #receipt{
        position:absolute;
        left:0;
        top:0;
    }

    @page{
        size:40mm 110mm;
        margin:0;
    }

}

.member-mini{
    background:#f4fff8;
    border:1px solid #b8ebc9;
    border-radius:8px;
    padding:10px;
    font-size:12px;
}

</style>
<script src="<?= asset_v('js/custom-alert.js')?>"></script>
<script>
const Alert = new CustomAlert();

let selectedMember = null;
const SECRET_KEY = '<?= env('SECRET_KEY')?>';

function format(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

// Fungsi untuk menampilkan member
function displayMember(member) {
    let memberBox = document.getElementById('member-box');
    let icon = document.getElementById('member-status-icon');
    icon.innerHTML = '';

    if (!member) {
        selectedMember = null;
        memberBox.classList.remove('d-none');
        memberBox.innerHTML = `
            <div class="text-danger fw-bold">
                Member Not Found
            </div>
            <div class="small text-secondary">
                Invalid member card or phone number
            </div>
        `;
        return;
    }

    // MEMBER NON ACTIVE
    if (member.status !== 'active') {
        selectedMember = null;
        memberBox.classList.remove('d-none');
        memberBox.innerHTML = `
            <div class="text-danger fw-bold">
                Member Non Active
            </div>
            <div class="small text-secondary">
                Membership already inactive
            </div>
        `;
        return;
    }

    // ACTIVE MEMBER
    selectedMember = member;
    let remaining = member.limit - member.used;
    memberBox.classList.remove('d-none');
    memberBox.innerHTML = `
        <div class="d-flex justify-content-between">
            <div>
                <div class="fw-bold text-success">
                    ${member.name}
                </div>
                <div class="small text-secondary">
                    Cashback : Rp ${format(member.cashback)}
                </div>
            </div>
            <div class="text-end">
                <div class="small text-secondary">
                    Credit Limit
                </div>
                <div class="fw-bold">
                    Rp ${format(remaining)}
                </div>
            </div>
        </div>
    `;
}

// Fungsi untuk search member via API
async function searchMemberAPI(value) {
    let memberBox = document.getElementById('member-box');
    let icon = document.getElementById('member-status-icon');
    
    // Tampilkan loading
    icon.innerHTML = '<i class="bi bi-hourglass-split spinner"></i>';
    memberBox.classList.add('d-none');
    
    try {
        const response = await fetch('https://koperasi-stanley.com/api/v1/member', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                member: value,
                key: SECRET_KEY
            })
        });

        const result = await response.json();
        icon.innerHTML = '';

        if (response.status === 401) {
            // Unauthorized
            memberBox.classList.remove('d-none');
            memberBox.innerHTML = `
                <div class="text-danger fw-bold">
                    Unauthorized
                </div>
                <div class="small text-secondary">
                    ${result.message}
                </div>
            `;
            return;
        }

        if (result.status === 200 && result.data) {
            // Mapping response API ke format member
            // Sesuaikan dengan struktur data dari API Anda
            const member = {
                code: result.data.username || result.data.uuid,
                name: result.data.name,
                cashback: result.data.cashback || 0,
                limit: result.data.limit || 0,
                used: result.data.used || 0,
                status: result.data.status || 'active'
            };
            displayMember(member);
        } else {
            // Data not found
            displayMember(null);
        }
    } catch (error) {
        console.error('Error searching member:', error);
        icon.innerHTML = '<i class="bi bi-exclamation-triangle text-warning"></i>';
        memberBox.classList.remove('d-none');
        memberBox.innerHTML = `
            <div class="text-warning fw-bold">
                Connection Error
            </div>
            <div class="small text-secondary">
                Failed to connect to server
            </div>
        `;
    }
}

// Event listener untuk tombol search
document.getElementById('btn-search-member').addEventListener('click', function() {
    let value = document.getElementById('member-input').value.trim();
    
    if (value === '') {
        selectedMember = null;
        document.getElementById('member-box').classList.remove('d-none');
        document.getElementById('member-box').innerHTML = `
            <div class="text-warning fw-bold">
                Empty Input
            </div>
            <div class="small text-secondary">
                Please enter member code or phone number
            </div>
        `;
        document.getElementById('member-status-icon').innerHTML = '';
        return;
    }
    
    searchMemberAPI(value);
});

// Event listener untuk Enter key
document.getElementById('member-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        let value = this.value.trim();
        if (value !== '') {
            searchMemberAPI(value);
        } else {
            selectedMember = null;
            document.getElementById('member-box').classList.remove('d-none');
            document.getElementById('member-box').innerHTML = `
                <div class="text-warning fw-bold">
                    Empty Input
                </div>
                <div class="small text-secondary">
                    Please enter member code or phone number
                </div>
            `;
            document.getElementById('member-status-icon').innerHTML = '';
        }
    }
});

// Event listener untuk clear input
document.getElementById('member-input').addEventListener('input', function() {
    if (this.value.trim() === '') {
        selectedMember = null;
        document.getElementById('member-box').classList.add('d-none');
        document.getElementById('member-status-icon').innerHTML = '';
    }
});

let products = [];
let cart = [];
let holdBills = [];
let transactions = [];
let returnTransactionIndex = null;
let returns = [];

let productPagination = {
    currentPage: 0,
    lastPage: 1,
    total: 0,
    isLoading: false,
    allLoaded: false,
    perPage: 25
};

// ==========================================
// HELPER FUNCTIONS
// ==========================================
function findProductById(id) {
    return products.find(p => p.id == id);
}

function addProductToList(product) {
    if (!products.find(p => p.id == product.id)) {
        products.push(product);
    }
}

function format(number) {
    return new Intl.NumberFormat('id-ID').format(number);
}

// ==========================================
// FETCH PRODUCTS
// ==========================================
async function fetchProducts(page = 1) {
    if (productPagination.isLoading) return;
    if (productPagination.allLoaded && page > 1) return;
    
    productPagination.isLoading = true;

    const loadMoreBtn = document.getElementById('btnLoadMore');
    if (loadMoreBtn) {
        loadMoreBtn.innerHTML = '⏳ Loading...';
        loadMoreBtn.disabled = true;
    }
    
    try {
        const response = await fetch(`<?= route('data.cashier.product') ?>?page=${page}`);
        const result = await response.json();
        
        if (result.data && result.data.length > 0) {
            const newProducts = result.data.map(item => ({
                id: item.id,
                barcode: item.qrcode,
                name: item.name,
                price: parseFloat(item.sell_price) || 0
            }));
            
            const existingIds = new Set(products.map(p => p.id));
            const uniqueNew = newProducts.filter(p => !existingIds.has(p.id));
            products = products.concat(uniqueNew);
            
            productPagination.currentPage = result.pagination.current_page;
            productPagination.lastPage = result.pagination.last_page;
            productPagination.total = result.pagination.total;
            
            if (result.pagination.current_page >= result.pagination.last_page) {
                productPagination.allLoaded = true;
            }
        } else {
            productPagination.allLoaded = true;
        }
        
        renderQuickProducts();
        
    } catch (error) {
        console.error('Error fetching products:', error);
    } finally {
        productPagination.isLoading = false;
    }
}

async function loadMoreProducts() {
    if (productPagination.allLoaded) return;
    if (productPagination.isLoading) return;
    
    const nextPage = productPagination.currentPage + 1;
    await fetchProducts(nextPage);
}

// ==========================================
// RENDER QUICK PRODUCTS
// ==========================================
function renderQuickProducts() {
    let html = '';
    
    products.forEach(product => {
        html += `
            <div class="col-6">
                <div class="quick-product" onclick="addToCart(${product.id})">
                    <div class="fw-bold">${product.name}</div>
                    <div class="small text-secondary">${product.barcode}</div>
                    <div class="mt-2 fw-bold text-primary">Rp ${format(product.price)}</div>
                </div>
            </div>
        `;
    });
    
    if (!productPagination.allLoaded) {
        html += `<div id="scroll-sentinel" style="height:1px;"></div>`;
    }
    
    if (!productPagination.allLoaded && products.length > 0) {
        html += `
            <div class="col-12 text-center mt-2 mb-3">
                <button class="btn btn-sm btn-outline-primary" 
                        onclick="loadMoreProducts()" 
                        id="btnLoadMore"
                        ${productPagination.isLoading ? 'disabled' : ''}>
                    ${productPagination.isLoading ? '⏳ Loading...' : `📥 Load More (${products.length}/${productPagination.total})`}
                </button>
            </div>
        `;
    }
    
    if (productPagination.allLoaded && products.length > 0) {
        html += `
            <div class="col-12 text-center mt-1 mb-2">
                <small class="text-success">${products.length} produk dimuat</small>
            </div>
        `;
    }
    
    document.getElementById('quick-products').innerHTML = html;
    setupInfiniteScroll();
}

// ==========================================
// SEARCH PRODUCTS
// ==========================================
let searchTimeout;

document.getElementById('search-product').addEventListener('keyup', function() {
    const keyword = this.value.trim();
    
    if (keyword === '') {
        document.getElementById('product-results').innerHTML = '';
        return;
    }

    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        searchProducts(keyword);
    }, 300);
});

async function searchProducts(keyword) {
    keyword = keyword.toLowerCase().trim();
    
    if (keyword === '') return;

    document.getElementById('product-results').innerHTML = `
        <div class="text-center p-2">
            <small class="text-muted">🔍 Mencari...</small>
        </div>
    `;
    
    try {
        const response = await fetch(`<?= route('data.cashier.product') ?>?search=${encodeURIComponent(keyword)}&per_page=20`);
        const result = await response.json();
        
        if (result.data && result.data.length > 0) {
            const searchResults = result.data.map(item => ({
                id: item.id,
                barcode: item.qrcode,
                name: item.name,
                price: parseFloat(item.sell_price) || 0
            }));
            
            // Simpan hasil search ke products[] biar addToCart bisa nemu
            searchResults.forEach(p => addProductToList(p));
            
            renderSearchResults(searchResults);
        } else {
            renderSearchResults([]);
        }
        
    } catch (error) {
        console.error('Search error:', error);
        renderSearchResults([]);
    }
}

function renderSearchResults(results) {
    let html = '';
    
    if (results.length > 0) {
        results.forEach(product => {
            html += `
                <div class="product-item" onclick="addToCart(${product.id})" style="cursor:pointer;">
                    <div class="d-flex justify-content-between">
                        <div>
                            <div class="fw-bold">${product.name}</div>
                            <div class="small text-secondary">${product.barcode}</div>
                        </div>
                        <div class="fw-bold text-primary">Rp ${format(product.price)}</div>
                    </div>
                </div>
            `;
        });
    } else {
        html = `
            <div class="text-center p-2">
                <small class="text-muted">❌ Produk tidak ditemukan</small>
            </div>
        `;
    }
    
    document.getElementById('product-results').innerHTML = html;
}

// ==========================================
// ADD TO CART
// ==========================================
function addToCart(productId) {
    let product = findProductById(productId);
    
    // Kalau produk ga ketemu di array, fetch dulu dari backend
    if (!product) {
        fetchAndAddToCart(productId);
        return;
    }
    
    // Cek apakah sudah ada di cart
    let existingItem = cart.find(item => item.id == productId);
    
    if (existingItem) {
        existingItem.qty += 1;
        existingItem.subtotal = existingItem.qty * existingItem.price;
    } else {
        cart.push({
            id: product.id,
            barcode: product.barcode,
            name: product.name,
            price: product.price,
            qty: 1,
            subtotal: product.price
        });
    }
    
    renderCart();
    calculateTotal();
}

async function fetchAndAddToCart(productId) {
    try {
        const response = await fetch(`<?= route('data.cashier.product') ?>?search=${productId}&per_page=1`);
        const result = await response.json();
        
        if (result.data && result.data.length > 0) {
            const item = result.data[0];
            
            const product = {
                id: item.id,
                barcode: item.qrcode,
                name: item.name,
                price: parseFloat(item.sell_price) || 0
            };
            
            // Simpan ke products[]
            addProductToList(product);
            
            // Tambah ke cart
            let existingItem = cart.find(cartItem => cartItem.id == product.id);
            
            if (existingItem) {
                existingItem.qty += 1;
                existingItem.subtotal = existingItem.qty * existingItem.price;
            } else {
                cart.push({
                    id: product.id,
                    barcode: product.barcode,
                    name: product.name,
                    price: product.price,
                    qty: 1,
                    subtotal: product.price
                });
            }
            
            renderCart();
            calculateTotal();
            renderQuickProducts();
        } else {
            Alert.warning('Produk tidak ditemukan');
        }
    } catch (error) {
        console.error('Error:', error);
        Alert.warning('Gagal menambahkan produk');
    }
}

// ==========================================
// SCAN PRODUCT
// ==========================================
document.getElementById('scan-product').addEventListener('keydown', async function(e) {
    if (e.key !== 'Enter') return;
    
    e.preventDefault();
    let keyword = this.value.trim();
    
    if (keyword === '') return;
    
    // Cari di products[] lokal dulu
    let product = products.find(p => 
        p.barcode === keyword || 
        p.name.toLowerCase() === keyword.toLowerCase()
    );
    
    if (product) {
        addToCart(product.id);
        this.value = '';
        document.getElementById('product-results').innerHTML = '';
        return;
    }
    
    // Kalau ga ada, cari dari backend
    try {
        const response = await fetch(`<?= route('data.cashier.product') ?>?search=${encodeURIComponent(keyword)}&per_page=1`);
        const result = await response.json();
        
        if (result.data && result.data.length > 0) {
            const item = result.data[0];
            
            const newProduct = {
                id: item.id,
                barcode: item.qrcode,
                name: item.name,
                price: parseFloat(item.sell_price) || 0
            };

            addProductToList(newProduct);
            renderQuickProducts();
            addToCart(item.id);
            
            this.value = '';
            document.getElementById('product-results').innerHTML = '';
        } else {
            Alert.warning('Produk tidak ditemukan');
        }
    } catch (error) {
        Alert.warning('Gagal mencari produk');
    }
});

// ==========================================
// RENDER CART
// ==========================================
function renderCart() {
    let html = '';
    
    if (cart.length === 0) {
        html = `
            <div class="text-center p-3 text-muted">
                <div style="font-size:30px;">🛒</div>
                <small>Keranjang kosong</small>
            </div>
        `;
    } else {
        cart.forEach((item, index) => {
            html += `
                <div class="cart-item d-flex justify-content-between align-items-center mb-2 p-2 border rounded">
                    <div style="flex:1; min-width:0;">
                        <div class="fw-bold small text-truncate">${item.name}</div>
                        <small class="text-muted">Rp ${format(item.price)}</small>
                        <div class="d-flex align-items-center mt-1">
                            <button class="btn btn-sm btn-outline-secondary" onclick="updateQty(${index}, -1)">−</button>
                            <span class="mx-2 fw-bold">${item.qty}</span>
                            <button class="btn btn-sm btn-outline-secondary" onclick="updateQty(${index}, 1)">+</button>
                        </div>
                    </div>
                    <div class="text-right ml-2">
                        <div class="fw-bold text-primary">Rp ${format(item.subtotal)}</div>
                        <button class="btn btn-sm text-danger mt-1" onclick="removeFromCart(${index})">✕</button>
                    </div>
                </div>
            `;
        });
    }
    
    document.getElementById('cart-items').innerHTML = html;
}

function updateQty(index, change) {
    cart[index].qty += change;
    
    if (cart[index].qty <= 0) {
        cart.splice(index, 1);
    } else {
        cart[index].subtotal = cart[index].qty * cart[index].price;
    }
    
    renderCart();
    calculateTotal();
}

function removeFromCart(index) {
    cart.splice(index, 1);
    renderCart();
    calculateTotal();
}

function calculateTotal() {
    let total = cart.reduce((sum, item) => sum + item.subtotal, 0);
    document.getElementById('cart-total').textContent = format(total);
}

// ==========================================
// INFINITE SCROLL
// ==========================================
let scrollObserver;

function setupInfiniteScroll() {
    const sentinel = document.getElementById('scroll-sentinel');
    if (!sentinel) return;
    
    if (scrollObserver) scrollObserver.disconnect();
    
    scrollObserver = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !productPagination.allLoaded && !productPagination.isLoading) {
                loadMoreProducts();
            }
        });
    }, {
        rootMargin: '100px'
    });
    
    scrollObserver.observe(sentinel);
}

// ==========================================
// INIT
// ==========================================
async function init() {
    await fetchProducts(1);
}

init();

function addToCart(id){

    let product = products.find(p => p.id === id);

    let existing = cart.find(item => item.id === id);

    if(existing){

        existing.qty += 1;
        existing.subtotal = existing.qty * existing.price;

    }else{

        cart.push({
            ...product,
            qty:1,
            subtotal:product.price
        });

    }

    renderCart();

}

function renderCart(){

    let html = '';

    let subtotal = 0;

    if(cart.length === 0){

        html = `
            <tr>
                <td colspan="5">

                    <div class="empty-cart">

                        <div class="empty-cart-icon">
                            🛒
                        </div>

                        <div class="fw-bold">
                            Cart Empty
                        </div>

                    </div>

                </td>
            </tr>
        `;

    }

    cart.forEach((item,index)=>{

        subtotal += item.subtotal;

        html += `
            <tr class="cart-item">

                <td>

                    <div class="fw-bold">
                        ${item.name}
                    </div>

                    <div class="small text-secondary">
                        ${item.barcode}
                    </div>

                </td>

                <td>

                    <input type="number"
                           min="1"
                           value="${item.qty}"
                           class="form-control qty-box"
                           onchange="changeQty(${index},this.value)">

                </td>

                <td>
                    Rp ${format(item.price)}
                </td>

                <td class="fw-bold">
                    Rp ${format(item.subtotal)}
                </td>

                <td>

                    <button class="remove-btn"
                            onclick="removeItem(${index})">

                        ×

                    </button>

                </td>

            </tr>
        `;

    });

    document.getElementById('cart-body').innerHTML = html;

    document.getElementById('subtotal').innerHTML =
        'Rp ' + format(subtotal);

    document.getElementById('grand-total').innerHTML =
        'Rp ' + format(subtotal);

    document.getElementById('total-items').innerHTML =
        cart.length + ' Items';

}

function changeQty(index,qty){

    qty = parseInt(qty);

    cart[index].qty = qty;

    cart[index].subtotal =
        qty * cart[index].price;

    renderCart();

}

function removeItem(index){

    cart.splice(index,1);

    renderCart();

}

function format(number){

    return number.toLocaleString('id-ID');

}

let selectedPayment = 'cash';

document.getElementById('btn-payment')
.addEventListener('click',function(){

    let total = calculateTotal();

    document.getElementById('payment-total')
    .innerHTML = 'Rp ' + format(total);

    let modal =
        new bootstrap.Modal(
            document.getElementById('paymentModal')
        );
    modal.show();
    generateQRIS(total);
});

async function generateQRIS(amount) {
    try {
        document.getElementById('qrisdinamis').src = '';
        Swal.fire({
            title: 'Generating QR...',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        const response = await fetch('<?= route('qris.generator')?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?= csrfHeader()?>'
            },
            body: JSON.stringify({
                amount: amount,
                invoice_id: 'INV-' + Date.now()
            })
        });

        const data = await response.json();

        if (data.success) {
            document.getElementById('qrisdinamis').src = data.qris_image;
            
            Swal.close();
        } else {
            Swal.fire('Error', 'Gagal generate QRIS', 'error');
        }
        
    } catch (error) {
        console.error('Error:', error);
        Swal.fire('Error', 'Gagal menghubungi server', 'error');
    }
}

function calculateTotal(){

    let total = 0;

    cart.forEach(item => {
        total += item.subtotal;
    });

    return total;

}

function setCash(amount){

    document.getElementById('cash-amount')
    .value = amount;

    let total = calculateTotal();

    let change = amount - total;

    document.getElementById('cash-change')
    .innerHTML =
        'Rp ' + format(change);

}

function selectPayment(method){

    selectedPayment = method;

    document.querySelectorAll('.payment-method')
    .forEach(el => el.classList.remove('active'));

    event.currentTarget.classList.add('active');

    document.getElementById('cash-section')
    .classList.add('d-none');

    document.getElementById('qris-section')
    .classList.add('d-none');

    document.getElementById('credit-section')
    .classList.add('d-none');

    document.getElementById(method + '-section')
    .classList.remove('d-none');

    if(method === 'credit'){
        validateCredit();
    }

}

document.getElementById('cash-amount')
.addEventListener('keyup',function(){

    let total = calculateTotal();

    let cash = parseInt(this.value || 0);

    let change = cash - total;

    document.getElementById('cash-change')
    .innerHTML = 'Rp ' + format(change);

});

function validateCredit(){

    let limit = 1000000;

    let used = 650000;

    let remaining = limit - used;

    let total = calculateTotal();

    if(total > remaining){

        document.getElementById('credit-status')
        .innerHTML = `
            <div class="alert alert-danger">

                Credit limit exceeded

            </div>
        `;

    }else{

        document.getElementById('credit-status')
        .innerHTML = `
            <div class="alert alert-success">

                Credit approved

            </div>
        `;

    }

}

function saveHoldBill(){

    if(cart.length === 0){

        Alert.warning('Cart is empty');
        return;

    }

    let holdName =
        document.getElementById('hold-name').value;

    if(holdName === ''){

        holdName =
            'HOLD-' + Date.now();

    }

    let total = calculateTotal();

    holdBills.push({

        id: Date.now(),
        name: holdName,
        total: total,
        items: JSON.parse(JSON.stringify(cart)),
        created_at: new Date().toLocaleString('id-ID')

    });

    renderHoldBills();

    // RESET CART
    cart = [];

    renderCart();

    document.getElementById('hold-name').value = '';

    Alert.success('Transaction saved to hold');

}

function renderHoldBills(){

    let html = '';

    if(holdBills.length === 0){

        html = `

            <div class="text-secondary text-center py-4">

                No hold bills

            </div>

        `;

    }

    holdBills.forEach((bill,index)=>{

        html += `

            <div class="card mb-2 shadow-sm border-0">

                <div class="card-body">

                    <div class="d-flex justify-content-between">

                        <div>

                            <div class="fw-bold">

                                ${bill.name}

                            </div>

                            <div class="small text-secondary">

                                ${bill.created_at}

                            </div>

                            <div class="mt-1 fw-bold text-primary">

                                Rp ${format(bill.total)}

                            </div>

                        </div>

                        <div class="d-flex gap-2">

                            <button class="btn btn-success btn-sm"
                                    onclick="resumeHold(${index})">

                                RESUME

                            </button>

                            <button class="btn btn-danger btn-sm"
                                    onclick="deleteHold(${index})">

                                DELETE

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        `;

    });

    document.getElementById('hold-list')
    .innerHTML = html;

}

function resumeHold(index){

    cart =
        JSON.parse(
            JSON.stringify(
                holdBills[index].items
            )
        );

    renderCart();

    holdBills.splice(index,1);

    renderHoldBills();

    bootstrap.Modal.getInstance(
        document.getElementById('holdModal')
    ).hide();

}

function deleteHold(index){

    if(confirm('Delete hold bill?')){

        holdBills.splice(index,1);

        renderHoldBills();

    }

}

function generateReceipt(){

    // =========================
    // HIDE POS PAGE
    // =========================
    document.querySelector('.container-xl')
    .classList.add('d-none');

    // =========================
    // HIDE PAYMENT MODAL
    // =========================
    bootstrap.Modal.getInstance(
        document.getElementById('paymentModal')
    ).hide();

    // =========================
    // SHOW RECEIPT PAGE
    // =========================
    document.getElementById('receipt-page')
    .classList.remove('d-none');

    // =========================
    // BASIC DATA
    // =========================
    let total = calculateTotal();

    let invoice =
        'INV-' + Date.now();

    window.currentInvoice = invoice;

    let currentDate =
        new Date().toLocaleString('id-ID');

    // =========================
    // PAYMENT DETAIL
    // =========================
    let payAmount = total;
    let change = 0;
    let charge = 0;

    // CASH
    if(selectedPayment === 'cash'){

        payAmount =
            parseInt(
                document.getElementById('cash-amount').value || 0
            );

        change = payAmount - total;

    }

    // QRIS
    if(selectedPayment === 'qris'){

        payAmount = total;
        change = 0;

        // contoh charge QRIS 0.7%
        charge = Math.round(total * 0.007);

    }

    // CREDIT
    if(selectedPayment === 'credit'){

        payAmount = total;
        change = 0;

    }

    // =========================
    // HEADER INFO
    // =========================
    document.getElementById('receipt-invoice')
    .innerHTML = invoice;

    document.getElementById('receipt-date')
    .innerHTML = currentDate;

    document.getElementById('receipt-payment')
    .innerHTML =
        selectedPayment.toUpperCase();

    // =========================
    // MEMBER INFO
    // =========================
    let memberHtml = '';

    if(selectedMember){

        memberHtml = `

            <div class="receipt-divider"></div>

            <table class="receipt-table">

                <tr>
                    <td>Member</td>

                    <td class="text-end">
                        ${selectedMember.name}
                    </td>
                </tr>

                <tr>
                    <td>Cashback</td>

                    <td class="text-end">
                        Rp ${format(selectedMember.cashback)}
                    </td>
                </tr>

            </table>

        `;

    }

    document.getElementById('receipt-member')
    .innerHTML = memberHtml;

    // =========================
    // RECEIPT ITEMS
    // =========================
    let html = '';

    cart.forEach(item => {

        html += `

            <div class="receipt-item">

                <div>
                    ${item.name}
                </div>

                <div class="d-flex justify-content-between">

                    <span>
                        ${item.qty} x ${format(item.price)}
                    </span>

                    <span>
                        ${format(item.subtotal)}
                    </span>

                </div>

            </div>

        `;

    });

    document.getElementById('receipt-items')
    .innerHTML = html;

    // =========================
    // PAYMENT SUMMARY
    // =========================
    document.getElementById('receipt-subtotal')
    .innerHTML =
        'Rp ' + format(total);

    document.getElementById('receipt-total')
    .innerHTML =
        'Rp ' + format(total + charge);

    document.getElementById('receipt-pay')
    .innerHTML =
        'Rp ' + format(payAmount);

    document.getElementById('receipt-change')
    .innerHTML =
        'Rp ' + format(change);

    document.getElementById('receipt-charge')
    .innerHTML =
        'Rp ' + format(charge);

    // =========================
    // AUTO PRINT
    // =========================
    setTimeout(() => {

        // SAVE TRANSACTION
        transactions.unshift({

            invoice: invoice,
            date: currentDate,
            payment: selectedPayment,
            total: total + charge,
            items: JSON.parse(JSON.stringify(cart))

        });

        renderTransactions();
        window.print();

    },500);

}

function openReturn(index){

    returnTransactionIndex = index;

    let trx = transactions[index];

    document.getElementById('return-invoice')
    .innerHTML = trx.invoice;

    let html = '';

    trx.items.forEach((item,itemIndex)=>{

        html += `

            <div class="card mb-3 border-0 shadow-sm">

                <div class="card-body">

                    <div class="d-flex justify-content-between align-items-center">

                        <div>

                            <div class="fw-bold">

                                ${item.name}

                            </div>

                            <div class="small text-secondary">

                                Qty : ${item.qty}

                            </div>

                            <div class="fw-bold text-primary mt-1">

                                Rp ${format(item.subtotal)}

                            </div>

                        </div>

                        <div style="width:120px;">

                            <label class="small text-secondary">

                                Return Qty

                            </label>

                            <input type="number"
                                   min="0"
                                   max="${item.qty}"
                                   value="0"
                                   class="form-control return-qty"
                                   data-index="${itemIndex}">

                        </div>

                    </div>

                </div>

            </div>

        `;

    });

    document.getElementById('return-items')
    .innerHTML = html;

    new bootstrap.Modal(
        document.getElementById('returnModal')
    ).show();

}

function processReturn(){

    let trx =
        transactions[returnTransactionIndex];

    let qtyInputs =
        document.querySelectorAll('.return-qty');

    let returnedItems = [];

    let totalReturn = 0;

    qtyInputs.forEach(input => {

        let qty =
            parseInt(input.value);

        if(qty > 0){

            let itemIndex =
                input.dataset.index;

            let item =
                trx.items[itemIndex];

            let subtotal =
                qty * item.price;

            totalReturn += subtotal;

            returnedItems.push({

                name: item.name,
                qty: qty,
                subtotal: subtotal

            });

        }

    });

    // VALIDATION
    if(returnedItems.length === 0){

        Alert.warning('No item selected');
        return;

    }

    let note =
        document.getElementById('return-note').value;

    returns.unshift({

        invoice: trx.invoice,
        date: new Date().toLocaleString('id-ID'),
        items: returnedItems,
        total: totalReturn,
        note: note

    });

    // UPDATE TOTAL TRANSACTION
    trx.total =
        trx.total - totalReturn;

    bootstrap.Modal.getInstance(
        document.getElementById('returnModal')
    ).hide();

    renderTransactions();

    Alert.success(

        'Return Success\n' +
        'Total Return : Rp ' +
        format(totalReturn)

    );

}

function renderTransactions(){

    let keyword =
        document.getElementById('search-transaction')
        ? document.getElementById('search-transaction').value.toLowerCase()
        : '';

    let paymentFilter =
        document.getElementById('filter-payment')
        ? document.getElementById('filter-payment').value
        : '';

    let html = '';

    let filtered =
        transactions.filter(trx => {

            let matchInvoice =
                trx.invoice.toLowerCase().includes(keyword);

            let matchPayment =
                paymentFilter === ''
                || trx.payment === paymentFilter;

            return matchInvoice && matchPayment;

        });

    if(filtered.length === 0){

        html = `

            <tr>

                <td colspan="6"
                    class="text-center text-secondary py-4">

                    No transactions

                </td>

            </tr>

        `;

    }

    filtered.forEach((trx,index)=>{

        html += `

            <tr>

                <td class="fw-bold">

                    ${trx.invoice}

                </td>

                <td>

                    ${trx.date}

                </td>

                <td>

                    <span class="badge bg-primary-lt">

                        ${trx.payment.toUpperCase()}

                    </span>

                </td>

                <td>

                    ${trx.items.length} Items

                </td>

                <td>

                    <div class="fw-bold text-primary">

                        Rp ${format(trx.total)}

                    </div>

                    ${
                        returns.find(r => r.invoice === trx.invoice)
                        ? `
                            <span class="badge bg-danger-lt mt-1">

                                RETURNED

                            </span>
                        `
                        : ''
                    }

                </td>

                <td>

                    <div class="d-flex gap-2">

                        <button class="btn btn-primary btn-sm"
                                onclick="detailTransaction(${index})">

                            DETAIL

                        </button>

                        <button class="btn btn-success btn-sm"
                                onclick="reprintTransaction(${index})">

                            PRINT

                        </button>

                        <button class="btn btn-danger btn-sm"
                                onclick="openReturn(${index})">

                            RETURN

                        </button>

                    </div>

                </td>

            </tr>

        `;

    });

    document.getElementById('transaction-table')
    .innerHTML = html;

}
function renderReturnTransactions(){

    let html = '';

    if(transactions.length === 0){

        html = `

            <tr>

                <td colspan="5"
                    class="text-center text-secondary py-4">

                    No Transactions

                </td>

            </tr>

        `;

    }

    transactions.forEach((trx,index)=>{

        html += `

            <tr>

                <td class="fw-bold">

                    ${trx.invoice}

                </td>

                <td>

                    ${trx.date}

                </td>

                <td>

                    ${trx.payment.toUpperCase()}

                </td>

                <td class="fw-bold text-primary">

                    Rp ${format(trx.total)}

                </td>

                <td>

                    <button class="btn btn-danger btn-sm"
                            onclick="openReturn(${index})">

                        RETURN

                    </button>

                </td>

            </tr>

        `;

    });

    document.getElementById('return-transaction-table')
    .innerHTML = html;

}
function detailTransaction(index){

    let trx = transactions[index];

    let items = '';

    trx.items.forEach(item => {

        items += `

            ${item.name}
            (${item.qty} x ${format(item.price)})
            = Rp ${format(item.subtotal)}

        `;

    });

    Alert.success(

        'Invoice : ' + trx.invoice +
        '\nPayment : ' + trx.payment.toUpperCase() +
        '\nTotal : Rp ' + format(trx.total) +
        '\n\nItems :\n' + items

    );

}
function reprintTransaction(index){

    let trx = transactions[index];

    Alert.success(

        'Reprint Receipt\n' +
        trx.invoice

    );

}

function newTransaction(){

    cart = [];

    renderCart();

    document.getElementById('receipt-page')
    .classList.add('d-none');

    document.querySelector('.container-xl')
    .classList.remove('d-none');

}

// Event listener pembayaran
const PRINT_SERVER = 'http://localhost:3000';
const API_URL = '<?= route('create.transaction')?>';
const AUTH_SESSION = '<?= auth()->user()->name?>'
document.getElementById('btn-complete-payment')
.addEventListener('click', async function() {
    
    const btn = this;
    btn.disabled = true;
    btn.textContent = 'Processing...';
    
    const subtotal = calculateTotal();
    let total = subtotal;
    let pay = total;
    let change = 0;
    let charge = 0;
    
    if (selectedPayment === 'cash') {
        pay = parseInt(document.getElementById('cash-amount').value || 0);
        change = pay - total;
    } else if (selectedPayment === 'qris') {
        pay = parseInt(document.getElementById('cash-amount').value || 0);
        total = total;
    }
    
    // Data Transaction
    const transactionData = {
        total_item: cart.length,
        sub_total: subtotal,
        total: total,
        paid_amount: pay,
        payment_method: selectedPayment,
        change: change,
        notes: '',
        member: selectedMember ? selectedMember.code : '',
        items: cart.map(item => ({
            product_id: item.id,
            quantity: item.qty,
            price: item.price,
        })),
    };

    try {
        const dbResponse = await fetch(API_URL, {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '<?= csrfHeader()?>'
            },
            body: JSON.stringify(transactionData)
        });
        
        const dbResult = await dbResponse.json();
        
        if (!dbResult.status) {
            throw new Error(dbResult.message);
        }
        
        const invoiceNumber = dbResult.data.invoice;
        const transactionDate = dbResult.data.date;

        const receiptData = {
            invoice: invoiceNumber,
            date: transactionDate,
            cashier: AUTH_SESSION,
            payment: selectedPayment,
            subtotal: subtotal,
            discount: 0,
            total: total,
            pay: pay,
            change: change,
            charge: charge,
            items: cart.map(item => ({
                name: item.name,
                qty: item.qty,
                price: item.price,
                subtotal: item.subtotal
            })),
            member: selectedMember ? {
                name: selectedMember.name,
                cashback: selectedMember.cashback
            } : null
        };
        
        cart = [];
        renderCart();
        
        const modal = bootstrap.Modal.getInstance(
            document.getElementById('paymentModal')
        );
        modal.hide();

        transactions.unshift(receiptData);
        renderTransactions();
        
        // 3. PRINT OPSIONAL (TIDAK WAJIB)
        // Cetak struk tanpa blocking, jika gagal tidak masalah
        printReceipt(receiptData);
        
        // Tampilkan pesan sukses
        Alert.success('Pembayaran berhasil! Transaksi tersimpan.','success',3000);
        
    } catch(error) {
        console.error('Transaction error:', error);
        Alert.error('Gagal menyimpan transaksi: ' + error.message);
    } finally {
        btn.disabled = false;
        btn.textContent = 'COMPLETE PAYMENT';
    }
});

// Fungsi terpisah untuk print (opsional)
async function printReceipt(receiptData) {
    try {
        // Coba ping printer service dulu (timeout 3 detik)
        const pingController = new AbortController();
        const pingTimeout = setTimeout(() => pingController.abort(), 3000);
        
        const pingResponse = await fetch(`${PRINT_SERVER}/health`, {
            method: 'GET',
            signal: pingController.signal
        }).catch(() => null);
        
        clearTimeout(pingTimeout);
        
        // Jika printer service tidak available, skip print
        if (!pingResponse || !pingResponse.ok) {
            console.warn('Printer service tidak tersedia, skip print');
            return;
        }
        
        // Kirim print request dengan timeout
        const printController = new AbortController();
        const printTimeout = setTimeout(() => printController.abort(), 5000);
        
        const response = await fetch(`${PRINT_SERVER}/print`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ receipt: receiptData }),
            signal: printController.signal
        });
        
        clearTimeout(printTimeout);
        
        const result = await response.json();
        
        if (result.success) {
            console.log('Struk berhasil dicetak');
            // Optional: tampilkan notifikasi sukses print
            showNotification('Struk dicetak', 'success');
        } else {
            console.warn('Print gagal:', result.message);
            showNotification('Struk gagal dicetak, tapi transaksi tersimpan', 'warning');
        }
        
    } catch(error) {
        if (error.name === 'AbortError') {
            console.warn('Print timeout - printer service lambat');
        } else {
            console.warn('Print error (non-blocking):', error.message);
        }
        // Tampilkan notifikasi ringan
        showNotification('Struk tidak tercetak, silakan cetak manual nanti', 'info');
    }
}

// Fungsi print dengan retry
async function printReceiptWithRetry(receiptData, maxRetries = 2) {
    for (let i = 0; i <= maxRetries; i++) {
        try {
            // Cek kesehatan printer
            const healthCheck = await fetch(`${PRINT_SERVER}/health`, {
                signal: AbortSignal.timeout(3000)
            }).catch(() => null);
            
            if (!healthCheck || !healthCheck.ok) {
                if (i === maxRetries) throw new Error('Printer service unavailable');
                await new Promise(r => setTimeout(r, 1000));
                continue;
            }
            
            // Kirim print
            const response = await fetch(`${PRINT_SERVER}/print`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ receipt: receiptData }),
                signal: AbortSignal.timeout(5000)
            });
            
            if (response.ok) {
                console.log('Print success');
                showNotification('Struk berhasil dicetak', 'success');
                return;
            }
            
            throw new Error('Print failed');
            
        } catch (error) {
            console.warn(`Print attempt ${i + 1} failed:`, error.message);
            if (i === maxRetries) {
                showNotification('Struk tidak tercetak, simpan struk untuk dicetak ulang', 'warning');
            }
        }
    }
}

// Fungsi notifikasi sederhana (opsional)
function showNotification(message, type = 'info') {
    // Bisa pakai toast atau alert, tapi lebih baik toast
    const toast = document.createElement('div');
    toast.className = `toast-notification toast-${type}`;
    toast.textContent = message;
    toast.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: ${type === 'success' ? '#4CAF50' : type === 'warning' ? '#ff9800' : '#2196F3'};
        color: white;
        padding: 12px 20px;
        border-radius: 4px;
        z-index: 9999;
        animation: slideIn 0.3s ease;
    `;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// function generateReceiptUI(data) {
//     // Hide POS
//     document.querySelector('.container-xl').classList.add('d-none');
    
//     // Hide modal
//     bootstrap.Modal.getInstance(document.getElementById('paymentModal')).hide();
    
//     // Show receipt page
//     document.getElementById('receipt-page').classList.remove('d-none');
    
//     // Isi data receipt
//     document.getElementById('receipt-invoice').textContent = data.invoice;
//     document.getElementById('receipt-date').textContent = data.date;
//     document.getElementById('receipt-payment').textContent = data.payment.toUpperCase();
//     document.getElementById('receipt-subtotal').textContent = formatRupiah(data.subtotal);
//     document.getElementById('receipt-discount').textContent = formatRupiah(data.discount);
//     document.getElementById('receipt-total').textContent = formatRupiah(data.total);
//     document.getElementById('receipt-pay').textContent = formatRupiah(data.pay);
//     document.getElementById('receipt-change').textContent = formatRupiah(data.change);
//     document.getElementById('receipt-charge').textContent = formatRupiah(data.charge);
    
//     // Items
//     let itemsHTML = '';
//     data.items.forEach(item => {
//         itemsHTML += `
//             <div class="receipt-item">
//                 <div class="item-name">${item.name}</div>
//                 <div class="d-flex justify-content-between">
//                     <div>${item.qty} x ${formatRupiah(item.price)}</div>
//                     <div>${formatRupiah(item.subtotal)}</div>
//                 </div>
//             </div>
//         `;
//     });
//     document.getElementById('receipt-items').innerHTML = itemsHTML;
    
//     // Member
//     if (data.member) {
//         document.getElementById('receipt-member').innerHTML = `
//             <div class="receipt-divider"></div>
//             <table class="receipt-table">
//                 <tr>
//                     <td>Member</td>
//                     <td class="text-end">${data.member.name}</td>
//                 </tr>
//                 <tr>
//                     <td>Cashback</td>
//                     <td class="text-end">${formatRupiah(data.member.cashback)}</td>
//                 </tr>
//             </table>
//         `;
//     } else {
//         document.getElementById('receipt-member').innerHTML = '';
//     }
// }

document.addEventListener('keydown', function(e){

    if(e.key === 'F9'){

        document.getElementById('btn-payment')
        .click();

    }

});
document.addEventListener('change',function(e){

    if(e.target.id === 'filter-payment'){

        renderTransactions();

    }

});
document.addEventListener('keyup',function(e){

    if(e.target.id === 'search-transaction'){

        renderTransactions();

    }

});

window.onload = function(){

    document.getElementById('scan-product')
    .focus();

}

document.getElementById('returnListModal')
.addEventListener('show.bs.modal', function(){

    renderReturnTransactions();

});

</script>