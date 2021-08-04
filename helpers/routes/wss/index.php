<?php
try {

   require __DIR__ . "/vendor/autoload.php";
   require __DIR__ . "/vendor/larapack/dd/src/helper.php";
   require __DIR__ . "/WsseAuthHeader.php";

   $wsdl = "https://ws.sunat.gob.pe/ws/v2/controladuanero/ReconocimientoFisicoService.htm?wsdl";
   $username = "20422696548S1STEMAS";
   $password = "S1STEMAS";

   $wsse_header = new WsseAuthHeader($username, $password);

   $client = new SoapClient($wsdl, array(
      'trace' => true,
   ));

   $client->__setSoapHeaders(array($wsse_header));
   // revisa el ejemplo planteaddo en body.xml
   $response = $client->verificarLevante(
      array(
         "Submitter" => array("RoleCode" => array("_" => 33)),
         "DeclarationOfficeID" => array("_" => 235),
         "IssueDateTime" => array("DateTimeString" => array("_" => 2021, "formatCode" => "")),
         "GovernmentProcedure" => array("CurrentCode" => array("_" => 28)),
         "ID" => 429634,
      )
   );

   dd($response);
   // dd($client->__getFunctions());
   // dd($client->__getTypes());
} catch (\Throwable $th) {
   echo $th;
}
