<?php
set_time_limit (0);
ini_set("memory_limit", -1);
ini_set('max_execution_time', -1);


require 'Publicacao.php';

function busca_num_processo($texto)
{
    $PADRAO[] = '[0-9]{5}-[0-9]{2}.[0-9]{4}.[0-9]{3}.[0-9]{4}';
    $PADRAO[] = '[0-9 ]{1,9}[-|.][0-9 ]{1,9}[.][0-9 ]{1,9}[.][0-9 ]{1,9}[.][0-9 ]{1,9}[.][0-9 ]{1,9}[.|-|/][0-9 ]{1,9}';            //9-9.9.9.9.9/9 ou 9-9.9.9.9.9.9 ou 9-9.9.9.9.9-9
    $PADRAO[] = '[0-9 ]{1,9}[.][0-9 ]{1,9}[-][A-Za-z ]{1,3}[ (]{1,2}[0-9 ]{1,9}[|]{1,1}[0-9 ]{1,9}[ -]{1,1}[0-9 ]{1,9}[ )]{1,1}';   //287.211 - BA (2013|0016610-6)
    $PADRAO[] = '[0-9 ]{1,9}[-|.][0-9 ]{1,9}[.][0-9 ]{1,9}[.][0-9 ]{1,9}[.][0-9 ]{1,9}[.|-][0-9 ]{1,9}';                            //9-9.9.9.9.9 ou 9.9.9.9.9.9 ou 9.9.9.9.9-9
    $PADRAO[] = '[0-9 ]{1,9}[-|.][0-9 ]{1,9}[.][0-9 ]{1,9}[-|.][0-9 ]{1,9}[-|.|/][0-9 ]{1,9}';                                      //9.9.9-9/9 ou 9-9.9.9.9 ou  9.9.9.9-9
    $PADRAO[] = '[0-9 ]{1,9}[-|.][0-9 ]{1,9}[.][0-9 ]{1,9}[-|.|/][0-9 ]{1,9}';                                                      //9.9.9/9 ou 9-9.9.9 ou  9.9.9-9
    $PADRAO[] = '[0-9 ]{5,6}[-][0-9 ]{4,6}[ \][0-9 ]{3,4}';                                                                         //55555-4444\999
    $PADRAO[] = '[0-9 ]{3,9}[/][0-9 ]{4,5}';                                                                                        //999/4444
    $PADRAO[] = '[0-9 ]{1,9}[-|.][0-9 ]{1,9}[-|.|/][0-9 ]{1,9}';                                                                    //9.9/9 ou 9.9.9 ou  9.9-9
    $PADRAO[] = '[0-9 ]{1,9}[-|.][0-9 ]{1,9}[-|.|/][0-9 ]{1,9}';                                                                    //9/9 ou 9-9 ou  9.9
    $PADRAO[] = '[0-9]{1,9}[.][1-9]{1,5}';                                                                                          //9.9

    $num_processo = '';

    $complemento1 = '0000 - Nr: ';
    $complemento2 = '0000 - Agravo de Instrumento ';
    $complemento3 = '0000 - Protocolo Numero/Ano: ';
    $complemento4 = '0000 - Apelacao ';
    $complemento5 = 'Agravo de Instrumento ';
    $complemento6 = 'Protocolo Numero/Ano: ';
    $complemento7 = 'Apelacao ';

    foreach($PADRAO as $index => $value)
    {
        if($num_processo == '')
        {
            for($z = 1; $z < 8; $z++)
            {
                $padrao_novo00 = '';
                if($z == 1) {$padrao_novo00 = $complemento1.$value; $replace_00 = $complemento1;}
                if($z == 2) {$padrao_novo00 = $complemento2.$value; $replace_00 = $complemento2;}
                if($z == 3) {$padrao_novo00 = $complemento3.$value; $replace_00 = $complemento3;}
                if($z == 4) {$padrao_novo00 = $complemento4.$value; $replace_00 = $complemento4;}
                if($z == 5) {$padrao_novo00 = $complemento5.$value; $replace_00 = $complemento5;}
                if($z == 6) {$padrao_novo00 = $complemento6.$value; $replace_00 = $complemento6;}
                if($z == 7) {$padrao_novo00 = $complemento7.$value; $replace_00 = $complemento7;}

                if (@eregi($padrao_novo00,$texto,$resultado))
                {
                    $num_processo = $resultado[0];
                    $num_processo = str_replace($replace_00,'',$num_processo);
                    return $num_processo;
                }
            }

            if (@eregi($value,$texto,$resultado) and trim($num_processo) == '')
            {
                return $resultado[0];
            }
        }
    }

    return $num_processo;
}

