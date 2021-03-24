<?php
// Copyright (c) 2016 Glauber Portella <glauberportella@gmail.com>

// Permission is hereby granted, free of charge, to any person obtaining a
// copy of this software and associated documentation files (the "Software"),
// to deal in the Software without restriction, including without limitation
// the rights to use, copy, modify, merge, publish, distribute, sublicense,
// and/or sell copies of the Software, and to permit persons to whom the
// Software is furnished to do so, subject to the following conditions:

// The above copyright notice and this permission notice shall be included in
// all copies or substantial portions of the Software.

// THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
// IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
// FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
// AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
// LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
// FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER
// DEALINGS IN THE SOFTWARE.

// Utilize esse arquivo para ponto de entrada de debug (por exemplo XDebug)

	   error_reporting(E_ALL);
	   ini_set('display_errors', 'On');


$conec = new conexao;
$conec->conecta('MYSQL');

	   
require_once __DIR__.'/vendor/autoload.php';

use CnabParser\Parser\Layout;
use CnabParser\Model\Remessa;
use CnabParser\Output\RemessaFile;

/*
 * Layout PAGAMENTOS FEBRABAN
 */

$remessaLayout = new Layout(__DIR__.'/config/sicoob/cnab240/cobranca.yml');
$remessa = new Remessa($remessaLayout);

// header arquivo
$remessa->header->codigo_banco = $codigobanco;
$remessa->header->tipo_inscricao = 2;
$remessa->header->inscricao_numero = $rowempresa['cnpj'];
$remessa->header->agencia = $agencia;
$remessa->header->conta = $conta;
$remessa->header->dac = 0;
$remessa->header->brancos_04 = $conta_dv;
$remessa->header->nome_empresa = retira_acentos_UTF8($rowempresa['razao']);
$remessa->header->data_geracao = date('dmY');
$remessa->header->hora_geracao = date('His');
$remessa->header->numero_sequencial_arquivo_retorno = 1;

// criar um novo lote de serviço para a remessa
// informando o código sequencial do lote
$lote = $remessa->novoLote(1);

// quantidade de Registros do Lote correspondente à soma da quantidade 
// dos registros tipo 1 (header_lote), 3(detalhes) e 5(trailer_lote)
$quantidade_registros_lote = 1;

$lote->header->codigo_banco = 756;
$lote->header->lote_servico = $lote->sequencial;
$lote->header->tipo_registro = 1;
$lote->header->tipo_operacao = 'R';
$lote->header->tipo_servico = '01';
$lote->header->zeros_01 = '';
$lote->header->versao_layout_lote = '040';
$lote->header->brancos_01 = '';
$lote->header->tipo_inscricao = 2;
$lote->header->inscricao_empresa = $rowempresa['cnpj'];
$lote->header->brancos_02 = '';
$lote->header->zeros_02 = 0;
$lote->header->agencia = $agencia;
$lote->header->brancos_03 = '';
$lote->header->zeros_03 = 0;
$lote->header->conta = $conta;
$lote->header->brancos_04 = $conta_dv;
$lote->header->dac = '';
$lote->header->nome_empresa = retira_acentos_UTF8($rowempresa['razao']);
$lote->header->brancos_05 = '';
$lote->header->numero_sequencial_arquivo_retorno = 1;
$lote->header->data_gravacao = date('dmY');
$lote->header->data_credito = '00000000';
$lote->header->brancos_06 = '';

	$numero_sequencial_registro_lote = 1;
	$quantidade_cobranca_simples = 1;
	$valor_total_cobranca_simples = 0;

 foreach ($dadosRemessa as $value) {

	$quantidade_registros_lote++;

	$detalhe = $lote->novoDetalhe();
	// segmento p
	$detalhe->segmento_p->lote_servico = $lote->sequencial;
	$detalhe->segmento_p->numero_sequencial_registro_lote = $numero_sequencial_registro_lote;
	$detalhe->segmento_p->codigo_ocorrencia = '01';
	$detalhe->segmento_p->agencia = $agencia; //$value["agencia"];
	$detalhe->segmento_p->dac = '';
	$detalhe->segmento_p->conta = $value["conta"];	
	$detalhe->segmento_p->brancos_02 = ''; //$value["agencia_dv"];	
	$detalhe->segmento_p->brancos_03 = $value["conta_dv"];
	//$detalhe->segmento_p->carteira = $value["carteira"];
	$detalhe->segmento_p->nosso_numero = $value["nosso_numero"];
	//$detalhe->segmento_p->dac_nosso_numero = $value["dac_nosso_numero"];
	$detalhe->segmento_p->numero_documento = $value["numero_documento"];
	$detalhe->segmento_p->codigo_carteira = 1;
	$detalhe->segmento_p->emissao_boleto = 2; // Beneficiário Emite
	$detalhe->segmento_p->distribuicao_boleto = '2'; // Beneficiário Distribui

	$data = str_replace("/", "-", $value["data_vencimento"]);
	$detalhe->segmento_p->vencimento = date('dmY', strtotime($data));

	$valor = str_replace("." , "" , $value["valor_boleto"]); // Primeiro tira os pontos
	$valor = str_replace("," , "" , $value["valor_boleto"]); // Depois tira a vírgula

	$valor_total_cobranca_simples+=$valor;

	$detalhe->segmento_p->valor_titulo = $valor;
	$detalhe->segmento_p->agencia_cobradora = 0;
	$detalhe->segmento_p->dac_agencia_cobradora = '';
	$detalhe->segmento_p->especie = '02';
	$detalhe->segmento_p->aceite = 'N';
	$detalhe->segmento_p->data_emissao = date('dmY');
	$detalhe->segmento_p->data_juros_mora = '00000000'; //date('dmY', strtotime($data));
	$detalhe->segmento_p->juros_1_dia = 0;
	$detalhe->segmento_p->data_1o_desconto = '00000000';
	$detalhe->segmento_p->valor_1o_desconto = 0;
	$detalhe->segmento_p->valor_iof = 0;
	$detalhe->segmento_p->valor_abatimento = 0;
	$detalhe->segmento_p->identificacao_titulo_empresa = $value["titulo_empresa"];
	$detalhe->segmento_p->codigo_negativacao_protesto = 3;
	$detalhe->segmento_p->prazo_negativacao_protesto = 0;
	$detalhe->segmento_p->codigo_baixa = 0;

	$numero_sequencial_registro_lote++;
	// segmento q
	$detalhe->segmento_q->lote_servico = $lote->sequencial;
	$detalhe->segmento_q->numero_sequencial_registro_lote = $numero_sequencial_registro_lote;
	$detalhe->segmento_q->codigo_ocorrencia = '01';
	$detalhe->segmento_q->tipo_inscricao = 1;
	$detalhe->segmento_q->inscricao_numero = $value["cpf"];
	$detalhe->segmento_q->nome_pagador = $value["sacado"];
	$detalhe->segmento_q->logradouro = $value["endremessa"];
	$detalhe->segmento_q->bairro = $value["bairemessa"];
	$detalhe->segmento_q->cep = substr($value["cepremessa"],0,5);
	$detalhe->segmento_q->sufixo_cep = substr($value["cepremessa"],5,3);
	$detalhe->segmento_q->cidade = $value["cidremessa"];
	$detalhe->segmento_q->uf = $value["ufremessa"];
	$detalhe->segmento_q->tipo_inscricao_sacador = 2;
	$detalhe->segmento_q->inscricao_sacador = $rowempresa['cnpj'];
	$detalhe->segmento_q->nome_sacador = retira_acentos_UTF8($rowempresa['razao']);
	// segmento r opcional nao adicionado
	unset($detalhe->segmento_r);
	// segmento y opcional nao adicionado
	unset($detalhe->segmento_y);
	// insere o detalhe no lote da remessa
	$lote->inserirDetalhe($detalhe);

	$numero_sequencial_registro_lote++;
	$quantidade_cobranca_simples++;
 }

 $quantidade_registros_lote++;

