<?php

namespace App\Services;
use Bpjs\Framework\Helpers\Validator;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class QRISService
{
    // Service logic here
    public function generateQRIS(array $data)
    {
        
        $merchantvendor = str_pad(strlen($data['merchantvendor']),2,'0',STR_PAD_LEFT);
        $merchantid = str_pad(strlen($data['merchantid']),2,'0',STR_PAD_LEFT);
        $merchantcriteria = str_pad(strlen($data['merchantcriteria']),2,'0',STR_PAD_LEFT);
        $merchanttype = str_pad(strlen($data['merchanttype']),2,'0',STR_PAD_LEFT);
        $merchantcategory = str_pad(strlen($data['merchantcategory']),2,"0",STR_PAD_LEFT);
        $merchantcurrency = str_pad(strlen($data['merchantcurrency']),2,'0',STR_PAD_LEFT);
        $countryid = str_pad(strlen($data['countryid']),2,'0',STR_PAD_LEFT);
        $merchantname = str_pad(strlen($data['merchantname']),2,'0',STR_PAD_LEFT);
        $merchantcity = str_pad(strlen($data['merchantcity']),2,'0',STR_PAD_LEFT);
        $merchantpostalcode = str_pad(strlen($data['merchantpostalcode']),2,'0',STR_PAD_LEFT);
        $total = strlen('00'.$merchantvendor.$data['merchantvendor'])+strlen('01'.$merchantid.$data['merchantid'])+strlen('02'.$merchantcriteria.$data['merchantcriteria'])+strlen('03'.$merchanttype.$data['merchanttype']);

        $payload  = "000201";        
        $payload .= "010212";        

        // Merchant INFO
        $payload .= '26'.$total;
        $payload .= '00'.$merchantvendor.$data['merchantvendor'];
        $payload .= '01'.$merchantid.$data['merchantid']; 
        $payload .= '02'.$merchantcriteria.$data['merchantcriteria'];        
        $payload .= '03'.$merchanttype.$data['merchanttype']; 

        $payload .= '52'.$merchantcategory.$data['merchantcategory'];      
        $payload .= '53'.$merchantcurrency.$data['merchantcurrency'];       

        if ($data['amount'] !== null) {
            $amt = number_format($data['amount'], 2, '.', '');
            $payload .= "54" . str_pad(strlen($amt), 2, "0", STR_PAD_LEFT) . $amt;
        }

        $payload .= '58'.$countryid.$data['countryid'];       
        $payload .= '59'.$merchantname.$data['merchantname'];  
        $payload .= '60'.$merchantcity.$data['merchantcity'];  
        $payload .= '61'.$merchantpostalcode.$data['merchantpostalcode'];         

        $invoiceId = $data['invoice_id'] ?? null;
        if ($invoiceId !== null) {
            $addData = "01" . str_pad(strlen($invoiceId), 2, "0", STR_PAD_LEFT) . $invoiceId;
            $payload .= "62" . str_pad(strlen($addData), 2, "0", STR_PAD_LEFT) . $addData;
        }

        $crc = $this->CRC($payload);
        $payload .= "6304" . $crc;

        $options = new QROptions([
            'version'      => QRCode::VERSION_AUTO, 
            'outputType'   => QRCode::OUTPUT_IMAGE_PNG, 
            'eccLevel'     => QRCode::ECC_M, 
            'scale'        => 10,
            'imageBase64'  => true, 
            'quietzoneSize'=> 4,
            'addQuietzone' => true,
            'imageTransparent' => false, 
        ]);

        $qrcode = new QRCode($options);
        $base64Image = $qrcode->render($payload);
        return [
            'success' => true,
            'statusCode' => 200,
            'qris_image' => $base64Image,
            'qris_data' => $payload,
            'amount' => $data['amount'],
            'merchant_name' => $data['merchantname']
        ];
    }
    public function CRC($payload)
    {
        $crc = 0xFFFF;
        $polynomial = 0x1021;
        $payload .= "6304";

        for ($i = 0; $i < strlen($payload); $i++) {
            $crc ^= (ord($payload[$i]) << 8);
            for ($j = 0; $j < 8; $j++) {
                if (($crc & 0x8000) != 0) {
                    $crc = (($crc << 1) ^ $polynomial) & 0xFFFF;
                } else {
                    $crc = ($crc << 1) & 0xFFFF;
                }
            }
        }
        return strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));
    }

    private function parseTLV($data) {
        $result = [];
        $pos = 0;
        while ($pos < strlen($data)) {
            $id = substr($data, $pos, 2);
            $pos += 2;

            $len = intval(substr($data, $pos, 2));
            $pos += 2;

            $value = substr($data, $pos, $len);
            $pos += $len;

            if (in_array($id, ['26','51','62','64','65'])) {
                $result[$id] = $this->parseTLV($value);
            } else {
                $result[$id] = $value;
            }
        }
        return $result;
    }

    private function mapQrisFields(array $parsed)
    {
        $mapping = [
            '00' => 'payloadFormat',
            '01' => 'pointOfInitiation',
            '26' => 'merchantAccountInfo',
            '51' => 'merchantAccountInfoAdditional',
            '52' => 'merchantCategoryCode',
            '53' => 'transactionCurrency',
            '54' => 'transactionAmount',
            '58' => 'countryCode',
            '59' => 'merchantName',
            '60' => 'merchantCity',
            '61' => 'postalCode',
            '62' => 'additionalData',
            '63' => 'crc',
        ];

        $result = [];
        foreach ($parsed as $id => $value) {
            $key = $mapping[$id] ?? $id;
            $result[$key] = $value;
        }

        return $result;
    }

    private function mapSimplified(array $parsed)
    {
        return [
            'payloadFormat'    => $parsed['00'] ?? null,
            'pointOfInitiation'=> $parsed['01'] ?? null,

            'merchantVid'      => $parsed['26']['00'] ?? null,
            'merchantId'       => $parsed['26']['01'] ?? null,
            'merchantCriteria' => $parsed['26']['02'] ?? null,
            'merchantType'     => $parsed['26']['03'] ?? null,

            'merchantCategory' => $parsed['52'] ?? null,
            'currency'         => $parsed['53'] ?? null,
            'amount'           => $parsed['54'] ?? null,
            'country'          => $parsed['58'] ?? null,
            'merchantName'     => $parsed['59'] ?? null,
            'merchantCity'     => $parsed['60'] ?? null,
            'postalCode'       => $parsed['61'] ?? null,
            'crc'              => $parsed['63'] ?? null,
        ];
    }

    private function buildTLV($id, $value)
    {
        $len = str_pad(strlen($value), 2, '0', STR_PAD_LEFT);
        return $id . $len . $value;
    }

    public function rebuildQris(array $parsed)
    {
        $payload  = '';
        $payload .= $this->buildTLV('00', $parsed['00']); // payloadFormat
        $payload .= $this->buildTLV('01', $parsed['01']); // pointOfInitiation

        // Merchant Account Info
        if (isset($parsed['26'])) {
            $ma = '';
            foreach ($parsed['26'] as $subId => $subVal) {
                $ma .= $this->buildTLV($subId, $subVal);
            }
            $payload .= $this->buildTLV('26', $ma);
        }

        // Merchant Category
        $payload .= $this->buildTLV('52', $parsed['52']);
        // Currency
        $payload .= $this->buildTLV('53', $parsed['53']);
        // Amount
        if (!empty($parsed['54'])) {
            $payload .= $this->buildTLV('54', $parsed['54']);
        }

        // Country
        $payload .= $this->buildTLV('58', $parsed['58']);
        // Merchant Name
        $payload .= $this->buildTLV('59', $parsed['59']);
        // City
        $payload .= $this->buildTLV('60', $parsed['60']);
        // Postal Code
        if (!empty($parsed['61'])) {
            $payload .= $this->buildTLV('61', $parsed['61']);
        }

        $crc = $this->CRC($payload);
        $payload .= '6304' . $crc;

        return $payload;
    }
}
