<?php
//REALIZANDO TESTES
	
if($contador==1){
	include 'src/sicoob/Arquivo.php';
}

	
//configurando o arquivo de remessa
$config['codigo_empresa'] = $convenio; //'1054508';
$config['razao_social'] = substr($dadosboleto["cedente"],0,29); //'Agnetech Soluções empresariais';
$config['numero_remessa'] = $remessa; //'2165';
$config['data_gravacao'] = date('d').date('m').date('y'); //'280815';

$arquivo = new Arquivo();
//configurando remessa
$arquivo->config($config);

//foreach ($dadosboleto as $value) {

	$ex = explode("/", $data_venc);
	$dia = $ex[0];
	$mes = $ex[1];
	$ano = $ex[2];
	$Vencto = $dia.$mes.substr($ano,2,2);
	$dtemissao = date('d').date('m').date('y');

	$valor_boleto=str_replace(".","",$valor_boleto);
	$valor_boleto=str_replace(",","",$valor_boleto);
	
	$cep = $rowcliente['cep'];
	$sufixoCep = substr($rowcliente['cep'],5,3);

	//agencia é 4 digitos
	$agencia = formata_numero($dadosboleto["agencia"],4,0);
	//conta é 6 digitos
	$conta = formata_numero($dadosboleto["conta"],6,0);
	//dv da conta
	$conta_dv = formata_numero($dadosboleto["conta_dv"],1,0);

	//adicionando boleto
	$boleto['agencia'] 						= $agencia;
	$boleto['agencia_dv'] 					= $dadosboleto["agencia_dv"];
	$boleto['razao_conta_corrente']			= '07050';
	$boleto['conta'] 						= $conta;
	$boleto['conta_dv'] 					= $conta_dv;
	$boleto['carteira'] 					= '01'; //  Carteira simples (1) com registro (Padrão)
	$boleto['numero_controle'] 				= $rowcliente['id'];
	$boleto['habilitar_debito_compensacao'] = false; // true para sair 237(bradesco) na poseicao 063 a 065, caso o cliente Beneficiário tenha optado pelo débito automático em Conta do Pagador
	$boleto['habilitar_multa'] 				= false; // true para Identificativos de Multa
	$boleto['percentual_multa'] 			= '0';
	$boleto['nosso_numero'] 				= $NossoNumero; //'61551964';
	$boleto['nosso_numero_dv'] 				= $Dv; //'P';
	$boleto['desconto_dia']	 				= '0';
	$boleto['rateio'] 						= false;
	$boleto['numero_documento'] 			= $rowcliente['id'];
	$boleto['vencimento'] 					= $Vencto;
	$boleto['valor'] 						= $valor_boleto;
	$boleto['data_emissao_titulo'] 			= $dtemissao;
	$boleto['valor_dia_atraso'] 			= '0';
	$boleto['data_limite_desconto'] 		= $dtemissao;
	$boleto['valor_desconto'] 				= '0';
	$boleto['valor_iof'] 					= '0';
	$boleto['valor_abatimento_concedido'] 	= '0';
	$boleto['tipo_inscricao_pagador'] 		= 'CPF';
	$boleto['numero_inscricao'] 			= $dadosboleto["cpf"];
	$boleto['nome_pagador'] 				= $dadosboleto["sacado"];
	$boleto['endereco_pagador'] 			= $dadosboleto["endereco1"];
	$boleto['primeira_mensagem'] 			= '';
	$boleto["bairro_pagador"] 				= $dadosboleto['bairro'];
	$boleto['cep_pagador'] 					= $cep;
	$boleto["cidade_pagador"]				= $dadosboleto['cidade'];
	$boleto["uf_pagador"] 					= $dadosboleto['uf'];
	$boleto['sufixo_cep_pagador'] 			= $sufixoCep;
	$boleto['sacador_segunda_mensagem'] 	= '';
			
	//adicionando boleto
	$arquivo->add_boleto($boleto);


//}
	

// Comentado aqui para Gravar o o arquivo de remessa no carne_bancoob.php
// evitando assim gravar um arquivo para cada vencto de carne
/*	
for ($i = 1; $i < 99; $i++) {
	$seq = str_pad($i, 2, '0', STR_PAD_LEFT);
	$arquivogravacao = $dirarquivoremessa.'/'.'CB'.date('d').date('m').$seq;
	if(!file_exists($arquivogravacao.".REM")) {
		break;
	}
}

$arquivo->setFilename($arquivogravacao);
$arquivo->save();
*/

$arquivogravacao = "ultimaremessagerada.rem";
$arquivo->setFilename($arquivogravacao);
$arquivo->save();
	
	

