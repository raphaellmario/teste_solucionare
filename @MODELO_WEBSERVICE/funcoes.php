<?php
require_once 'consulta.php';
require_once 'validacao.php';
require_once 'ResultadoPublicacaoLida.php';

# ----------------------------------------------------

/**
 * Retorna todas publicações do jornal informado
 *
 * @param recorte string                Nome do recorte
 * @param token string                  É o token que valida o cliente
 * @param codCliente int                Código do cliente
 * @param jornal string restricted      String que se refere aquele jornal
 * @param data date_string              Data em formato string (2014-04-21)
 */
function publicacoesData($recorte, $token, $codCliente, $jornal, $data, $intExportada = null)
{
    try
    {
        # Validação
        token_valido($recorte, $token);
        jornal_valido($jornal);
        //data_valida($data);

        # Consutruimos a consulta
        $consulta = new Consulta();
        $consulta->recorte($recorte)
                 ->cliente($codCliente)
                 ->jornal($jornal)
                 ->data($data)
                 ->setExportada($intExportada);

        # Executamos e armazenamos o resultado
        return $consulta->getDetalhado();
    }
    catch(Exception $e)
    {
        return $e->getMessage();
    }

}
$server->addFunction('publicacoesData'); # Registramos a view

# ----------------------------------------------------

/**
 * Retorna todas publicações do jornal informado
 *
 * @param recorte string                Nome do recorte
 * @param token string                  É o token que valida o cliente
 * @param codCliente int                Código do cliente
 * @param jornal string restricted      String que se refere aquele jornal
 * @param data date_string              Data em formato string (2014-04-21)
 */
function publicacoesDataDiario($recorte, $token, $codCliente, $jornal, $data, $sigla)
{
    try
    {
        # Validação
        token_valido($recorte, $token);
        jornal_valido($jornal);
        //data_valida($data);

        # Consutruimos a consulta
        $consulta = new Consulta();
        $consulta->recorte($recorte)
                 ->cliente($codCliente)
                 ->jornal($jornal)
                 ->tribunalSigla($sigla)
                 ->data($data);

        # Executamos e armazenamos o resultado
        return $consulta->getDetalhado();
    }
    catch(Exception $e)
    {
        return $e->getMessage();
    }

}
$server->addFunction('publicacoesDataDiario'); # Registramos a view

# ----------------------------------------------------

function getPublicacoesTodos($recorte, $token, $dataInicio, $dataFim, $intExportada, $intCodGrupo)
{
    try
    {
        # Validação
        token_valido($recorte, $token);
        data_valida($dataInicio);
        data_valida($dataFim);
    }
    catch (Exception $e)
    {
        return $e->getMessage();
    }

    global $listaJornais;
    $resultados = array();

    $consulta = new Consulta();
    //$codigos = $consulta->recorte($recorte)->getCodigos();

    //foreach($codigos as $cod)
    //{
        foreach($listaJornais as $jornal)
        {
            if($jornal != "TODOS ESTADOS"){
                # Criamos a nova consulta
                $consulta = new Consulta();
                $temp = $consulta->recorte($recorte)->cliente($intCodGrupo)->jornal($jornal)->data($dataInicio, $dataFim)->setExportada($intExportada)->getDetalhado();
                
                // Juntamos td
                foreach($temp as $pub)
                {
                    $resultados[] = $pub;
                }
            }
        }
        
    //}

    return $resultados;
}  
$server->addFunction('getPublicacoesTodos'); # Registramos a view

