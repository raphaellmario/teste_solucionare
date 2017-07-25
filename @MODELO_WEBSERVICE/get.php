<?php

$funcao  = $_GET['func'];
$recorte = $_GET['recorte'];
$token   = $_GET['token'];
$codCliente = $_GET['codCliente'];
$jornal  = $_GET['jornal'];
$data    = $_GET['data'];

$soap = new SoapClient(null, array('location' => "http://acessows.sytes.net:9090/recorte/webservice/personalizado_dev/{$cliente}/webservice.php",
                                   'uri'      => "http://acessows.sytes.net:9090/recorte/webservice/personalizado_dev/{$cliente}/webservice.php"));

echo '<pre>';
print_R($soap->{$funcao}($recorte, $token, $codCliente, $jornal, $data));
echo ereg_replace("[^a-z]", '', base64_encode(md5($recorte . 'md5#validation#check#token'))) . '<br>';
echo $token;
echo '</pre>';
