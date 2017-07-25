<?php
$soap = new SoapClient("http://webservice.advise.adv.br/integracaoPublicacao.php?wsdl");
var_dump($soap->getPublicacoes("Nome USsuario", "senha", "2015-03-18", "2015-03-19", 1));