// trailer lote
$lote->trailer->lote_servico = $lote->sequencial;
$lote->trailer->quantidade_registros_lote = $quantidade_registros_lote; // quantidade de Registros do Lote correspondente à soma da quantidade dos registros tipo 1 (header_lote), 3(detalhes) e 5(trailer_lote)
$lote->trailer->quantidade_cobranca_simples = $quantidade_cobranca_simples;
$lote->trailer->valor_total_cobranca_simples = $valor_total_cobranca_simples;
$lote->trailer->quantidade_cobranca_vinculada = 0;
$lote->trailer->valor_total_cobranca_vinculada = 0;
$lote->trailer->aviso_bancario = '';
// inserir lote na remessa
$remessa->inserirLote($lote);

// trailer arquivo
$remessa->trailer->total_lotes = 1; // quantidade de Lotes do arquivo correspondente à soma da quantidade dos registros tipo 1 (header_lote).
$remessa->trailer->total_registros = $quantidade_registros_lote; // 6; //total da quantidade de Registros no arquivo correspondente à soma da quantidade dos registros tipo 0(header_arquivo), 1(header_lote), 3(detalhes), 5(trailer_lote) e 9(trailer_arquivo).

// gera arquivo
$remessaFile = new RemessaFile($remessa);
$dirarquivoremessa = __DIR__.'/remessas/out/';
$lcFile = [];
$lcFileDownload = [];

for ($i = 1; $i < 99; $i++) {
	$seq = str_pad($i, 2, '0', STR_PAD_LEFT);
	$arquivogravacao = $dirarquivoremessa.'CB'.date('d').date('m').$seq;
	$lcFile = 'CB'.date('d').date('m').$seq;
	if(!file_exists($arquivogravacao.".REM")) {
		break;
	}
}

	$lcFile = $lcFile.".REM";
	$lcFileDownload = $lcFile;

       //numero do lote
	   $qryNroDoc = "update carne_lote set arquivo='".$lcFile."' where id = ".$numero_lote;
	   $exeNroDoc = mysqli_query($conec->con,$qryNroDoc) or die('Erro na query: ' .$qryNroDoc. mysqli_error($conec->con));


$cfile = $arquivogravacao.".REM";
//$remessaFile->generate(__DIR__.'/remessas/out/itaucobranca240.rem');
$remessaFile->generate($cfile);

       //numero do lote
	   $qryNroDoc = "SELECT max(id) as proximo FROM carne_lote";
	   $exeNroDoc = mysqli_query($conec->con,$qryNroDoc) or die('Erro na query: ' .$qryNroDoc. mysqli_error($conec->con));
	   $rownrodoc = mysqli_fetch_array($exeNroDoc);
	   $numero_lote = $rownrodoc['proximo'];

// Gravando o Arquivo de Remessa
echo "<script>";
echo "  window.location.href = 'cnab240/download_remessa.php?file=".$lcFileDownload."';";
echo "  window.open('../../admin/geral/relremessa.php?lote=".$numero_lote."', '_blank');";
echo "</script>";


exit;

?>
