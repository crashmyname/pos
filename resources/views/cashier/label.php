<div id="receipt">

    <!-- STORE -->
    <div class="receipt-center">

        <div class="store-name">
            KOPERASI KARYAWAN
        </div>

        <div class="store-address">
            Jl. Industri No. 88
        </div>

        <div class="store-phone">
            Telp : 0812-0000-0000
        </div>

    </div>

    <div class="receipt-divider"></div>

    <!-- INFO -->
    <table class="receipt-table">

        <tr>
            <td>Invoice</td>
            <td class="text-end">
                INV-20260518-001
            </td>
        </tr>

        <tr>
            <td>Date</td>
            <td class="text-end">
                18/05/2026 10:30
            </td>
        </tr>

        <tr>
            <td>Cashier</td>
            <td class="text-end">
                Fervian
            </td>
        </tr>

        <tr>
            <td>Payment</td>
            <td class="text-end">
                QRIS
            </td>
        </tr>

    </table>

    <div class="receipt-divider"></div>

    <!-- ITEMS -->
    <div class="receipt-items">

        <div class="receipt-item">

            <div class="item-name">
                Coca Cola
            </div>

            <div class="d-flex justify-content-between">

                <div>
                    2 x 5.000
                </div>

                <div>
                    10.000
                </div>

            </div>

        </div>

        <div class="receipt-item">

            <div class="item-name">
                Indomie Goreng
            </div>

            <div class="d-flex justify-content-between">

                <div>
                    3 x 3.500
                </div>

                <div>
                    10.500
                </div>

            </div>

        </div>

    </div>

    <div class="receipt-divider"></div>

    <!-- TOTAL -->
    <table class="receipt-table">

        <tr>
            <td>Subtotal</td>
            <td class="text-end">
                20.500
            </td>
        </tr>

        <tr>
            <td>Discount</td>
            <td class="text-end">
                500
            </td>
        </tr>

        <tr class="receipt-total">
            <td>TOTAL</td>
            <td class="text-end">
                20.000
            </td>
        </tr>

    </table>

    <div class="receipt-divider"></div>

    <!-- FOOTER -->
    <div class="receipt-center">

        <div class="thankyou">
            TERIMA KASIH
        </div>

        <div class="receipt-note">
            BELANJA ANDA GRATIS
            <br>
            JIKA TIDAK MENERIMA STRUK
        </div>

        <div class="receipt-date">
            www.koperasi.local
        </div>

    </div>

</div>

<style>
    #receipt{

    width:40mm;
    min-height:110mm;

    background:#fff;

    padding:8px;

    font-family:monospace;
    font-size:10px;
    color:#000;

}

.receipt-center{
    text-align:center;
}

.store-name{
    font-size:13px;
    font-weight:bold;
}

.store-address,
.store-phone{
    font-size:9px;
}

.receipt-divider{
    border-top:1px dashed #000;
    margin:6px 0;
}

.receipt-table{
    width:100%;
    font-size:10px;
}

.receipt-table td{
    padding:1px 0;
}

.receipt-items{
    font-size:10px;
}

.receipt-item{
    margin-bottom:5px;
}

.item-name{
    font-weight:bold;
}

.receipt-total{
    font-size:12px;
    font-weight:bold;
}

.thankyou{
    font-weight:bold;
    margin-bottom:5px;
}

.receipt-note{
    font-size:9px;
    font-weight:bold;
    margin-top:5px;
}

.receipt-date{
    margin-top:6px;
    font-size:8px;
}
</style>