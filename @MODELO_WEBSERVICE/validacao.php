<?php

$listaJornais = array("AMAZONAS", "AMAPA", "ACRE", "ALAGOAS", "CEARA", "BAHIA", "MARANHAO",
                     "TOCANTINS", "RIO GRANDE DO SUL", "RIO GRANDE DO NORTE", "MATO GROSSO",
                     "MATO GROSSO DO SUL", "MINAS GERAIS", "RIO DE JANEIRO", "SANTA CATARINA",
                     "PERNAMBUCO", "PIAUI", "GOIAS", "RONDONIA", "RORAIMA", "SERGIPE",
                     "TRIBUNAIS SUPERIORES", "ESPIRITO SANTO", "DISTRITO FEDERAL", "PARA", "PARAIBA",
                     "PARANA", "SAO PAULO","TODOS ESTADOS");

# Função de validação do token
function token_valido($recorte, $token)
{
    $res = (ereg_replace("[^a-z]", '', base64_encode(md5($recorte . 'md5#validation#check#token'))) == $token);
    if ( !$res ) throw new Exception('Token informado invalido');
}

# Função que confere se os dados inseridos são validos
function jornal_valido($jornal)
{
    global $listaJornais;

    $res = in_array(strtoupper($jornal), $listaJornais);
    if ( !$res ) throw new Exception('Jornal informado invalido');
}

# validação de data
function data_valida($data, $mascara = null)
{
    try
    {
        list($ano, $mes, $dia) = explode('-', $data);
        if ((int)$ano < 1900 || (int)$ano > 2100) throw new Exception('Erro');
        if ((int)$mes > 12 || (int)$mes < 0) throw new Exception('Erro');
        if ((int)$dia > 31 || (int)$dia < 0) throw new Exception('Erro');
    }
    catch(Exception $e)
    {
        throw new Exception('Data '.((!is_null($mascara)) ? $mascara . ' ' : '').'informada invalida.');
    }
}
