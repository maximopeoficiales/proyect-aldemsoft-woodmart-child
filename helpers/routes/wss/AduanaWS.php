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
                    "GovernmentProcedure" => array("CurrentCode" => array("_" => 10)),
                    "ID" => 78739,
                )
            );
            return ($response);
        } catch (\Throwable $th) {
            return $th;
        }
    }

    static function verificarLevante(string $dua1, string $dua2, string $dua3, string $dua4, int $roleCode = 33)
    {
        try {
            $response = self::getService()->verificarLevante(
                array(
                    "Submitter" => array("RoleCode" => array("_" => $roleCode)),
                    "DeclarationOfficeID" => array("_" => $dua1),
                    "IssueDateTime" => array("DateTimeString" => array("_" => $dua2, "formatCode" => "")),
                    "GovernmentProcedure" => array("CurrentCode" => array("_" => $dua3)),
                    "ID" => $dua4,
                )
            );
            return self::getObjectByService($response);
            // return ($response);
        } catch (\Throwable $th) {
            return $th;
        }
    }

    static function getObjectByService($response)
    {
        // si es igual a 8 no existe
        if ($response->StatusCode->_ == 8) return null;

        $newService = new  stdClass();
        // variables
        $manifesto =
            $response->AdditionalDocument->IssueDateTime->DateTimeString->_ . sprintf("%05d", $response->AdditionalDocument->ID->_);

        $kilos = number_format($response->TotalGrossMassMeasure->_, 2);
        $fecha_levante = $response->GoodsShipment->Status->ReleaseDateTime->DateTimeString->_;
        $fecha_levante =
            date('Y/m/d H:i:s', strtotime($fecha_levante));

        $job = $response->GoodsShipment->Consignment->TransportContractDocument->ID->_;
        // logica de green_channel
        $green_channel = null;
        $green_channelValue = null;
        $semaforo = null;
        // obtengo la variable RAH
        foreach ($response->AdditionalInformation as $information) {
            if ($information->StatementTypeCode->_ === "RAH") {
                $green_channel = strtoupper($information->StatementCode->_);
            }
        }
        if ($green_channel == "V") {
            $green_channelValue = 1;
            $semaforo = "verde";
        } else if ($green_channel == "F") {
            $green_channelValue = 3;
            $semaforo = "rojo";
        } else if ($green_channel == "D") {
            $green_channelValue = 2;
            $semaforo = "ambar";
        }

        // protocolo
        $protocolo = null;
        if (is_array($response->GoodsShipment->GovernmentAgencyGoodsItem)) {
            $protocolo = $response->GoodsShipment->GovernmentAgencyGoodsItem[0]->Commodity->IntendedUse->_;
        } else {
            $protocolo = $response->GoodsShipment->GovernmentAgencyGoodsItem->Commodity->IntendedUse->_;
        }
        // $newService
        $newService->job = $job;
        $newService->manifiesto = $manifesto;
        $newService->pcs = $response->TotalPackageQuantity->_;
        $newService->kilos = $kilos;
        $newService->id_importador = $response->GoodsShipment->Consignee->Name->_;
        $newService->protocolo = $protocolo;
        $newService->fecha_levante = $fecha_levante;
        $newService->green_channel = $green_channelValue;
        $newService->semaforo = $semaforo;

        return $newService;
    }
}
