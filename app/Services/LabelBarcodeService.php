<?php
// File: app/Services/LabelBarcodeService.php

namespace App\Services;

use Picqer\Barcode\BarcodeGeneratorPNG;

class LabelBarcodeService
{
    private $barcodeGenerator;
    private $config;

    public function __construct()
    {
        $this->barcodeGenerator = new BarcodeGeneratorPNG();
        $this->config = [
            'columns' => 3,
            'rows' => 7,
            'labels_per_page' => 21,
            'label_width_mm' => 60,
            'label_height_mm' => 40,
            'margin_top_mm' => 3,
            'margin_left_mm' => 4,
            'margin_right_mm' => 4,
            'gap_horizontal_mm' => 1.5,
            'gap_vertical_mm' => 1,
            'barcode_height' => 30,
            'barcode_width' => 2,
        ];
    }

    public function generateLabels($products, $logoPath = null)
    {
        $barcodeImages = $this->generateBarcodeImages($products);
        $pages = array_chunk($products, $this->config['labels_per_page']);

        ob_start();
        echo $this->getPrintHeader();
        
        foreach ($pages as $pageIndex => $pageProducts) {
            if ($pageIndex > 0) {
                echo '<div style="page-break-before: always;"></div>';
            }
            
            echo '<div class="label-page">';
            
            for ($i = 0; $i < $this->config['labels_per_page']; $i++) {
                if (isset($pageProducts[$i])) {
                    $productId = $pageProducts[$i]['id'] ?? $i;
                    echo $this->generateLabelHTML(
                        $pageProducts[$i],
                        $barcodeImages[$productId] ?? '',
                        $logoPath
                    );
                } else {
                    echo '<div class="label-empty"></div>';
                }
            }
            
            echo '</div>';
        }
        
        echo '</body></html>';
        return ob_get_clean();
    }

    private function generateBarcodeImages($products)
    {
        $images = [];
        
        foreach ($products as $index => $product) {
            $barcodeData = '';
            $productId = $index;
            
            if (is_array($product)) {
                $barcodeData = $product['barcode'] ?? $product['code'] ?? '';
                $productId = $product['id'] ?? $index;
            } elseif (is_object($product)) {
                $barcodeData = $product->barcode ?? $product->code ?? '';
                $productId = $product->id ?? $index;
            }
            
            if (empty($barcodeData)) {
                $barcodeData = '0000000000';
            }
            
            $barcode = $this->barcodeGenerator->getBarcode(
                $barcodeData,
                $this->barcodeGenerator::TYPE_CODE_128,
                (int) $this->config['barcode_width'],
                (int) $this->config['barcode_height']
            );
            
            $images[$productId] = 'data:image/png;base64,' . base64_encode($barcode);
        }
        
        return $images;
    }