class Consulta
{
    private $data        = null; # Parametro de busca da data
    private $jornal      = null; # Buscar em qual jornal
    private $codCliente  = null; # Cliente da busca
    private $numMaiorQue = null; # publicações com num maior que.
    private $recorte     = null; # Cliente
    private $siglaTribunal = null; # Sigla do Tribunal buscado
    private $intExportada = null;
    private $resultados  = null;
    private $exportada = null;

    function gravarResultado( $log , $cont)
    {
        # garantimos existencia do path requisitado
        # ignorando o ultimo nodo que é o nome do arquivo
        $pathArquivo = "log/$cont";    

        # Gravamos o resultado
        $arquivoResposta = fopen($pathArquivo . '.txt', 'w+');
        fwrite($arquivoResposta, $log);
        fclose($arquivoResposta);
    }

    function cliente($codCliente)
    {
        $this->codCliente = $codCliente;
        return $this;
    }

    function data($dataInicio, $dataFim = Null)
    {
        if (is_array($dataInicio))
        {
            $dataFim = $dataInicio[1];
            $dataInicio = $dataInicio[0];
        }
        else if ($dataFim == '' or is_null($dataFim))
        {
            $dataFim = $dataInicio;
        }

        $this->data = array("{$dataInicio} 00:00:00", "{$dataFim} 23:59:59");

        return $this;
    }
    
    function tribunalSigla($sigla)
    {
        $this->siglaTribunal = $sigla;
        return $this;
    }

    function jornal($jornal)
    {
        $this->jornal = strtoupper($jornal); # Normalizamos deixando todos maiusculos

        switch($this->jornal)
        {
            case "AMAZONAS":             $this->jornal_tabela = "PROC_AMAZONAS";            break;
            case "AMAPA":                $this->jornal_tabela = "PROC_AMAPA";               break;
            case "ACRE":                 $this->jornal_tabela = "PROC_ACRE";                break;
            case "ALAGOAS":              $this->jornal_tabela = "PROC_ALAGOAS";             break;
            case "CEARA":                $this->jornal_tabela = "PROC_CEARA";               break;
            case "BAHIA":                $this->jornal_tabela = "PROC_BAHIA";               break;
            case "MARANHAO":             $this->jornal_tabela = "PROC_MARANHAO";            break;
            case "TOCANTINS":            $this->jornal_tabela = "PROC_TOCANTINS";           break;
            case "RIO GRANDE DO SUL":    $this->jornal_tabela = "PROC_RIO_GRANDE_DO_SUL";   break;
            case "RIO GRANDE DO NORTE":  $this->jornal_tabela = "PROC_RIO_GRANDE_DO_NORTE"; break;
            case "MATO GROSSO":          $this->jornal_tabela = "PROC_MATO_GROSSO";         break;
            case "MATO GROSSO DO SUL":   $this->jornal_tabela = "PROC_MATO_GROSSO_DO_SUL";  break;
            case "MINAS GERAIS":         $this->jornal_tabela = "PROC_MINAS_GERAIS";        break;
            case "RIO DE JANEIRO":       $this->jornal_tabela = "PROC_RIO_DE_JANEIRO";      break;
            case "SANTA CATARINA":       $this->jornal_tabela = "PROC_SANTA_CATARINA";      break;
            case "PERNAMBUCO":           $this->jornal_tabela = "PROC_PERNAMBUCO";          break;
            case "PIAUI":                $this->jornal_tabela = "PROC_PIAUI";               break;
            case "GOIAS":                $this->jornal_tabela = "PROC_GOIAS";               break;
            case "RONDONIA":             $this->jornal_tabela = "PROC_RONDONIA";            break;
            case "RORAIMA":              $this->jornal_tabela = "PROC_RORAIMA";             break;
            case "SERGIPE":              $this->jornal_tabela = "PROC_SERGIPE";             break;
            case "TRIBUNAIS SUPERIORES": $this->jornal_tabela = "PROC_BRAS";                break;
            case "ESPIRITO SANTO":       $this->jornal_tabela = "PROC_ESPIRITO_SANTO";      break;
            case "DISTRITO FEDERAL":     $this->jornal_tabela = "PROC_DISTRITO_FEDERAL";    break;
            case "PARA":                 $this->jornal_tabela = "PROC_PARA";                break;
            case "PARAIBA":              $this->jornal_tabela = "PROC_PARAIBA";             break;
            case "PARANA":               $this->jornal_tabela = "PROC_PARANA";              break;
            case "SAO PAULO":            $this->jornal_tabela = "PROC_SAO_PAULO";           break;
            case "TODOS ESTADOS":        $this->jornal_tabela = "PROC_TODOS_ESTADOS";       break;

            default: throw new Exception('Jornal informado incorretamente');                break;
        }

        return $this;
    }

