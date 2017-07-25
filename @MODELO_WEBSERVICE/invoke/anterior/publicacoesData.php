<?php
ini_set("memory_limit", -1);
ini_set('max_execution_time', -1);
set_time_limit (0);

include '_helpers.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    # Dados da consulta
    $recorte    = $_POST['recorte'];
    $token      = $_POST['token'];
    $codCliente = $_POST['codCliente'];
    $jornal     = $_POST['jornal'];
    $data       = $_POST['data'];

    $soap = new SoapClient(null, array('location' => "http://acessows.sytes.net:9090/recorte/webservice/personalizado_dev/aurum/webservice.php",
                                       'uri'      => "http://acessows.sytes.net:9090/recorte/webservice/personalizado_dev/aurum/webservice.php",
                                       'trace'    => 1
                                       ));

    # pegamos o resultado da consulta
    echo "<h3>publicacoesData('$recorte', '$token', $codCliente, '$jornal', '$data')</h3>";
    $res = $soap->publicacoesData($recorte, $token, $codCliente, $jornal, $data);

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
    echo '<h3>publicacoesData(recorte, token, codCliente, jornal, data)</h3>';
    echo '<p style="color:red">O preenchimento de todos os campos &eacute; obrigat&oacute;rio</p>';
    echo open_form();
    echo input_field('recorte', 'Nome da empresa em caixa alta.');
    echo input_field('token', 'Token para validacao do servico.');
    echo input_field('codCliente', 'Codigo do cliente de pesquisa.');
    echo input_field('jornal', 'Estado das publicacoes em caixa alta.');
    echo input_field('data', 'Data de busca (ex: 2014-12-15)');
    echo button_submit('Enviar', 1);
    echo close_form();
}