# ----------------------------------------------------
function setpublicacoes($recorte, $token, $id)
{

    try
    {
        token_valido($recorte, $token);
    }
    catch (Exception $e)
    {
        return $e->getMessage();
    }

   $resposta = array();

            $identificador_uf = substr($id,(strlen($id)-3),strlen($id));
            $id_banco=substr($id, 0, strlen($id) - 3);
            $TABELA_JORNAL=codEstadoreverso($identificador_uf);
             //recebimento e tratamento dos ids.
            $server = "192.168.0.10";
            $conexaoinfo = array('Database'=>"$recorte", "UID"=>"DESENVOLVIMENTO", "PWD"=>"DESENVOLVIMENTOADM");
            $conexao = sqlsrv_connect($server, $conexaoinfo);
            //$this->conexao = $conexao;
            $query = sqlsrv_query($conexao,"UPDATE $TABELA_JORNAL SET PUBLICACAO_EXPORTADA = '1' WHERE NUM ='$id_banco'");

            if($query){
                sqlsrv_query($conexao,"INSERT INTO LOG (DATA, HORA, USUARIO, ACAO, DISCRIMINACAO, ERRO, TEMPO_EXEC)
                VALUES (GETDATE(),GETDATE(),'{$recorte}', 'WEBSERVICE', 'SETPUBLICACOES - Setou a coluna PUBLICACAO_EXPORTADA da publicacao de ID unico $id_banco para 1','N',GETDATE())");
            }

   $item = new ResultadoPublicacaoLida();
   $item->idPublicacao = $id;
   $item->status = '1';
   
   $resposta[$id] = $item;

   return $resposta;

}
$server->addFunction('setpublicacoes'); # Registramos a view



function publicacoesDataTodosCodigosDiario($recorte, $token, $data, $sigla)
{
    try
    {
        # Validação
        token_valido($recorte, $token);
        data_valida($data);
    }
    catch (Exception $e)
    {
        return $e->getMessage();
    }

    global $listaJornais;
    $resultados = array();

    $consulta = new Consulta();
    $codigos = $consulta->recorte($recorte)->getCodigos();

    foreach($codigos as $cod)
    {
        foreach($listaJornais as $jornal)
        {
            # Criamos a nova consulta
            $consulta = new Consulta();
            $resultados['codigo:'.$cod][$jornal] = $consulta->recorte($recorte)
                    ->cliente($cod)
                    ->jornal($jornal)
                    ->tribunalSigla($sigla)
                    ->data($data)
                    ->getDetalhado();
        }
    }

    return $resultados;
}  
$server->addFunction('publicacoesDataTodosCodigosDiario'); # Registramos a view


# ----------------------------------------------------

/**
 * Retorna todas publicações do jornal informado
 *
 * @param recorte string                Nome do recorte
 * @param token string                  É o token que valida o cliente
 * @param codCliente int                Código do cliente
 * @param data date_string              Data em formato string (2014-04-21)
 */
function getPublicacoes($recorte, $token, $codCliente, $dataInicio, $dataFim, $jornal, $intExportada)
{
    try
    {
        token_valido($recorte, $token);
        data_valida($dataInicio);
        data_valida($dataFim);
    }
    catch (Exception $e)
    {
        return $e->getMessage();
    }

    //global $listaJornais;
    $resultados = array();

    $datas = array($dataInicio, $dataFim);

    //foreach($listaJornais as $jornal)
    //{
        $resultados = publicacoesData($recorte, $token, $codCliente, $jornal, $datas, $intExportada);

        // Juntamos td
      /*  foreach($temp as $pub)
        {
           $resultados[] = $pub;
        }*/
    //}

    return $resultados;
}
$server->addFunction('getPublicacoes'); # Registramos a view

# ----------------------------------------------------

/**
 * Retorna todas as publicações entre um periodo
 *
 * @param recorte string                Nome do recorte
 * @param token string                  É o token que valida o cliente
 * @param codCliente int                Código do cliente
 * @param jornal string restricted      String que se refere aquele jornal
 * @param dataInicio date_string        Data em formato string (2014-04-21)
 * @param dataFim date_string           Data em formato string (2014-04-21)
 */
function publicacoesEntreDatas($recorte, $token, $codCliente, $jornal, $dataInicio, $dataFim)
{
    try
    {
        # Validação
        token_valido($recorte, $token);
        jornal_valido($jornal);

        # Consutruimos a consulta
        $consulta = new Consulta();
        $consulta->recorte($recorte)
                 ->cliente($codCliente)
                 ->jornal($jornal)
                 ->data($dataInicio, $dataFim);

        # Executamos e armazenamos o resultado
        return $consulta->getDetalhado();
    }
    catch(Exception $e)
    {
        return $e->getMessage();
    }
}
$server->addFunction('publicacoesEntreDatas'); # Registramos a view

# ----------------------------------------------------

/**
 * Retorna todas as publicações entre um periodo
 *
 * @param recorte string                Nome do recorte
 * @param token string                  É o token que valida o cliente
 * @param codCliente int                Código do cliente
 * @param jornal string restricted      String que se refere aquele jornal
 * @param numUltimaLeitura int          Código da publicação da ultima leitura realizada para aquele jornal.
 */
function publicacoesNovas($recorte, $token, $codCliente, $jornal, $numUltimaLeitura)
{
    try
    {
        # Validação
        token_valido($recorte, $token);
        jornal_valido($jornal);

        # Ultimo código lido deve ser valido.
        if ( ! is_int($numUltimaLeitura) ) $numUltimaLeitura = 0;

        # Consutruimos a consulta
        $consulta = new Consulta();
        $consulta->recorte($recorte)
                 ->cliente($codCliente)
                 ->jornal($jornal)
                 ->numMaiorQue($numUltimaLeitura);

        # Executamos e armazenamos o resultado
        return $consulta->getDetalhado();
    }
    catch(Exception $e)
    {
        return $e->getMessage();
    }
}
$server->addFunction('publicacoesNovas'); # Registramos a view

# ----------------------------------------------------

function publicacoesNovasTodosAuto($recorte, $token, $codCliente)
{
    try
    {
        token_valido($recorte, $token);
    }
    catch (Exception $e)
    {
        return $e->getMessage();
    }

    global $listaJornais;
    $resultados = array();

    foreach($listaJornais as $jornal)
    {
        # Consutruimos a consulta
        $consulta = new Consulta();
        $consulta->recorte($recorte)
                 ->cliente($codCliente)
                 ->jornal($jornal)
                 ->novos();

        # Executamos e armazenamos o resultado
        foreach($consulta->getDetalhado() as $item)
        {
            $resultados[] = $item;
        }
    }

    return $resultados;
}
$server->addFunction('publicacoesNovasTodosAuto'); # Registramos a view
