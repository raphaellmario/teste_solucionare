<?php
set_time_limit (0);
//ini_set("mssql.textlimit", "21474836479999999999");
//ini_set("mssql.textsize", "214748364799999999999");
ini_set("memory_limit", -1);
ini_set('max_execution_time', -1);

$server = new SoapServer(null, array('uri' => 'http://acessows.sytes.net:9090/recorte/webservice/personalizado_dev/'.{$cliente}.'/webservice.php'));

require_once 'consulta.php';
require_once 'validacao.php';
require_once 'funcoes.php';

var_dump(getPublicacoes('strtoupper('.$cliente.')', 'jkjkmzlxzjihm', 1, '2015-03-23', '2015-03-23'));
