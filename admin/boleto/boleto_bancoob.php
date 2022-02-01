<?php

// +----------------------------------------------------------------------+
// | BoletoPhp - Versão Beta                                              |
// +----------------------------------------------------------------------+
// | Este arquivo está disponível sob a Licença GPL disponível pela Web   |
// | em http://pt.wikipedia.org/wiki/GNU_General_Public_License           |
// | Você deve ter recebido uma cópia da GNU Public License junto com     |
// | esse pacote; se não, escreva para:                                   |
// |                                                                      |
// | Free Software Foundation, Inc.                                       |
// | 59 Temple Place - Suite 330                                          |
// | Boston, MA 02111-1307, USA.                                          |
// +----------------------------------------------------------------------+

// +----------------------------------------------------------------------+
// | Originado do Projeto BBBoletoFree que tiveram colaborações de Daniel |
// | William Schultz e Leandro Maniezo que por sua vez foi derivado do    |
// | PHPBoleto de João Prado Maia e Pablo Martins F. Costa                |
// |                                                                      |
// | Se vc quer colaborar, nos ajude a desenvolver p/ os demais bancos :-)|
// | Acesse o site do Projeto BoletoPhp: www.boletophp.com.br             |
// +----------------------------------------------------------------------+

// +----------------------------------------------------------------------+
// | Equipe Coordenação Projeto BoletoPhp: <boletophp@boletophp.com.br>   |
// | Desenvolvimento Boleto BANCOOB/SICOOB: Marcelo de Souza              |
// | Ajuste de algumas rotinas: Anderson Nuernberg                        |
// +----------------------------------------------------------------------+


// ------------------------- DADOS DINÂMICOS DO SEU CLIENTE PARA A GERAÇÃO DO BOLETO (FIXO OU VIA GET) -------------------- //
// Os valores abaixo podem ser colocados manualmente ou ajustados p/ formulário c/ POST, GET ou de BD (MySql,Postgre,etc)	//

	session_start();

	include ("../../includes/include_geral_III.php");
	include ("../../includes/classes/paging.class.php");

	$conec = new conexao;
	$conec->conecta('MYSQL');

	$dtinicial = Fdate($_POST['datainicio']);
	$dtfinal = Fdate($_POST['datafim']);
	$bancoEmissor = $_POST['bancoemissor'];

	// $dtfinal = $dtinicial;
	
	$data_inicial = explode("/", $_POST['datainicio']);
	$data_inicial = fdate($data_inicial[2]."-".$data_inicial[1]."-".$data_inicial[0]);
	
	$MesIni = date( 'm', strtotime($dtinicial));
	$MesFim = date( 'm', strtotime($dtfinal));
	$MesFimPos = date( 'm', strtotime($dtfinal));
	$AnoIni = date( 'Y', strtotime($dtinicial));
	$AnoFim = date( 'Y', strtotime($dtfinal));
	$DiaIni = date( 'd', strtotime($dtfinal));
	
	$outroano = false;
	$totaltaxasparcitular = 0;
	$nomeNoBoleto = '';
	
	if($AnoIni <> $AnoFim && $MesFim <= $MesIni) {
		$MesFim = 12;
		$outroano = true;
	}
	
	
	// Dados da Empresa
	$qrylocal = "SELECT * from cadastro_unidades where codigo=".$_SESSION['s_local']."";
	$exelocal = mysqli_query($conec->con,$qrylocal) or die('Erro na query: ' .$qrylocal. mysqli_error($conec->con));
	$rowempresa = mysqli_fetch_array($exelocal);

	// Dados do Banco
   	$queryConfig = "SELECT id, nome, bancoemissor, nroagencia, digitoagencia, nroconta, digitoconta, nrocontrato, infocliente1, infocliente2, infocliente3, instrucaocaixa1, instrucaocaixa2, instrucaocaixa3, dirarquivoremessa, carteiracobranca FROM carne_bancos where nome = '".$bancoEmissor."'";
	$resulConfig = mysqli_query($conec->con,$queryConfig) or die('ERRO NA QUERY !'.$queryConfig);
	$rowconfig = mysqli_fetch_array($resulConfig);
	

	// Qtde de Dependentes
	
   	$queryCliente = "SELECT count(*) as qtde_dep FROM carne_dependente where idtitular = '".$_POST['titular']."'";
	$resulCliente = mysqli_query($conec->con,$queryCliente) or die('ERRO NA QUERY !'.$queryCliente);
	$i=0;
	while($rowQtde = mysqli_fetch_array($resulCliente)){
		$qtde = $rowQtde['qtde_dep'];
		$i++;
	}
	
	if($i > 0) {
		
		$nQtdeDep = $qtde;
		
	} else {
		
		$nQtdeDep = 0;
	}

	// Pego total das taxas do Titular se houver
	$queryTaxas = "SELECT sum(aeromedico+comissao+coopart+taxabanco+apene) as totaltaxas, nomeboleto FROM carne_taxastitular where idtitular = '".$_POST['titular']."'";
	$resulTaxas = mysqli_query($conec->con,$queryTaxas) or die('ERRO NA QUERY !'.$queryTaxas);
	if (mysqli_num_rows($resulTaxas) > 0) {
		$rowtaxas = mysqli_fetch_array($resulTaxas);
		$totaltaxasparcitular = $rowtaxas['totaltaxas'];
		$nomeNoBoleto = $rowtaxas['nomeboleto'];
	}

	
	// Dados do Cliente
   	$queryCliente = "SELECT a.id,a.nometitular,a.endereco,a.numero,a.cep,a.bairro,a.cidade,a.uf,a.cpf,b.nrocontrato,b.diavencto,c.descricao,d.valor,d.valor_dependente,a.valorplano, d.vlrfixonegociado FROM carne_titular a".
   	" join carne_contratos b on b.idtitular = a.id".
   	" join carne_tipoplano c on c.id = b.plano".
   	" join carne_competenciaplano d on d.idplano = c.id".   	
   	" where a.id = '".$_POST['titular']."'";
	$resulCliente = mysqli_query($conec->con,$queryCliente) or die('ERRO NA QUERY !'.$queryCliente);