    private function getPrintHeader()
    {
        $w = $this->config['label_width_mm'];
        $h = $this->config['label_height_mm'];
        $cols = $this->config['columns'];
        $rows = $this->config['rows'];
        $mt = $this->config['margin_top_mm'];
        $ml = $this->config['margin_left_mm'];
        $mr = $this->config['margin_right_mm'];
        $gh = $this->config['gap_horizontal_mm'];
        $gv = $this->config['gap_vertical_mm'];
        $pageWidth = ($w * $cols) + ($gh * ($cols - 1)) + $ml + $mr;

        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Print Label Barcode</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 0;
            background: white;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        
        .label-page {
            width: {$pageWidth}mm;
            margin: 0 auto;
            padding: {$mt}mm {$mr}mm {$mt}mm {$ml}mm;
            display: grid;
            grid-template-columns: repeat({$cols}, {$w}mm);
            grid-template-rows: repeat({$rows}, {$h}mm);
            gap: {$gv}mm {$gh}mm;
            page-break-after: always;
            background: white;
        }
        
        /* ==========================================
           LABEL STYLE
           ========================================== */
        .label {
            width: {$w}mm;
            height: {$h}mm;
            border: 1px solid #ddd;
            padding: 0.8mm 1mm;
            background: white;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        
        /* ---------- BARIS 1: Logo + STANLEY MART ---------- */
        .label-brand {
            display: flex;
            align-items: center;
            gap: 0.8mm;
            padding-bottom: 0.5mm;
            border-bottom: 0.5px solid #eee;
            margin-bottom: 0.5mm;
        }
        
        .label-logo {
            width: 4mm;
            height: 4mm;
            object-fit: contain;
            flex-shrink: 0;
        }
        
        .label-brand-name {
            font-size: 5.5pt;
            font-weight: bold;
            color: #333;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }
        
        /* ---------- BARIS 2: Nama Produk (besar) + Company (kanan) ---------- */
        .label-product-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.8mm;
            margin-bottom: 0.5mm;
        }
        
        .label-name {
            font-size: 10pt;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.2;
            flex: 1;
            min-width: 0;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .label-company {
            font-size: 5.5pt;
            color: white;
            text-align: right;
            white-space: nowrap;
            flex-shrink: 0;
            max-width: 18mm;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        /* ---------- BODY: Barcode (kiri) + Harga (kanan) ---------- */
        .label-body {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 0.8mm;
        }
        
        /* KOLOM KIRI: Barcode + Digit */
        .label-barcode-col {
            width: 28mm;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.2mm;
        }
        
        .label-barcode-img {
            width: 100%;
            max-width: 26mm;
            height: 11mm;
            object-fit: contain;
        }
        
        .label-barcode-digit {
            font-size: 4pt;
            font-weight: bold;
            letter-spacing: 0.3px;
            color: #333;
            text-align: center;
            word-break: break-all;
        }
        
        /* KOLOM KANAN: Harga BESAR */
        .label-price-col {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
        }
        
        .label-price {
            font-size: 14pt;
            font-weight: 900;
            color: #d00;
            line-height: 1;
            white-space: nowrap;
        }
        
        /* ---------- FOOTER: Tanggal + Kategori ---------- */
        .label-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 0.3mm;
            margin-top: 0.3mm;
            border-top: 0.5px solid #eee;
        }
        
        .label-date {
            font-size: 5.5pt;
            color: #000000;
        }
        
        .label-category {
            font-size: 3.5pt;
            color: #999;
            background: #f5f5f5;
            padding: 0.2mm 1mm;
            border-radius: 1mm;
        }
        
        .label-empty {
            width: {$w}mm;
            height: {$h}mm;
            border: 1px dashed #eee;
            background: white;
        }
        
        @media print {
            .label {
                border: 0.3px solid #ccc;
            }
        }
    </style>
</head>
<body>
HTML;
    }

    private function generateLabelHTML($product, $barcodeBase64, $logoPath = null)
    {
        if (is_object($product)) {
            $product = (array) $product;
        }
        
        // Logo
        $logoHTML = '';
        if ($logoPath && file_exists($logoPath)) {
            $logoExt = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoData = base64_encode(file_get_contents($logoPath));
            $logoHTML = "<img src='data:image/{$logoExt};base64,{$logoData}' class='label-logo' alt='Logo'>";
        }

        // Data
        $name = strtoupper(substr($product['name'] ?? 'No Name', 0, 22));
        $company = strtoupper(substr($product['company'] ?? '', 0, 18));
        $barcode = $product['qrcode'] ?? $product['code'] ?? '';
        $price = $product['sell_price'] ?? $product['price'] ?? 0;
        $date = date('d/m/Y');
        $category = strtoupper(substr($product['category'] ?? '', 0, 12));
        $uom = $product['uom'] ?? '';

        // Brand toko (bisa di-set dari config atau hardcode)
        $brandName = 'STANLEY MART';

        // Format harga
        $priceFormatted = 'Rp ' . number_format($price, 0, ',', '.');

        // Escape
        $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $company = htmlspecialchars($company, ENT_QUOTES, 'UTF-8');
        $barcode = htmlspecialchars($barcode, ENT_QUOTES, 'UTF-8');
        $priceFormatted = htmlspecialchars($priceFormatted, ENT_QUOTES, 'UTF-8');
        $category = htmlspecialchars($category, ENT_QUOTES, 'UTF-8');
        $brandName = htmlspecialchars($brandName, ENT_QUOTES, 'UTF-8');
        $uom = htmlspecialchars($uom, ENT_QUOTES, 'UTF-8');

        $categoryBadge = $category ? "<span class='label-category'>{$category}</span>" : '';
        $companyHTML = $company ? "<div class='label-company'>{$company}</div>" : '';

        return <<<HTML
        <div class="label">
            <!-- BARIS 1: Logo + STANLEY MART -->
            <div class="label-brand">
                {$logoHTML}
                <span class="label-brand-name">{$brandName}</span>
            </div>
            
            <!-- BARIS 2: Nama Produk (besar) + Company (kanan) -->
            <div class="label-product-row">
                <div class="label-name">{$name}</div>
                {$companyHTML}
            </div>
            
            <!-- BODY: Barcode (kiri) + Harga (kanan) -->
            <div class="label-body">
                <!-- KIRI: Barcode + Digit -->
                <div class="label-barcode-col">
                    <img src="{$barcodeBase64}" class="label-barcode-img" alt="Barcode">
                    <div class="label-barcode-digit">{$barcode}</div>
                </div>
                
                <!-- KANAN: Harga BESAR -->
                <div class="label-price-col">
                    <div class="label-price">{$priceFormatted}</div> 
                </div>
                <br>
                <span class="label-date">/ {$uom}</span>
            </div>
            
            <!-- FOOTER: Tanggal + Kategori -->
            <div class="label-footer">
                <span class="label-date">Cetak: {$date}</span>
                {$categoryBadge}
            </div>
        </div>
HTML;
    }
}