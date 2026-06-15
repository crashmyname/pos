<?php
// File: app/Views/labels/print-labels.php
// View ini tidak dipanggil langsung, output HTML langsung dari controller
// Ini sebagai backup/template jika dibutuhkan

// Data sudah di-generate oleh LabelBarcodeService
// Jika view ini dipanggil dengan data, render langsung
if (isset($html)) {
    echo $html;
}
?>