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
            'margin_top_mm' => 8.5,
            'margin_left_mm' => 5,
            'margin_right_mm' => 5,
            'gap_horizontal_mm' => 2,
            'gap_vertical_mm' => 2,
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
        
        /* ============ LABEL ============ */
        .label {
            width: {$w}mm;
            height: {$h}mm;
            border: 1px solid #ddd;
            padding: 1.5mm;
            background: white;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }
        
        /* HEADER: Logo + Nama */
        .label-top {
            display: flex;
            align-items: center;
            gap: 1mm;
            margin-bottom: 0.5mm;
        }
        
        .label-logo {
            width: 5mm;
            height: 5mm;
            object-fit: contain;
            flex-shrink: 0;
        }
        
        .label-name {
            font-size: 7pt;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.2;
            flex: 1;
        }
        
        .label-company {
            font-size: 5pt;
            color: #999;
            position: absolute;
            top: 0.5mm;
            right: 1mm;
        }
        
        /* TENGAH: Barcode */
        .label-barcode-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 0.3mm;
        }
        
        .label-barcode-img {
            width: 100%;
            max-width: 52mm;
            height: auto;
            max-height: 9mm;
        }
        
        .label-barcode-digit {
            font-size: 6pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            color: #333;
        }
        
        /* BAWAH: Harga + Footer */
        .label-bottom {
            margin-top: 0.5mm;
        }
        
        .label-price {
            text-align: center;
            font-size: 11pt;
            font-weight: 900;
            color: #d00;
            line-height: 1.2;
            margin-bottom: 0.5mm;
        }
        
        .label-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 0.3mm;
            border-top: 0.5px solid #eee;
        }
        
        .label-date {
            font-size: 4.5pt;
            color: #999;
        }
        
        .label-category {
            font-size: 4.5pt;
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
                border: 0.5px solid #ccc;
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
        $name = strtoupper(substr($product['name'] ?? 'No Name', 0, 30));
        $barcode = $product['barcode'] ?? $product['code'] ?? '';
        $price = $product['sell_price'] ?? $product['price'] ?? 0;
        $date = date('d/m/Y');
        $category = strtoupper(substr($product['category'] ?? '', 0, 15));
        $company = strtoupper(substr($product['company'] ?? '', 0, 20));

        // Format harga: "RP. 3.000" satu baris
        $priceFormatted = 'RP. ' . number_format($price, 0, ',', '.');

        // Escape
        $name = htmlspecialchars($name, ENT_QUOTES, 'UTF-8');
        $barcode = htmlspecialchars($barcode, ENT_QUOTES, 'UTF-8');
        $category = htmlspecialchars($category, ENT_QUOTES, 'UTF-8');
        $company = htmlspecialchars($company, ENT_QUOTES, 'UTF-8');
        $priceFormatted = htmlspecialchars($priceFormatted, ENT_QUOTES, 'UTF-8');

        $categoryBadge = $category ? "<div class='label-category'>{$category}</div>" : '';
        $companyHTML = $company ? "<div class='label-company'>{$company}</div>" : '';

        return <<<HTML
        <div class="label">
            {$companyHTML}
            
            <!-- ATAS: Logo + Nama Produk -->
            <div class="label-top">
                {$logoHTML}
                <div class="label-name">{$name}</div>
            </div>
            
            <!-- TENGAH: Barcode -->
            <div class="label-barcode-area">
                <img src="{$barcodeBase64}" class="label-barcode-img" alt="Barcode">
                <div class="label-barcode-digit">{$barcode}</div>
            </div>
            
            <!-- BAWAH: Harga Besar + Footer -->
            <div class="label-bottom">
                <div class="label-price">{$priceFormatted}</div>
                <div class="label-footer">
                    <div class="label-date">Cetak: {$date}</div>
                    {$categoryBadge}
                </div>
            </div>
        </div>
HTML;
    }
}