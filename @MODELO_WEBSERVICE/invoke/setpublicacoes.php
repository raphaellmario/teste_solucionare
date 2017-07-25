<?php
set_time_limit (0);
ini_set('default_socket_timeout', 60000);

include '_helpers.php';
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    # Dados da consulta
    $strUsuario    = $_POST['strUsuario'];
    $strSenha      = $_POST['strSenha'];
    $codPublicacao =  $_POST['codPublicacao'];

    $soap = new SoapClient(null, array('location' => "http://acessows.sytes.net:9090/recorte/webservice/personalizado_dev/{$cliente}/webservice.php",
                                       'uri'      => "http://acessows.sytes.net:9090/recorte/webservice/personalizado_dev/{$cliente}/webservice.php",
                                       'trace'    => True
                                       ));

    # pegamos o resultado da consulta
    echo "<h3>setpublicacoes('$strUsuario', '$strSenha', '$codPublicacao')</h3>";
	try {
		$res = $soap->setpublicacoes($strUsuario, $strSenha, $codPublicacao);
	}
	catch  (Exception $e)
	{
		echo 'Erro no soap: <pre>' . print_r($e->getMessage()) . '</pre>';
	}
    

    # Formatamos a requisição
    $dom = new DOMDocument;
    $dom->preserveWhiteSpace = True;
    $dom->loadXML($soap->__getLastRequest());
    $dom->formatOutput = TRUE;
    echo '<h4>Requisicao</h4><pre>' . htmlspecialchars($dom->saveXml()) . '</pre>';

    # formatamos o resultado
    $dom = new DOMDocument;
    $dom->preserveWhiteSpace = True;
    $dom->loadXML($soap->__getLastResponse());
    $dom->formatOutput = TRUE;
    echo '<h4>Resposta</h4><pre>' . htmlspecialchars($dom->saveXml()) . '</pre>';
}
else
{
    # Exibição do formulario.
    echo '<h3>setpublicacoes (strUsuario, strSenha, codPublicacao)</h3>';
    echo '<p style="color:red">O preenchimento de todos os campos &eacute; obrigat&oacute;rio</p>';
    echo open_form();
    echo input_field('strUsuario', 'Nome da empresa em caixa alta.');
    echo input_field('strSenha', 'Token para validacao do servico.');
    echo input_field('codPublicacao', 'Codigo da publicacao');
    echo button_submit('Enviar', 1);
    echo close_form();
}
