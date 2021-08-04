<?php

// require __DIR__ . "/vendor/autoload.php";
// require __DIR__ . "/vendor/larapack/dd/src/helper.php";
require __DIR__ . "/WsseAuthHeader.php";

class AduanaWS
{

    const wsdl = "https://ws.sunat.gob.pe/ws/v2/controladuanero/ReconocimientoFisicoService.htm?wsdl";
    const username = "20422696548S1STEMAS";
    const password = "S1STEMAS";


    static function getService(): SoapClient
    {
        $wsse_header = new WsseAuthHeader(self::username, self::password);
        $client = new SoapClient(self::wsdl, array(
            'trace' => true,
        ));
        $client->__setSoapHeaders(array($wsse_header));
        return $client;
    }

    static function verificarLevanteDefault()
    {
        try {
            $response = self::getService()->verificarLevante(
                array(
                    "Submitter" => array("RoleCode" => array("_" => 33)),
                    "DeclarationOfficeID" => array("_" => 235),
                    "IssueDateTime" => array("DateTimeString" => array("_" => 2021, "formatCode" => "")),
                    "GovernmentProcedure" => array("CurrentCode" => array("_" => 28)),
                    "ID" => 429634,
                )
            );
            return $response;
        } catch (\Throwable $th) {
            return $th;
        }
    }
}