    function recorte($recorte)
    {
        $this->recorte = $recorte;
        return $this;
    }

    function numMaiorQue($num)
    {
        $this->numMaiorQue = $num;
        return $this;
    }

    function novos()
    {
        # Pegamos o ultimo protocolo lido
        $this->conectar();
        $row = sqlsrv_fetch_array(sqlsrv_query($this->conexao, "SELECT TOP 1 * FROM LOG_WEBSERVICE WHERE RECORTE = '{$this->recorte}' AND JORNAL = '{$this->jornal}' ORDER BY DATA DESC"));

        if (isset($row['NUM_ULT_PROTOCOLO'])) $this->numMaiorQue($row['NUM_ULT_PROTOCOLO']);
        else $this->numMaiorQue(0);
    }
    

    
    function setExportada($valor)
    {
             $this->exportada = $valor;
             return $this;
    }

    function getQuery()
    {
        # Configurações
        $select = array('P1.NUM as PROTOCOLO',
                        'P1.CODIGO as CODIGO_CLIENTE',
                        'P1.NOME as NOME_PESQUISA',
                        'P1.VARA',
                        'P1.N_PROCESSO',
                        'P1.ARQUIVO',
                        'P1.TRIBUNAL',
                        'P1.PUBLICACAO_EXPORTADA',
                        'E.NOME as NOME_ESCRITORIO',
                        'P2.PUBLICACAO',
                        'CONVERT(varchar(122), P1.DATA, 121) as DATA_PESQUISA',
                        'P1.ORDEM',
                        "'{$this->jornal}' as ESTADO",
                        "P1.N_PROCESSO"
                        );

        $from = array("{$this->jornal_tabela} P1",
                      "{$this->jornal_tabela}2 P2",
                      "ESCRITORIO E");

        # Contrução do where
        $where = array();

        # Junções
        $where[] = 'P1.NUM = P2.NUM2';
        $where[] = 'P1.CODIGO = E.CODIGO';

        # Se existir valor para revisado ele realiza tal filtragem
      /*  if (!is_null($this->exportada))
        {
            $where[] = 'P1.PUBLICACAO_EXPORTADA = ' . $this->exportada;
        }*/

        # Condicionais
        if ( ! is_null($this->numMaiorQue) ) $where[] = "P1.NUM > {$this->numMaiorQue}";
        if ( ! is_null($this->intExportada) ) $where[] = "P1.PUBLICACAO_EXPORTADA = {$this->intExportada}";
        if ( ! is_null($this->codCliente)  ) $where[] = "P1.CODIGO = {$this->codCliente}";
        if ( $this->jornal == "TODOS ESTADOS") $where[] = "P1.ESTADO = P2.ESTADO";
        if ( is_array($this->data)         ) $where[] = "DATA BETWEEN '{$this->data[0]}' AND '{$this->data[1]}'";
        if ( ! is_null($this->siglaTribunal) ) $where[] = "ARQUIVO LIKE '%{$this->siglaTribunal}%'";

        # Construção da consulta
        $sql = array();
        $sql[] = 'SELECT ' . implode(', ', $select);
        $sql[] = 'FROM ' . implode(', ', $from);
        $sql[] = 'WHERE ' . implode(' AND ', $where);
        $sql[] = 'ORDER BY P1.NUM DESC';

        return implode(' ', $sql);
    }


