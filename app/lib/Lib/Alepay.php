<?php

define('DS', str_replace('\\', '/', DIRECTORY_SEPARATOR));
define('ROOT_PATH', dirname(__FILE__));
include(ROOT_PATH . DS . 'Utils/AlepayUtils.php');
/*
 * Alepay class
 * Implement with Alepay service
 */

class Alepay {

    protected $alepayUtils;
    protected $publicKey = "";
    protected $checksumKey = "";
    protected $apiKey = "";
    protected $callbackUrl = "";
    protected $env = "test";
    protected $baseURL = array(
        'dev' => 'localhost:8080',
        'test' => 'https://alepay-v3-sandbox.nganluong.vn/api/v3/checkout/',
        'live' => 'https://alepay-v3.nganluong.vn/api/v3/checkout/'
    );
    protected $URI = array(
        'requestPayment' => 'request-payment',
        'getTransactionInfo' => 'get-transaction-info',
        'getlistBank' => 'get-installment-info',
    );

    public function __construct($opts) {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Credentials: true");
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Max-Age: 1000');
        header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');

        /*
         * Require curl and json extension
         */
        if (isset($opts) && !empty($opts["apiKey"])) {
            $this->apiKey = 'yJVzaXsEfDKYvf6D4hineQMHEBI3EF';
        } else {
            throw new Exception("API key is required !");
        }
        if (isset($opts) && !empty($opts["encryptKey"])) {
            $this->publicKey = 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDFXmSiZ9q1X0CBN0ye7SNSv+Sz6gtH3lsd3SPaTwYcubQNgah/mwezNl/ABU5cPvUH6g3EQ9a13X++eRSfap4sFF2CehBQSZroZPHIGZEAYi401iOFLInqxV71zqRv1xrNQ4GnEldni+/rkgQaWYICsfy8pxkr+w7l6fZVrJ+aVwIDAQAB';
        } else {
            throw new Exception("Encrypt key is required !");
        }
        if (isset($opts) && !empty($opts["checksumKey"])) {
            $this->checksumKey = 'HaG8ImT64DO8Iivg8qWokIwfqC542x';
        } else {
            throw new Exception("Checksum key is required !");
        }
        if (isset($opts) && !empty($opts["callbackUrl"])) {
            $this->callbackUrl = $opts["callbackUrl"];
        }
         if (isset($opts) && !empty($opts["env"])) {
            $this->env = 'test';
        }
        $this->alepayUtils = new AlepayUtils();
    }

    /*
     * sendOrder - Send order information to Alepay service
     * @param array|null $data
     */

    public function sendOrderToAlepay($data) {
        // get demo data
        // $data = $this->createCheckoutData();
        $data['returnUrl'] = $this->callbackUrl;
        // $data['cancelUrl'] = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . '/demo-alepay';
        $url = $this->baseURL[$this->env] . $this->URI['requestPayment'];
        $result = $this->sendRequestToAlepay($data, $url);
        if (isset($result) && $result->errorCode == '000') {
            $dataDecrypted = $this->alepayUtils->decryptData($result->data, $this->publicKey);
            return json_decode($dataDecrypted);
        } else {
            return $result;
        }
    }

    public function sendOrderToAlepayDomestic($data) {
        // get demo data
        $data = $this->createCheckoutDomesticData();
        $data['returnUrl'] = $this->callbackUrl;
        //  $data['cancelUrl'] = 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . '/demo-alepay';
        $url = $this->baseURL[$this->env] . $this->URI['requestPayment'];
        $result = $this->sendRequestToAlepay($data, $url);
        if ($result->errorCode == '000') {
            $dataDecrypted = $this->alepayUtils->decryptData($result->data, $this->publicKey);
            return json_decode($dataDecrypted);
        } else {
            echo json_encode($result);
        }
    }

    /*
     * get transaction info from Alepay
     * @param array|null $data
     */

    public function getTransactionInfo($transactionCode) {

        // demo data
        $data = array('transactionCode' => $transactionCode);
        $url = $this->baseURL[$this->env] . $this->URI['getTransactionInfo'];
        $result = $this->sendRequestToAlepay($data, $url);
        return $result;
        // if ($result->errorCode == '000') {
        //     $dataDecrypted = $this->alepayUtils->decryptData($result->data, $this->publicKey);
        //     return  $dataDecrypted;
        // } else {
        //     return json_encode($result);
        // }
    }

    /*
     * sendCardLinkRequest - Send user's profile info to Alepay service
     * return: cardlink url
     * @param array|null $data
     */

