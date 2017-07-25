<?php

$strUsuario ="RECORTETESTE";
$strSenha ="mmmwjhhjjijkwg";
$dataInicio ="2017-05-15";
$dataFim ="2017-05-19";
$intExportada ="0";
$intCodGrupo ="47";
$quantidadeDePublicacoes ="100";

$soap = new SoapClient(null, array('location' => "http://acessows.sytes.net:9090/recorte/webservice/personalizado_dev/{$strUsuario}/webservice.php",
                                   'uri'      => "http://acessows.sytes.net:9090/recorte/webservice/personalizado_dev/{$strUsuario}/webservice.php",
                                   'trace'    => True
                                   ));
$res = $soap->getPublicacoesTodosComQuantidadeLimitada($strUsuario, $strSenha, $dataInicio, $dataFim, $intExportada, $intCodGrupo, $quantidadeDePublicacoes);
$dom = new DOMDocument;
$dom->preserveWhiteSpace = True;
$dom->loadXML($soap->__getLastResponse());
$dom->formatOutput = TRUE;
header("Content-type: text/xml");
echo $dom->saveXml();