    function registrarLog()
    {
        if ( ! is_array($this->resultados)) return False;

        if (count($this->resultados) == 0)
        {
            $num_publicacoes = 0;
            $num_ult_protocolo = -1;
        }
        else
        {
            $num_publicacoes = count($this->resultados);
            $num_ult_protocolo = $this->resultados[$num_publicacoes - 1]['PROTOCOLO'];
        }

        $query = str_replace("'", "''", $this->getQuery());

        $this->conectar();
        sqlsrv_query($this->conexao,"INSERT INTO LOG_WEBSERVICE (RECORTE, COD_CLIENTE, JORNAL, DATA, NUM_ULT_PROTOCOLO, NUM_PROCESSOS, QUERY)
                     VALUES ('{$this->recorte}', '{$this->codCliente}', '{$this->jornal}', GETDATE(), {$num_ult_protocolo}, {$num_publicacoes}, '{$query}')");
    }

    function conectar()
    {

$server = "192.168.0.10";
$conexaoinfo = array('Database'=>"$this->recorte", "UID"=>"DESENVOLVIMENTO", "PWD"=>"DESENVOLVIMENTOADM");
$conexao = sqlsrv_connect($server, $conexaoinfo);


     $this->conexao = $conexao;
        //$conexao = sqlsrv_connect('192.168.0.10', 'DESENVOLVIMENTO', 'DESENVOLVIMENTOADM');
        //$db = mssql_select_db($this->recorte, $conexao);
    }

    # Retorna os resultados da consulta;
    function get()
    {
        $this->conectar();

        # Pegamos os resultados da consulta
        $query = sqlsrv_query($this->conexao, $this->getQuery());
        
        //var_dump($query);
        //var_dump($this->getQuery());
        //echo "\n\n";

        $this->resultados = array();
        while($row = sqlsrv_fetch_array($query))
        {
            # Normalizamos para apenas conter as chaves corretas.
            for($i = 0; $i < count($row); $i++)
            {
                unset($row[$i]);
            }

            # Normalizamos as variaveis
            $temp = array();
            foreach($row as $key => $value)
            {
                if (is_object($value))
                {
                    $temp[$key] = (string)$value;
                }
                else
                {
                    $temp[$key] = htmlspecialchars(utf8_encode($value));
                }
            }

            $this->resultados[] = $temp;
        }

        // Pegamos as datas
        foreach($this->resultados as $i => $row)
        {

            list($sigla, $resto) = explode('-', $row['ARQUIVO']);
            $query = sqlsrv_query($this->conexao,"SELECT CONVERT(VARCHAR(50),DATA_PUBLICACAO, 121 ) AS DATA_PUBLICACAO,
                                                         CONVERT(VARCHAR(50),DATA_DISPONIBILIZACAO, 121 ) AS DATA_DISPONIBILIZACAO,EDICAO
                                    FROM VISTA.[dbo].DIARIO_OFICIAL_MAPA
                                   WHERE DATA_VISTA = '{$row['DATA_PESQUISA']}'
                                     AND SIGLA = '{$sigla}'");

            $datas = sqlsrv_fetch_array($query);
            $this->resultados[$i]['DATA_PUBLICACAO'] = $datas['DATA_PUBLICACAO'];
            $this->resultados[$i]['DATA_DIVULGACAO'] = $datas['DATA_DISPONIBILIZACAO'];
            $this->resultados[$i]['EDICAO'] = $datas['EDICAO'];

            $NOME_NA_PUBLICACAO = $row['NOME_PESQUISA'];
            $CODIGO_DO_ESCRITORIO = $row['CODIGO_CLIENTE'];
            $query2 = sqlsrv_query($this->conexao,"SELECT num as codNome, nome as nomePesquisa FROM CLIENTE c
            WHERE (c.NOME = '$NOME_NA_PUBLICACAO' AND  c.CODIGO= '$CODIGO_DO_ESCRITORIO' AND C.NUM_NOME IS NULL) OR
            c.NUM IN (SELECT NUM_NOME FROM  CLIENTE WHERE NOME = '$NOME_NA_PUBLICACAO' AND CODIGO= '$CODIGO_DO_ESCRITORIO' AND NUM_NOME IS NOT NULL)");
            $NOMES_PUB = sqlsrv_fetch_array($query2);
            $this->resultados[$i]['CODVINCULO'] = $NOMES_PUB['codNome'];
            $this->resultados[$i]['NOMEVINCULO'] = $NOMES_PUB['nomePesquisa'];

            $this->resultados[$i]['N_PROCESSO'] = $row['N_PROCESSO'];
            //$this->resultados[$i]['N_PROCESSO'] = '12345678910';
            $this->resultados[$i]['ESTADO'] = $this->jornal;

        }

        $this->registrarLog();

        return $this->resultados;
    }

    function getDetalhado()
    {
        $publicacoes = $this->get();            
        $res = array();
        foreach($publicacoes as $key => $pub)
        {            
            $res[$key] = new Publicacao($this->recorte, $pub);
            #settype($res[$key], "Publicacao");
        }

        if (count($publicacoes) == 0)
        {
            return 'Nao ha publicacoes';
        }
        else
        {
            return $res;
        }
    }

    function getCodigos()
    {
        $this->conectar();

        $codigos = array();
        $query = sqlsrv_query($this->conexao,"SELECT CODIGO FROM ESCRITORIO ORDER BY CODIGO");
        while($row = sqlsrv_fetch_array($query)) $codigos[] = $row['CODIGO'];

        return $codigos;
    }
}

function codEstado( $estado )
{
	$cods = array(
		"ACRE" => "001",
		"ALAGOAS" => "002",
		"AMAZONAS" => "003",
		"AMAPA" => "004",
		"BAHIA" => "005",
		"CEARA" => "006",
		"DISTRITO FEDERAL" => "007",
		"ESPIRITO SANTO" => "008",
		"GOIAS" => "009",
		"MARANHAO" => "010",
		"MINAS GERAIS" => "011",
		"MATO GROSSO DO SUL" => "012",
		"MATO GROSSO" => "013",
		"PARA" => "014",
		"PARAIBA" => "015",
		"PERNAMBUCO" => "016",
		"PIAUI" => "017",
		"PARANA" => "018",
		"RIO DE JANEIRO" => "019",
		"RIO GRANDE DO NORTE" => "020",
		"RONDONIA" => "021",
		"RORAIMA" => "022",
		"RIO GRANDE DO SUL" => "023",
		"SANTA CATARINA" => "024",
		"SERGIPE" => "025",
		"SAO PAULO" => "026",
		"TOCANTINS" => "027",

		"BRASILIA" => "999",
		"TRIBUNAIS SUPERIORES" => "999"
	);

	return $cods[$estado];
}

function codEstadoreverso( $estado )
{
	$cods = array(
		"001" => "PROC_ACRE",
		"002" => "PROC_ALAGOAS",
		"003" => "PROC_AMAZONAS",
		"004" => "PROC_AMAPA",
		"005" => "PROC_BAHIA",
		"006" => "PROC_CEARA",
		"007" => "PROC_DISTRITO_FEDERAL",
		"008" => "PROC_ESPIRITO_SANTO",
		"009" => "PROC_GOIAS",
		"010" => "PROC_MARANHAO",
		"011" => "PROC_MINAS_GERAIS",
		"012" => "PROC_MATO_GROSSO_DO_SUL",
		"013" => "PROC_MATO_GROSSO",
		"014" => "PROC_PARA",
		"015" => "PROC_PARAIBA",
		"016" => "PROC_PERNAMBUCO",
		"017" => "PROC_PIAUI",
		"018" => "PROC_PARANA",
		"019" => "PROC_RIO_DE_JANEIRO",
		"020" => "PROC_RIO_GRANDE_DO_NORTE",
		"021" => "PROC_RONDONIA",
		"022" => "PROC_RORAIMA",
		"023" => "PROC_RIO_GRANDE_DO_SUL",
		"024" => "PROC_SANTA_CATARINA",
		"025" => "PROC_SERGIPE",
		"026" => "PROC_SAO_PAULO",
		"027" => "PROC_TOCANTINS",
		"999" => "PROC_BRASILIA"
	);

	return $cods[$estado];
}


function anoPublicacao( $data )
{
$entrada = trim($data);
	if (strstr($entrada, " ")){
		$aux2 = explode (" ", $entrada);
        $data = $aux2[0];
}
//RECE E CONFIGURA A DATA
$entrada = $data;
if (strstr($entrada, "-")){
$aux2 = explode ("-", $entrada);
$anoPublicacao = $aux2[0];
}

return $anoPublicacao;
}