<?php

namespace App\Controllers;

use App\Services\QRISService;
use Bpjs\Framework\Helpers\BaseController;
use Bpjs\Framework\Core\Request;
use Bpjs\Framework\Helpers\Validator;
use Bpjs\Framework\Helpers\View;
use Bpjs\Framework\Helpers\CSRFToken;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QrisController extends BaseController
{
    // Controller logic here
    protected $QRService;
    public function __construct()
    {
        $this->QRService = new QRISService();
    }

    public function generate(Request $request)
    {
        $params = [
            'merchantvendor' => 'ID.CO.BNI.WWW',
            'merchantid' => '936000091503957030',
            'merchantcriteria' => '614369697',
            'merchanttype' => 'UMI',
            'merchantcategory' => '5499',
            'merchantcurrency' => '360',
            'countryid' => 'ID',
            'merchantname' => 'KANTIN AHMAD ROFIK QR BNI',
            'merchantcity' => 'TANGERANG',
            'merchantpostalcode' => '15710',
            'amount' => $request->amount,
            'invoice_id' => $request->inovice_id ?? null
        ];
        $result = $this->QRService->generateQRIS($params);
        return $this->json([
            'success' => $result['success'],
            'qris_image' => $result['qris_image'],
            'qris_data' => $result['qris_data'],
            'amount' => $result['amount'],
            'merchant_name' => $result['merchant_name']
        ],200);
    }
}
