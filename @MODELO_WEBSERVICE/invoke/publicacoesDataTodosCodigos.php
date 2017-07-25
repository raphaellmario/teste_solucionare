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
    $dataInicio       = $_POST['dataInicio'];
    $intCodGrupo = $_POST['intCodGrupo'];
    $dataFim       = $_POST['dataFim'];
    $intExportada  = $_POST['intExportada'];

    $soap = new SoapClient(null, array('location' => "http://acessows.sytes.net:9090/recorte/webservice/personalizado_dev/{$cliente}/webservice.php",
                                       'uri'      => "http://acessows.sytes.net:9090/recorte/webservice/personalizado_dev/{$cliente}/webservice.php",
                                       'trace'    => True
                                       ));

    # pegamos o resultado da consulta
    echo "<h3>getPublicacoesTodos('$strUsuario', '$strSenha', $dataInicio, $dataFim, $intExportada, $intCodGrupo)</h3>";
    $res = $soap->getPublicacoesTodos($strUsuario, $strSenha, $dataInicio, $dataFim, $intExportada, $intCodGrupo);

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
    echo '<h3>getPublicacoesTodos(strUsuario, strSenha, dataInicio, dataFim , intExportada)</h3>';
    echo '<p style="color:red">O preenchimento de todos os campos &eacute; obrigat&oacute;rio</p>';
    echo open_form();
    echo input_field('strUsuario', 'Nome da empresa em caixa alta.');
    echo input_field('strSenha', 'Token para validacao do servico.');
    echo input_field('intCodGrupo', 'Codigo do cliente de pesquisa.');
    echo input_field('dataInicio', 'Data de inicio da busca em string (ex: 2014-12-21)');
    echo input_field('dataFim', 'Data final de busca em string (ex: 2014-12-25)');
    echo input_field('intExportada', 'Publicacoes ja exportadas (sim ou nao) 1 ou 0.');
    echo button_submit('Enviar', 1);
    echo close_form();
}
