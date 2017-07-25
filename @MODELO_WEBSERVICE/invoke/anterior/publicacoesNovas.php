<?php
set_time_limit (0);

include '_helpers.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $recorte          = $_POST['recorte'];
    $token            = $_POST['token'];
    $codCliente       = $_POST['codCliente'];
    $jornal           = $_POST['jornal'];
    $numUltimaLeitura = $_POST['numUltimaLeitura'];

    $soap = new SoapClient(null, array('location' => "http://acessoweb.brasilia.me:8080/vsap/webservice2/webservice.php",
                                       'uri'      => "http://acessoweb.brasilia.me:8080/vsap/webservice2/webservice.php",
                                       'trace'    => 1
                                       ));

    echo "<h3>publicacoesNovas('$recorte', '$token', $codCliente, '$jornal', '$numUltimaLeitura')</h3>";
    $res = $soap->publicacoesNovas($recorte, $token, $codCliente, $jornal, $numUltimaLeitura);

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
    echo '<h3>publicacoesNovas(recorte, token, codCliente, jornal, numUltimaLeitura)</h3>';
    echo '<p style="color:red">O preenchimento de todos os campos &eacute; obrigat&oacute;rio</p>';
    echo open_form();
    echo input_field('recorte', 'Nome da empresa em caixa alta.');
    echo input_field('token', 'Token para validacao do servico.');
    echo input_field('codCliente', 'Codigo do cliente de pesquisa.');
    echo input_field('jornal', 'Estado das publicacoes em caixa alta.');
    echo input_field('numUltimaLeitura', 'Protocolo do ultima publicacao lida.');
    echo button_submit('Enviar', 1);
    echo close_form();
}