$DataVencimento = $_POST['datainicio'];
$somames = 1;

while($rowcliente = mysqli_fetch_array($resulCliente)){

$contador = 1;
$qtdeInicio = $MesFim;

For ($x=$MesIni; $x<=$MesFim; $x++) {

	$qtdeFim = $x;
	
 	if($x>$MesIni) {
 	 $DataVencimento = date('d/m/Y', strtotime("+".$somames." month", strtotime($data_inicial)));
 	 $somames++;
 	}
		
 
 // Dados do numero do documento - numero_documento
 $qryNroDoc = "SELECT a.AUTO_INCREMENT as proximo FROM information_schema.tables a  WHERE a.table_name = 'carne_remessabanco' and table_schema = '".SQL_DB."'";
 $exeNroDoc = mysqli_query($conec->con,$qryNroDoc) or die('Erro na query: ' .$qryNroDoc. mysqli_error($conec->con));
 $rownrodoc = mysqli_fetch_array($exeNroDoc);
 $numero_documento = $rownrodoc['proximo'];
 
 	
$dirarquivoremessa = $rowconfig['dirarquivoremessa']; 
$carteiracobranca = $rowconfig['carteiracobranca'];
		
	
// DADOS DO BOLETO PARA O SEU CLIENTE
$dias_de_prazo_para_pagamento = 7;
$taxa_boleto = 0;
$data_venc = $DataVencimento; //"14/05/2013";//date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006"; 

$valor_cobrado = $rowcliente['valor']; // "1,00"; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal

// Soma o valor dos dependentes com o Titular
if($nQtdeDep > 0) {
	$valor_cobrado = $rowcliente['valor'] + ($rowcliente['valor_dependente'] * $nQtdeDep);
}

// Valor Negociado com o Cliente
if($rowcliente['vlrfixonegociado'] == 2 && $rowcliente['valorplano'] > 0){
	$valor_cobrado = $rowcliente['valorplano'];
}

		
$valor_cobrado = str_replace(",", ".",$valor_cobrado);
$valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');

//$dadosboleto["nosso_numero"] = "08123456";  // Até 8 digitos, sendo os 2 primeiros o ano atual (Ex.: 08 se for 2008)


/*************************************************************************
 * +++
 *************************************************************************/

// http://www.bancoob.com.br/atendimentocobranca/CAS/2_Implanta%C3%A7%C3%A3o_do_Servi%C3%A7o/Sistema_Proprio/DigitoVerificador.htm
// http://blog.inhosting.com.br/calculo-do-nosso-numero-no-boleto-bancoob-sicoob-do-boletophp/
// http://www.samuca.eti.br
// 
// http://www.bancoob.com.br/atendimentocobranca/CAS/2_Implanta%C3%A7%C3%A3o_do_Servi%C3%A7o/Sistema_Proprio/LinhaDigitavelCodicodeBarras.htm

// Contribuição de script por:
// 
// Samuel de L. Hantschel
// Site: www.samuca.eti.br
// 

if(!function_exists('formata_numdoc'))
{
	function formata_numdoc($num,$tamanho)
	{
		while(strlen($num)<$tamanho)
		{
			$num="0".$num; 
		}
	return $num;
	}
}

$IdDoSeuSistemaAutoIncremento = $numero_documento; //'2'; // Deve informar um numero sequencial a ser passada a função abaixo, Até 6 dígitos
$agencia = $rowconfig['nroagencia']; // Num da agencia, sem digito
$agencia_dv = $rowconfig['digitoagencia']; // Digito Verificador da agencia
$conta = $rowconfig['nroconta']; // Num da conta, sem digito
$conta_dv = $rowconfig['digitoconta']; // Digito do nro da conta
$convenio = $rowconfig['nrocontrato']; //Número do convênio indicado no frontend

$NossoNumero = formata_numdoc($IdDoSeuSistemaAutoIncremento,7);
$qtde_nosso_numero = strlen($NossoNumero);
$sequencia = formata_numdoc($agencia,4).formata_numdoc(str_replace("-","",$convenio),10).formata_numdoc($NossoNumero,7);
$cont=0;
$calculoDv = 0;
	for($num=0;$num<=strlen($sequencia);$num++)
	{
		$cont++;
		if($cont == 1)
		{
			// constante fixa Sicoob » 3197 
			$constante = 3;
		}
		if($cont == 2)
		{
			$constante = 1;
		}
		if($cont == 3)
		{
			$constante = 9;
		}
		if($cont == 4)
		{
			$constante = 7;
			$cont = 0;
		}

		$somando = intval(substr($sequencia,$num,1));

		//$calculoDv = $calculoDv + (substr($sequencia,$num,1) * $constante);
		$calculoDv = $calculoDv + ($somando * $constante);

	}
	
$Resto = $calculoDv % 11;
$Dv = 11 - $Resto;
if ($Dv == 0) $Dv = 0;
if ($Dv == 1) $Dv = 0;
if ($Dv > 9) $Dv = 0;
$dadosboleto["nosso_numero"] = $NossoNumero . $Dv;
$dadosboleto["nro_lote"] = 0;

/*************************************************************************
 * +++
 *************************************************************************/


$dadosboleto["numero_documento"] = $numero_documento; //"12";	// Num do pedido ou do documento
$dadosboleto["data_vencimento"] = $data_venc; // Data de Vencimento do Boleto - REGRA: Formato DD/MM/AAAA
$dadosboleto["data_documento"] = date("d/m/Y"); // Data de emissão do Boleto
$dadosboleto["data_processamento"] = date("d/m/Y"); // Data de processamento do boleto (opcional)
$dadosboleto["valor_boleto"] = $valor_boleto; 	// Valor do Boleto - REGRA: Com vírgula e sempre com duas casas depois da virgula

// DADOS DO SEU CLIENTE
$dadosboleto["sacado"] = substr($rowcliente['nometitular'],0,40);
$dadosboleto["endereco1"] = $rowcliente['endereco']." ".$rowcliente['numero']." ".$rowcliente['bairro'];
$dadosboleto["endereco2"] = $rowcliente['cidade']."-".$rowcliente['uf']." Cep:".$rowcliente['cep'];
$dadosboleto["cpf"] = str_replace('.', '', $rowcliente['cpf']);
$dadosboleto["cpf"] = str_replace('-', '', $dadosboleto["cpf"]);
$dadosboleto["cpf"] = trim($dadosboleto["cpf"]);


if($totaltaxasparcitular > 0) {
	$valor_boleto = str_replace(",", ".",$valor_boleto);
	$dadosboleto["valor_boleto"] = number_format($valor_boleto + $totaltaxasparcitular, 2, ',', '');
}
if(!empty($nomeNoBoleto)) {
	$dadosboleto["sacado"] = substr($nomeNoBoleto,0,40);
}


// INFORMACOES PARA O CLIENTE
$dadosboleto["demonstrativo1"] = "Pagamento referente a ".$rowcliente['descricao']; //$rowconfig['infocliente1']; //Pagamento de Compra na Loja Nonononono";
$dadosboleto["demonstrativo2"] = $rowconfig['infocliente2']; //"Mensalidade referente a nonon nonooon nononon<br>Taxa bancária - R$ ".number_format($taxa_boleto, 2, ',', '');
$dadosboleto["demonstrativo3"] = $rowconfig['infocliente3']; //"BoletoPhp - http://www.boletophp.com.br";

// INSTRUÇÕES PARA O CAIXA
$dadosboleto["instrucoes1"] = $rowconfig['instrucaocaixa1']; //"- Sr. Caixa, cobrar multa de 2% após o vencimento";
$dadosboleto["instrucoes2"] = $rowconfig['instrucaocaixa2']; //"- Receber até 10 dias após o vencimento";
$dadosboleto["instrucoes3"] = $rowconfig['instrucaocaixa3']; //"- Em caso de dúvidas entre em contato conosco: xxxx@xxxx.com.br";
$dadosboleto["instrucoes4"] = ""; //"&nbsp; Emitido pelo sistema Projeto BoletoPhp - www.boletophp.com.br";

// DADOS OPCIONAIS DE ACORDO COM O BANCO OU CLIENTE
$dadosboleto["quantidade"] = ""; //01";
$dadosboleto["valor_unitario"] = ""; //10";
$dadosboleto["aceite"] = "N";		
$dadosboleto["especie"] = "R$";
$dadosboleto["especie_doc"] = "DM";


// ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //
// DADOS ESPECIFICOS DO SICOOB
$dadosboleto["modalidade_cobranca"] = "02";
$dadosboleto["numero_parcela"] = "901";


// DADOS DA SUA CONTA - BANCO SICOOB
$dadosboleto["agencia"] = $agencia; // Num da agencia, sem digito
$dadosboleto["agencia_dv"] = $agencia_dv; // Digito do Num da agencia

$dadosboleto["conta"] = $conta; // Num da conta, sem digito
$dadosboleto["conta_dv"] = $conta_dv; 	// Digito do Num da conta


// DADOS PERSONALIZADOS - Bradesco
$dadosboleto["conta_cedente"] = $conta; // ContaCedente do Cliente, sem digito (Somente N�meros)
$dadosboleto["conta_cedente_dv"] = $conta_dv; // Digito da ContaCedente do Cliente
if($bancoEmissor=='Bradesco') {
	$dadosboleto["carteira"] = "06";  // C�digo da Carteira: pode ser 06 ou 03
}


// DADOS PERSONALIZADOS - SICOOB
$dadosboleto["convenio"] = $convenio; // Num do convênio - REGRA: No máximo 7 dígitos
if($bancoEmissor=='Sicoob') {
	$dadosboleto["carteira"] = "1";
}

// SEUS DADOS
$dadosboleto["identificacao"] = retira_acentos_UTF8($rowempresa['razao']);
$dadosboleto["cpf_cnpj"] = $rowempresa['cnpj'];
$dadosboleto["endereco"] = $rowempresa['linhacab'];
$dadosboleto["cidade_uf"] = "";
$dadosboleto["cedente"] = retira_acentos_UTF8($rowempresa['razao']);


	if($bancoEmissor=='Sicoob') {
	
		// NÃO ALTERAR!
		if($x==$MesIni) {
			include("include/funcoes_bancoob.php");
			header ('Content-type: text/html; charset=ISO-8859-1');
		}
		
		include("include/layout_bancoob.php");
		
		 
	}


	if($bancoEmissor=='Bradesco') {
	
		// N�O ALTERAR!
		if($x==$MesIni) {
			include("include/funcoes_bradesco.php");
			header ('Content-type: text/html; charset=ISO-8859-1');
		}
		include("include/layout_bradesco.php");
	
	}
	

	if($carteiracobranca == 'Com Registro') {

		$datageracao = date('Y-m-d H:i:s');
	
		// Insert em carne_remessabanco
	   	$queryRemessa= "insert into carne_remessabanco (data,unidade,usuario) values ('".$datageracao."',".$_SESSION['s_local'].",".$_SESSION['s_uid'].")";
		$resulConfig = mysqli_query($conec->con,$queryRemessa) or die('ERRO NA QUERY !'.$queryRemessa);
		$remessa = $numero_documento;
		
		include("../geral/remessabanco.php");
	}
	
 	$contador++;
 
 }
	

}

?>