<?php

include '../validacao.php';

class PublicacoesXML
{
    private $recorte,
            $token,
            $codCliente;

    function __construct($data)
    {
        try
        {
            $this->recorte    = $data['recorte'];
            $this->token      = $data['token'];
            $this->codCliente = $data['codCliente'];
        }
        catch (Exception $e)
        {
            echo 'Parametros informados incorretamente.';
            die();
        }

        $this->validaCampos();
    }

    function validaCampos()
    {
        try
        {
            token_valido($this->recorte, $this->token);
        }
        catch (Exception $e)
        {
            return $e->getMessage();
            die();
        }
    }

    function view($resultados)
    {
        header("Content-type: text/xml; charset=utf-8");
        $recorte = $this->recorte;

        foreach($resultados as $i => $row)
        {
            foreach(array_keys($resultados[$i]) as $key)
            {
                $resultados[$i][$key] = htmlspecialchars($resultados[$i][$key]);
            }
        }
        include 'view.php';
    }
}
