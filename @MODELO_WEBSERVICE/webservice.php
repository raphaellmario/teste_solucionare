<?php
set_time_limit (0);
ini_set('default_socket_timeout', 600000);
include 'config.php';
# Criamos o servidor
$server = new SoapServer(null, array('uri' => 'http://acessows.sytes.net:9090/recorte/webservice/personalizado_dev/'.$cliente.'/webservice.php'));

# Adicionamos as possiveis views a ele.
require_once 'funcoes.php';

# Tratamos o request
if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    $server->handle();
}
else
{
    /*
    echo '<a href="invoke/publicacoesData.php">';
    echo 'publicacoesData(recorte, token, codCliente, jornal, data)';
    echo '</a><br />';
    */
    echo '<a href="invoke/publicacoesDataTodos.php">';
    echo 'getPublicacoes(strUsuario, strSenha, intCodGrupo, dataInicio, dataFim, jornal, intExportada)';
    echo '</a><br />';

    echo '<a href="invoke/publicacoesDataTodosCodigos.php">';
    echo 'getPublicacoesTodos(strUsuario, strSenha, dataInicio, dataFim, intExportada, intCodGrupo)';
    echo '</a><br />';
    
    echo '<a href="invoke/setpublicacoes.php">';
    echo 'setpublicacoes(strUsuario, strSenha, codPublicacao)';
    echo '</a><br />';

    /*
    echo '<a href="invoke/publicacoesEntreDatas.php">';
    echo 'publicacoesEntreDatas(recorte, token, codCliente, jornal, dataInicio, dataFim)';
    echo '</a><br />';

    echo '<a href="invoke/publicacoesNovas.php">';
    echo 'publicacoesNovas(recorte, token, codCliente, jornal, numUltimaLeitura)';
    echo '</a><br />';

    echo '<a href="invoke/publicacoesNovasTodosAuto.php">';
    echo 'publicacoesNovasTodosAuto(recorte, token, codCliente)';
    echo '</a><br />';
    
    echo '<a href="invoke/publicacoesDataDiario.php">';
    echo 'publicacoesDataDiario(recorte, token, codCliente, jornal, data, diario)';
    echo '</a><br />';
    
    echo '<a href="invoke/publicacoesDataTodosCodigosDiario.php">';
    echo 'publicacoesDataTodosCodigosDiario(recorte, token, data, diario)';
    echo '</a><br />';
    */
}

