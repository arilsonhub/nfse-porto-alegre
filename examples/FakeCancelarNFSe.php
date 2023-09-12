<?php
error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once '../bootstrap.php';

use NFePHP\Common\Certificate;
use NFePHP\NFSeBHISS\Tools;
use NFePHP\NFSeBHISS\Common\Soap\SoapFake;
use NFePHP\NFSeBHISS\Common\FakePretty;

try {
    $config = [
        'cnpj' => '99999999000191',
        'im' => '1733160024',
        'cmun' => '4314902', //ira determinar as urls e outros dados
        'razao' => 'Empresa Test Ltda',
        'tpamb' => 2 //1-producao, 2-homologacao
    ];

    $configJson = json_encode($config);

    $content = file_get_contents('expired_certificate.pfx');
    $password = 'associacao';
    $cert = Certificate::readPfx($content, $password);

    $soap = new SoapFake();
    $soap->disableCertValidation(true);
    
    $tools = new Tools($configJson, $cert);
    $tools->loadSoapClass($soap);

    $id = 'C201800000000001';
    $numero = '201800000000001';
    
    $response = $tools->cancelarNfse($id, $numero, $tools::CANCEL_ERRO_EMISSAO);

    echo FakePretty::prettyPrint($response, '');
 
} catch (\Exception $e) {
    echo $e->getMessage();
}

