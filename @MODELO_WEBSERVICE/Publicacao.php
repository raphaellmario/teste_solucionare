<?php

class Publicacao
{
    public $NomeEscritorio;
    public $CodigoEscritorio;
    public $anoPublicacao;
    public $codPublicacao;
    public $edicaoDiario;
    public $descricaoDiario;
    public $descricaoUF;
    public $paginaInicial;
    public $paginaFinal;
    public $dataPublicacao;
    public $dataDivulgacao;
    public $dataCadastro;
    public $numeroProcesso;
    public $cidadePublicacao;
    public $orgaoDescricao;
    public $varaDescricao;
	public $despachoPublicacao;
   	public $processoPublicacao;
   	public $publicacaoCorrigida;
  	public $codVinculo;
   	public $nomeVinculo;
   	public $OABNumero;
   	public $OABEstado;
   	public $codIntegracao;
   	public $publicacaoExportada;


    function __construct($recorte, $vetor)
    {

        $this->NomeEscritorio = $vetor['NOME_ESCRITORIO'];
        $this->CodigoEscritorio = $vetor['CODIGO_CLIENTE'];
        $this->anoPublicacao = anoPublicacao($vetor['DATA_PUBLICACAO']);
        $this->codPublicacao = $vetor['PROTOCOLO'].codEstado($vetor['ESTADO']);
        $this->edicaoDiario  = $vetor['EDICAO'];
        $this->descricaoDiario = substr($vetor['ARQUIVO'], 0, strpos($vetor['ARQUIVO'], '-'));
        $this->descricaoUF = $vetor['ESTADO'];
        $this->paginaInicial = "1";
        $this->paginaFinal = "1";
        $this->dataPublicacao = $vetor['DATA_PUBLICACAO'];
        $this->dataDivulgacao = $vetor['DATA_DIVULGACAO'];
        $this->dataCadastro = $vetor['DATA_PESQUISA'];
        $this->numeroProcesso = $vetor['N_PROCESSO'];
        $this->cidadePublicacao = htmlspecialchars(utf8_encode($vetor['VARA']));
        $this->orgaoDescricao = htmlspecialchars(utf8_encode($vetor['TRIBUNAL']));
        $this->varaDescricao = htmlspecialchars(utf8_encode($vetor['VARA']));
        $this->despachoPublicacao = ".";
        $this->processoPublicacao = htmlspecialchars(utf8_encode($vetor['PUBLICACAO']));
        //$this->processoPublicacao = $vetor['PUBLICACAO'];
        $this->publicacaoCorrigida = "0";
        $this->codVinculo = $vetor['CODVINCULO'];
        $this->nomeVinculo = $vetor['NOMEVINCULO'];
        $this->OABNumero = "0";
        $this->OABEstado = "0";
        $this->codIntegracao = $vetor['CODIGO_CLIENTE'];
        $this->publicacaoExportada = $vetor['PUBLICACAO_EXPORTADA'];     
    }
}
