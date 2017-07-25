<?php

include '../Consulta.php';
include 'PublicacoesXML.php';

$recorte    = $_GET['recorte'];
$codCliente = $_GET['codCliente'];
$jornal     = $_GET['token'];

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
    foreach($consulta->get() as $row)
    {
        $resultados[] = $row;
    }
}

$xml = new PublicacoesXML($_GET);
$xml->view($resultados);
