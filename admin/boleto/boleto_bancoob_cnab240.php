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

    // VALIDADOR SICCOB
    // https://www.sicoob.com.br/web/sicoob/validador-cnab
    // https://www.sicoobsc.com.br/transcredi/para-sua-empresa/para-sua-empresa/cobranca-2/
    // http://sicoobcredicampo.hospedagemdesites.ws/pga.php?pg=cobranca-bancaria-sistema-proprio
    
    // API Open Source
    // https://api.boletosimples.com.br/bank_contracts/sicoob/

	session_start();

    // DEFINE O FUSO HORARIO COMO O HORARIO DE BRASILIA
    date_default_timezone_set('America/Sao_Paulo');

	include ("../../includes/include_geral_III.php");
	include ("../../includes/classes/paging.class.php");

	$conec = new conexao;
	$conec->conecta('MYSQL');

    $numero_lote = [];
    
    $datageracao = date('Y-m-d H:i:s');
            
	$dtinicial = Fdate($_POST['inicio']);
	$dtfinal = Fdate($_POST['fim']);
    
	$bancoEmissor = $_POST['bancoemissor'];
    $layoutimpressao = $_POST['layoutimpressao'];

    $data_inicial = fdate($_POST['inicio']);

	$MesIni = date( 'm', strtotime($dtinicial));
	$MesFim = date( 'm', strtotime($dtfinal));
	$MesFimPos = date( 'm', strtotime($dtfinal));
	$AnoIni = date( 'Y', strtotime($dtinicial));
	$AnoFim = date( 'Y', strtotime($dtfinal));
	$DiaIni = date( 'd', strtotime($dtfinal));
	$MesFim_ = $MesFim;
    $MesIni_ = $MesIni;

	$outroano = false;
    $totaltaxasparcitular = 0;
    $nomeNoBoleto = '';

    if($AnoIni <> $AnoFim && $MesFim <= $MesIni) {
		$MesFim = 12;
		$outroano = true;
	}

	//$TotalParcelas = date( 'm', strtotime($dtfinal)) - date( 'm', strtotime($dtinicial));
    $date = new DateTime($dtinicial); // Data de Nascimento
    $TotalPar = $date->diff(new DateTime($dtfinal)); // Data do Acompanhamento
    $TotalPar_mostra_anos = $TotalPar->format('%Y')*12;
    $TotalPar_mostra_meses = $TotalPar->format('%m');
    $TotalParcelas = $TotalPar_mostra_anos+$TotalPar_mostra_meses + 1;

    // Dados da Empresa
	$qrylocal = "SELECT * from cadastro_unidades where codigo=".$_SESSION['s_local']."";
	$exelocal = mysqli_query($conec->con,$qrylocal) or die('Erro na query: ' .$qrylocal. mysqli_error($conec->con));
	$rowempresa = mysqli_fetch_array($exelocal);

	// Dados do Banco
   	$queryConfig = "SELECT id, nome, bancoemissor, nroagencia, digitoagencia, nroconta, digitoconta, nrocontrato, infocliente1, infocliente2, infocliente3, instrucaocaixa1, instrucaocaixa2, instrucaocaixa3, dirarquivoremessa, carteiracobranca, idretornobanco, codcedente FROM carne_bancos where nome = '".$bancoEmissor."'";
	$resulConfig = mysqli_query($conec->con,$queryConfig) or die('ERRO NA QUERY !'.$queryConfig);
	$rowconfig = mysqli_fetch_array($resulConfig);
	

    // Insert em carne_lote
    $queryRemessa= "insert into carne_lote (data,banco,unidade,usuario) values ('".$datageracao."','".$bancoEmissor."',".$_SESSION['s_local'].",".$_SESSION['s_uid'].")";
    $resulConfig = mysqli_query($conec->con,$queryRemessa) or die('ERRO NA QUERY !'.$queryRemessa);

       //numero do lote
	   $qryNroDoc = "SELECT max(id) as proximo FROM carne_lote";
	   $exeNroDoc = mysqli_query($conec->con,$qryNroDoc) or die('Erro na query: ' .$qryNroDoc. mysqli_error($conec->con));
	   $rownrodoc = mysqli_fetch_array($exeNroDoc);
	   $numero_lote = $rownrodoc['proximo'];
       $lotedaremessa = $rownrodoc['proximo'];

       
// Array dos Contribuintes Selecionados em geraremessabanco.php
$arr = $_POST['selecionado'];
$dadosRemessa = array();
$controleheader = 0;