    public function sendCardLinkRequest($data) {
        // get demo data
        $data = $this->createRequestCardLinkData();
        $url = $this->baseURL[$this->env] . $this->URI['requestCardLink'];
        $result = $this->sendRequestToAlepay($data, $url);
        if ($result->errorCode == '000') {
            $dataDecrypted = $this->alepayUtils->decryptData($result->data, $this->publicKey);
            return json_decode($dataDecrypted);
        } else {
            return $result;
        }
    }

    public function sendCardLinkDomesticRequest() {
        // get demo data
        $data = $this->createRequestCardLinkDataDomestic();
        $url = $this->baseURL[$this->env] . $this->URI['requestCardLinkDomestic'];
        $result = $this->sendRequestToAlepay($data, $url);
        if ($result->errorCode == '000') {
            $dataDecrypted = $this->alepayUtils->decryptData($result->data, $this->publicKey);
            return json_decode($dataDecrypted);
        } else {
            return $result;
        }
    }

    public function sendTokenizationPayment($tokenization) {

        $data = $this->createTokenizationPaymentData($tokenization);
        $url = $this->baseURL[$this->env] . $this->URI['tokenizationPayment'];
        $result = $this->sendRequestToAlepay($data, $url);
        if ($result->errorCode == '000') {
            $dataDecrypted = $this->alepayUtils->decryptData($result->data, $this->publicKey);
            return json_decode($dataDecrypted);
        } else {
            return $result;
        }
    }

    public function sendTokenizationPaymentDomestic($tokenization) {
        $data = $this->createTokenizationPaymentDomesticData($tokenization);
        $url = $this->baseURL[$this->env] . $this->URI['tokenizationPaymentDomestic'];
        $result = $this->sendRequestToAlepay($data, $url);
        if ($result->errorCode == '000') {
            $dataDecrypted = $this->alepayUtils->decryptData($result->data, $this->publicKey);
            return json_decode($dataDecrypted);
        } else {
            return $result;
        }
    }

    public function cancelCardLink($alepayToken) {
        $params = array('alepayToken' => $alepayToken);
        $url = $this->baseURL[$this->env] . $this->URI['cancelCardLink'];
        $result = $this->sendRequestToAlepay($params, $url);
        echo json_encode($result);
        if ($result->errorCode == '000') {
            $dataDecrypted = $this->alepayUtils->decryptData($result->data, $this->publicKey);
            echo $dataDecrypted;
        }
    }

    private function sendRequestToAlepay($data, $url) {
        $dataEncrypt = $this->alepayUtils->encryptData(json_encode($data), $this->publicKey);
        $checksum = md5($dataEncrypt . $this->checksumKey);
        $items = array(
            'token' => $this->apiKey,
            'data' => $dataEncrypt,
            'checksum' => $checksum
        );
        $data_string = json_encode($items);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
        );
        $result = curl_exec($ch);
        // echo $result;
        return json_decode($result);
    }

    public function return_json($error, $message = "", $data = array()) {
        header('Content-Type: application/json');
        echo json_encode(array(
            "error" => $error,
            "message" => $message,
            "data" => $data
        ));
    }

    public function decryptCallbackData($data) {
        return $this->alepayUtils->decryptCallbackData($data, $this->publicKey);
    }

    public function sendOrderV3($data)
    {
        $data['tokenKey'] = $this->apiKey;
        $data['customMerchantId'] = 'lam123';
         $data['returnUrl'] = $this->callbackUrl;
        $data['cancelUrl'] = $this->callbackUrl;
        $signature = $this->alepayUtils->makeSignature($data, $this->checksumKey);
        $data['signature'] = $signature;
        $data_string = json_encode($data);
		$url =  'https://alepay-v3.nganluong.vn/api/v3/checkout/'. $this->URI['requestPayment'];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        ));

       

        $result = curl_exec($ch);
        return json_decode($result);
    }

    public function Get_Transaction_data($transactionCode)
    {
        $data['tokenKey'] = 'yJVzaXsEfDKYvf6D4hineQMHEBI3EF';
        $data['transactionCode'] = $transactionCode;
        $signature = $this->alepayUtils->makeSignature($data, 'HaG8ImT64DO8Iivg8qWokIwfqC542x');
        $data['signature'] = $signature;
        $data_string = json_encode($data);
        $url =  'https://alepay-v3.nganluong.vn/api/v3/checkout/'. $this->URI['getTransactionInfo'];
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        ));
        $result = curl_exec($ch);
        return json_decode($result);

    }

}

?>