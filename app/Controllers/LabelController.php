<?php
// File: app/Controllers/LabelController.php

namespace App\Controllers;

use App\Models\Product;
use App\Services\LabelBarcodeService;
use Bpjs\Framework\Helpers\BaseController;
use Bpjs\Framework\Core\Request;
use Bpjs\Framework\Helpers\View;
use Bpjs\Framework\Helpers\CSRFToken;
use Bpjs\Framework\Helpers\Validator;
use Bpjs\Framework\Helpers\Redirect;

class LabelController extends BaseController
{
    protected $labelService;

    public function __construct()
    {
        // Inisialisasi service manual karena tidak ada dependency injection
        $this->labelService = new LabelBarcodeService();
    }

    /**
     * Halaman form untuk memilih produk yang akan diprint
     */
    public function index(Request $request)
    {
        // Ambil produk active
        $query = Product::query()->where('is_active', '=', '1');
        
        // Filter pencarian
        if ($request->get('search')) {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('products.name', 'like', "%{$search}%")
                  ->orWhere('qrcode', 'like', "%{$search}%");
                //   ->orWhere('category', 'like', "%{$search}%");
            });
        }
        
        // Filter kategori
        // if ($request->get('category')) {
        //     $query->where('category', '=', $request->get('category'));
        // }
        
        // Get products dengan pagination
        $page = $request->get('page', 1);
        $products = $query->orderBy('products.name', 'ASC')->paginate(50);
        
        // Ambil daftar kategori untuk filter
        $categoryModel = new Product();
        $categories = $categoryModel::query()
            ->where('is_active', '=', '1')
            ->select('category_id')
            ->distinct()
            ->get(\PDO::FETCH_COLUMN);
        
        // Set data untuk view
        $data = [
            'title' => 'Print Label Barcode',
            'products' => $products,
            'categories' => $categories,
            'search' => $request->get('search', ''),
            'selectedCategory' => $request->get('category_id', ''),
            'csrf_token' => CSRFToken::generateToken()
        ];
        
        // Render view
        View::render('labels/print-form', $data);
    }

    /**
     * Print label untuk produk yang dipilih
     */
    public function printSelected(Request $request)
    {
        $selectedIds = $request->input('selected_products', []);
        
        if (empty($selectedIds)) {
            $_SESSION['error'] = 'Pilih minimal 1 produk untuk diprint!';
            redirect('/labels');
            return;
        }
        
        // Ambil data produk yang dipilih
        $products = Product::query()
            ->whereIn('products.id', $selectedIds)
            ->where('is_active', '=', '1')
            ->select('products.id', 'products.name', 'qrcode', 'sell_price', 'categories.name as category', 'suppliers.name as company','uom')
            ->leftJoin('suppliers','suppliers.id','=','products.supplier_id')
            ->leftJoin('categories','categories.id','=','products.category_id')
            ->orderBy('products.name', 'ASC')
            ->get(\PDO::FETCH_ASSOC);
        
        if (empty($products)) {
            $_SESSION['error'] = 'Produk tidak ditemukan!';
            redirect('/labels');
            return;
        }
        
        // Path logo - sesuaikan dengan lokasi logo
        $logoPath = public_path('logo-mart.jpg');
        
        // Generate HTML label
        $html = $this->labelService->generateLabels($products, $logoPath);
        
        // Output langsung untuk print
        echo $html;
        exit;
    }

    /**
     * Print semua produk sekaligus
     */
    public function printAll()
    {
        $products = Product::query()
            ->where('is_active', '=', '1')
            ->select('products.id', 'products.name', 'qrcode', 'sell_price', 'categories.name as category', 'suppliers.name as company','uom')
            ->leftJoin('suppliers','suppliers.id','=','products.supplier_id')
            ->leftJoin('categories','categories.id','=','products.category_id')
            ->orderBy('products.name', 'ASC')
            ->get(\PDO::FETCH_ASSOC);
        
        if (empty($products)) {
            $_SESSION['error'] = 'Tidak ada produk untuk diprint!';
            redirect('/labels');
            return;
        }
        
        $logoPath = public_path('logo-mart.jpg');
        $html = $this->labelService->generateLabels($products, $logoPath);
        
        echo $html;
        exit;
    }

    /**
     * Print label by category
     */
    public function printByCategory($category)
    {
        $products = Product::query()
            ->where('is_active', '=', '1')
            // ->where('category', '=', urldecode($category))
            ->select('products.id', 'products.name', 'qrcode', 'sell_price', 'categories.name as category', 'suppliers.name as company','uom')
            ->leftJoin('suppliers','suppliers.id','=','products.supplier_id')
            ->leftJoin('categories','categories.id','=','products.category_id')
            ->orderBy('products.name', 'ASC')
            ->get(\PDO::FETCH_ASSOC);
        
        if (empty($products)) {
            $_SESSION['error'] = 'Tidak ada produk di kategori ini!';
            redirect('/labels');
            return;
        }
        
        $logoPath = public_path('logo-mart.jpg');
        $html = $this->labelService->generateLabels($products, $logoPath);
        
        echo $html;
        exit;
    }

    /**
     * Print custom number of labels per product
     */
    public function printCustomQuantity(Request $request)
    {
        $productList = [];
        $products = $request->input('products', []);
        
        foreach ($products as $item) {
            if (empty($item['id']) || empty($item['quantity'])) continue;
            
            $product = Product::query()
                ->select('products.id', 'products.name', 'qrcode', 'sell_price', 'categories.name as category', 'suppliers.name as company','uom')
                ->leftJoin('suppliers','suppliers.id','=','products.supplier_id')
                ->leftJoin('categories','categories.id','=','products.category_id')
                ->where('id', '=', $item['id'])
                ->first();
            
            if ($product) {
                // Konversi ke array jika perlu
                if (is_object($product)) {
                    $product = (array) $product;
                }
                
                // Duplikasi produk sesuai quantity
                for ($i = 0; $i < (int)$item['quantity']; $i++) {
                    $productList[] = $product;
                }
            }
        }
        
        if (empty($productList)) {
            $_SESSION['error'] = 'Tidak ada produk valid!';
            redirect('/labels');
            return;
        }
        
        $logoPath = $logoPath = public_path('logo-mart.jpg');
        $html = $this->labelService->generateLabels($productList, $logoPath);
        
        echo $html;
        exit;
    }

    /**
     * Preview label via AJAX
     */
    public function preview(Request $request)
    {
        $selectedIds = $request->input('selected_products', []);
        
        if (empty($selectedIds)) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Pilih minimal 1 produk!']);
            exit;
        }
        
        $products = Product::query()
            ->whereIn('id', $selectedIds)
            ->where('is_active', '=', '1')
            ->select('products.id', 'products.name', 'qrcode', 'sell_price', 'categories.name as category', 'suppliers.name as company','uom')
            ->leftJoin('suppliers','suppliers.id','=','products.supplier_id')
            ->leftJoin('categories','categories.id','=','products.category_id')
            ->orderBy('products.name', 'ASC')
            ->get(\PDO::FETCH_ASSOC);
        
        if (empty($products)) {
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Produk tidak ditemukan!']);
            exit;
        }
        
        $logoPath = public_path('logo-mart.jpg');
        $html = $this->labelService->generateLabels($products, $logoPath);
        
        header('Content-Type: application/json');
        echo json_encode(['html' => $html]);
        exit;
    }
    
    /**
     * Print single product langsung
     */
    public function printSingle($id)
    {
        $product = Product::query()
            ->select('products.id', 'products.name', 'qrcode', 'sell_price', 'categories.name as category', 'suppliers.name as company','uom')
            ->leftJoin('suppliers','suppliers.id','=','products.supplier_id')
            ->leftJoin('categories','categories.id','=','products.category_id')
            ->where('products.id', '=', $id)
            ->where('is_active', '=', '1')
            ->first();
        
        if (!$product) {
            $_SESSION['error'] = 'Produk tidak ditemukan!';
            redirect('/labels');
            return;
        }
        
        // Konversi ke array jika object
        if (is_object($product)) {
            $product = (array) $product;
        }
        
        $logoPath = public_path('logo-mart.jpg');
        $html = $this->labelService->generateLabels([$product], $logoPath);
        
        echo $html;
        exit;
    }
}