foreach ($arr as &$value) {
   
    if($outroano == false) {
        if($AnoIni <> $AnoFim){
            $outroano = true;
        }
    }

    $IdCliente=trim($value);
    $proximoAno = false;

	$MesIni = date( 'm', strtotime($dtinicial));
	$MesFim = date( 'm', strtotime($dtfinal));

	if($AnoIni <> $AnoFim && $MesFim_ <= $MesIni_) {
        $MesIni = date( 'm', strtotime($dtinicial));
        if($outroano == true){
            $MesFim = 12;
        } else {
            $MesFim = date( 'm', strtotime($dtfinal));
        }
    }

	// Qtde de Dependentes
	
   	$queryCliente = "SELECT count(*) as qtde_dep FROM carne_dependente where idtitular = '".$IdCliente."' and cobrarnoboleto = 1";
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
	$queryTaxas = "SELECT sum(aeromedico+comissao+coopart+taxabanco+apene) as totaltaxas, nomeboleto FROM carne_taxastitular where idtitular = '".$IdCliente."'";
	$resulTaxas = mysqli_query($conec->con,$queryTaxas) or die('ERRO NA QUERY !'.$queryTaxas);
	if (mysqli_num_rows($resulTaxas) > 0) {
		$rowtaxas = mysqli_fetch_array($resulTaxas);
		$totaltaxasparcitular = $rowtaxas['totaltaxas'];
		$nomeNoBoleto = $rowtaxas['nomeboleto'];
	}

    
	// Dados do Cliente
   	$queryCliente = "SELECT a.id,a.nometitular,a.endereco,a.numero,a.cep,a.bairro,a.cidade,a.uf,a.cpf,b.nrocontrato,b.diavencto,b.datacontrato,c.descricao,d.valor,d.valor_dependente,a.valorplano, d.vlrfixonegociado, a.somenteresponsavel FROM carne_titular a".
   	" join carne_contratos b on b.idtitular = a.id".
   	" join carne_tipoplano c on c.id = b.plano".
   	" join carne_competenciaplano d on d.idplano = c.id".   	
   	" where a.id = '".$IdCliente."'";
	$resulCliente = mysqli_query($conec->con,$queryCliente) or die('ERRO NA QUERY !'.$queryCliente);

    $DataVencimento = explode("-",substr($_POST['inicio'],0,10));
    $DataVencimento = $DataVencimento[2]."/".$DataVencimento[1]."/".$DataVencimento[0];
    $somenteresponsavel = 0;
    $somames = 1;
    
    while($rowcliente = mysqli_fetch_array($resulCliente)) {

        $contador = 1;
        $qtdeInicio = $MesFim;
        $somenteresponsavel = $rowcliente['somenteresponsavel'];
        $diaVencto = $rowcliente['diavencto'];
        $diaDataInicio = date('d',strtotime($data_inicial));
        $venctoContribuinte = explode("-",substr($data_inicial,0,10));
        $venctoContribuinte[2] = $diaVencto;
        $anoPosterior = date('Y', strtotime('+1 year')).'-01-'.$venctoContribuinte[2];
        $DataVencimento = $venctoContribuinte[2]."/".$venctoContribuinte[1]."/".$venctoContribuinte[0];

        $venctoContribuinte = implode("-",$venctoContribuinte);

        $proxano = date('Y', strtotime("+1 year"));
        $proxmes = date('m', strtotime($dtfinal));
        $venctoContrato = $proxano.'-'.$proxmes.'-'.$diaVencto.' 00:00:00';
        $ultimomescarne = $proxano.$proxmes;
        $contadorpaginascarne = 1;

        For ($x=$MesIni; $x<=$MesFim; $x++) {

            $qtdeFim = $x;

            if($proximoAno == true) {
                  $DataVencimento = date('d/m/Y', strtotime("+".$somames." month", strtotime($anoPosterior)));
                  $venctoContribuinte = $anoPosterior;
                  $somames++;
            };

            if($x>$MesIni && $proximoAno == false) {
            // Alterado parra pegar o dia de vencto do contribuinte
            //$DataVencimento = date('d/m/Y', strtotime("+".$somames." month", strtotime($data_inicial)));
            $DataVencimento = date('d/m/Y', strtotime("+".$somames." month", strtotime($venctoContribuinte)));
            $somames++;
            }

            $numero_documento = $rowcliente['id'];
        
            $dirarquivoremessa = $rowconfig['dirarquivoremessa']; 
            $carteiracobranca = $rowconfig['carteiracobranca'];
                
            
            // DADOS DO BOLETO PARA O SEU CLIENTE
            $dias_de_prazo_para_pagamento = 7;
            $taxa_boleto = 0;
            $data_venc = $DataVencimento; //"14/05/2013";//date("d/m/Y", time() + ($dias_de_prazo_para_pagamento * 86400));  // Prazo de X dias OU informe data: "13/04/2006"; 

            $valor_cobrado = $rowcliente['valor']; // "1,00"; // Valor - REGRA: Sem pontos na milhar e tanto faz com "." ou "," ou com 1 ou 2 ou sem casa decimal

            // Soma o valor dos dependentes com o Titular
            if($nQtdeDep > 0) {
                $valor_cobrado = $rowcliente['valor'] + ($rowcliente['valor_dependente'] * ($nQtdeDep - $somenteresponsavel));
            }

            // Valor Negociado com o Cliente
            if($rowcliente['vlrfixonegociado'] == 2 && $rowcliente['valorplano'] > 0){
                $valor_cobrado = $rowcliente['valorplano'];
            }

                    
            $valor_cobrado = str_replace(",", ".",$valor_cobrado);
            $valor_boleto=number_format($valor_cobrado+$taxa_boleto, 2, ',', '');


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
            $contacomdigito = $conta.$conta_dv;
            $codcedente = $rowconfig['codcedente'];
            $convenio = $codcedente;

            $qtde_nosso_numero = strlen($NossoNumero);
            $sequencia = formata_numdoc($agencia,4).formata_numdoc(str_replace("-","",$codcedente),10).formata_numdoc($NossoNumero,7);
            $cont=0;
            $calculoDv=0;
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
            /*if ($Dv == 0) $Dv = 0;
            if ($Dv == 1) $Dv = 0;
            if ($Dv > 9) $Dv = 0;
            */
            if ($Resto == 0 || $Resto == 1) {
                $Dv = 0;
            } else {
                $Dv = 11 - $Resto;
            }

            $dadosboleto["nosso_numero"] = $NossoNumero . $Dv;
            $dadosboleto["dac_nosso_numero"] = $Dv;
            $dadosboleto["nro_lote"] = $lotedaremessa;

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
                        
            $dadosboleto["endremessa"] = retira_acentos_ISO($rowcliente['endereco'])." ".$rowcliente['numero'];
            $dadosboleto["bairemessa"] = retira_acentos_ISO($rowcliente['bairro']);
            $dadosboleto["cidremessa"] = retira_acentos_ISO($rowcliente['cidade']);
            $dadosboleto["ufremessa"] = $rowcliente['uf'];
            $dadosboleto["cepremessa"] = $rowcliente['cep'];

            if($rowconfig["idretornobanco"] == 1) {
                $dadosboleto["titulo_empresa"] = $rowcliente['id'];
            } else {
                $dadosboleto["titulo_empresa"] = trim($dadosboleto["cpf"]);
            }

            // INFORMACOES PARA O CLIENTE
            $dadosboleto["demonstrativo1"] = $rowconfig['infocliente1']; //Pagamento de Compra na Loja Nonononono";
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
            $dadosboleto["especie_doc"] = "DS"; // Duplicata Servicos


            // ---------------------- DADOS FIXOS DE CONFIGURAÇÃO DO SEU BOLETO --------------- //
            // DADOS ESPECIFICOS DO SICOOB
            $dadosboleto["modalidade_cobranca"] = "01"; // 02
            $dadosboleto["numero_parcela"] = "001"; // 901


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

            //layoutimpressao

            if($bancoEmissor=='Sicoob') {
            
                    // NÃO ALTERAR!
                    if($x==$MesIni && $controleheader == 0) {
                        include("include/funcoes_bancoob.php");
                        if($layoutimpressao == 'Boleto') {
                            header ('Content-type: text/html; charset=ISO-8859-1');
                        }
                    }

                    if($layoutimpressao == 'Carne') {
                        include("../carnebancario/carne.php");
                        $contadorpaginascarne++;
                    } else {
                        include("include/layout_bancoob.php");
                    }
                    
            }

            $contador++;
            $controleheader++;

            array_push($dadosRemessa, $dadosboleto);

            if($outroano == true) {
                if($x==$MesFim){
                    $MesIni = 01;
                    $x = 0;
                    $MesFim = date( 'm', strtotime($dtfinal) );
                    $outroano = false;
                    $proximoAno = true;
                    $somames = 0;
                }
            }

        }

        
        // Atualizando a Data de Vencto do Contrato
        $queryCont = "UPDATE carne_contratos set datacontrato = '".$venctoContrato."' Where idtitular = ".$rowcliente['id']." and status = 0";
        $resulCont = mysqli_query($conec->con,$queryCont) or die('Erro no Update '.$queryCont);
        if ($resulCont == 0) {
            $aviso = TRANS('ERR_INSERT');
        }	
                
        // Atualizando a Data de Vencto do Contrato
        $queryCont = "UPDATE carne_titular set ultimomescarne = '".$ultimomescarne."' Where id = ".$rowcliente['id'];
        $resulCont = mysqli_query($conec->con,$queryCont) or die('Erro no Update '.$queryCont);
        if ($resulCont == 0) {
            $aviso = TRANS('ERR_INSERT');
        }	
        

    }

}


foreach($dadosRemessa as $dadosboleto){
    include("include/sqlbancoob.php"); 

    $Valorcarne = number_format($dadosboleto["valor_boleto"],2,'.','');

			// Henrique 24/10/2019 13:23 GLPI 16571
			// Inserindo na Tabela carne_carnesgerados
			// Os Carnes que estao sendo gerados para o Contribuinte
            if($layoutimpressao == 'Carne') {
			$query = "INSERT INTO carne_carnesgerados (idtitular,datainicio,datafim,usuario,datagerou,valor)".
					" values ('".$dadosboleto["numero_documento"]."','".$dtinicial."','".$dtfinal."',".$_SESSION['s_uid'].",'".$datageracao."',".$Valorcarne.")";
			$resultado = mysqli_query($conec->con,$query) or die('Erro no Insert '.$query);
			}

}


            if($carteiracobranca == 'Com Registro') {
                
                if(isset($_POST["remessa"])) {
                    include("cnab240/remessa_sicoob.php");
                }
                

            }

?>