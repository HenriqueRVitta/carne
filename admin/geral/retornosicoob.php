<?php
/*      Copyright 2015 MCJ Assessoria Hospitalar e Informática LTDA

        Desenvolvedor: Carlos Henrique R Vitta
		Data: 27/11/2017 15:17 

		* Módulo Carnê *

		Processa Retorno Bancario

*/

session_start();

	// 1 - Siccob
	// 2 - Bradesco
	
	$Banco = $_POST['banco'];
	
       	
// Definições da barra de progresso
//==============================================================
define("_JPGRAPH_PATH", '../../includes/mpdf54/'); // must define this before including mpdf.php file
$JpgUseSVGFormat = true;

define('_MPDF_URI','../../includes/mpdf54/'); 	// must be  a relative or absolute URI - not a file system path
//==============================================================

include("../../includes/mpdf54/mpdf.php");	
include ("../../includes/include_geral.inc.php");

include ("../../includes/include_geral_II.inc.php");

$lcNomeArquivo = "";


$query = "SELECT idretornobanco,localpagto FROM carne_bancos where id ='".$Banco."'";
$resultado = mysql_query($query) or die('ERRO NA EXECUÇÂO DA QUERY DE MAX ID!');
// 1 = ID Retorno Codigo/carne_titular.ID
// 2 = Nro Contrato carne_titular.nrocarne
$retornobanco = mysql_fetch_array($resultado);
$idretornobanco = $retornobanco['idretornobanco'];
$localpagto = $retornobanco['localpagto'];


// Pasta onde o arquivo vai ser salvo
$_UP['pasta'] = '../uploads/';

// Tamanho máximo do arquivo (em Bytes)
$_UP['tamanho'] = 1024 * 1024 * 2; // 2Mb

// Array com as extensões permitidas
$_UP['extensoes'] = array('txt', 'ret');

// Renomeia o arquivo? (Se true, o arquivo será salvo como .jpg e um nome único)
$_UP['renomeia'] = false;

// Array com os tipos de erros de upload do PHP
$_UP['erros'][0] = 'Não houve erro';
$_UP['erros'][1] = 'O arquivo no upload é maior do que o limite do PHP';
$_UP['erros'][2] = 'O arquivo ultrapassa o limite de tamanho especifiado no HTML';
$_UP['erros'][3] = 'O upload do arquivo foi feito parcialmente';
$_UP['erros'][4] = 'Não foi feito o upload do arquivo';

// Verifica se houve algum erro com o upload. Se sim, exibe a mensagem do erro
if ($_FILES['arquivo']['error'] != 0) {
  die("Não foi possível fazer o upload, erro:" . $_UP['erros'][$_FILES['arquivo']['error']]);
  exit; // Para a execução do script
}

// Caso script chegue a esse ponto, não houve erro com o upload e o PHP pode continuar
// Faz a verificação da extensão do arquivo
$extensao = strtolower(end(explode('.', $_FILES['arquivo']['name'])));

if (array_search($extensao, $_UP['extensoes']) === false) {
  echo "Por favor, envie arquivos com as seguintes extensões: txt ou ret";
  exit;
}

// Faz a verificação do tamanho do arquivo
if ($_UP['tamanho'] < $_FILES['arquivo']['size']) {
  echo "O arquivo enviado é muito grande, envie arquivos de até 2Mb.";
  exit;
}

// O arquivo passou em todas as verificações, hora de tentar movê-lo para a pasta
// Primeiro verifica se deve trocar o nome do arquivo
if ($_UP['renomeia'] == true) {
  // Cria um nome baseado no UNIX TIMESTAMP atual e com extensão .txt
  $nome_final = md5(time()).'.txt';
} else {
  // Mantém o nome original do arquivo
  $nome_final = $_FILES['arquivo']['name'];
}
  
// Depois verifica se é possível mover o arquivo para a pasta escolhida
if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $_UP['pasta'] . $nome_final)) {
  // Upload efetuado com sucesso, exibe uma mensagem e um link para o arquivo

  // echo "Upload efetuado com sucesso!";
  // echo '<a href="' . $_UP['pasta'] . $nome_final . '">Clique aqui para acessar o arquivo</a>';


if (!empty($_FILES['arquivo']))
    {
		$lcNomeArquivo = $_FILES['arquivo']['name'];
    	$lcFile = $_UP['pasta'].$_FILES['arquivo']['name'];
    	$file    = fopen($lcFile, 'r');
    	$dataprocesso = '1900-01-01 00:00:00';
        while (!feof($file)){
            $linha = fgets($file); 
            
			$idcliente = 0;
            $databaixa = '1900-01-01 00:00:00';
            $valor = 0.00;
            $multa = 0.00;
            $desconto = 0.00;
            $mesano = 0000;
            $historico = 'ERRO CPF E/OU CNPJ';
            $nrotitulobanco = "";
            

            $Registro = trim(substr($linha, 0, 3));
            $linha = trim(substr($linha, 0, 400));
            
            $cpfcnpj = trim(substr($linha, 342, 14));
            if($cpfcnpj == '00000166298689'){
            	$x = 1;
            }
			/* Codigo do Movimento bancario
			Comando/Movimento:
			02 = Confirmação Entrada Título;
			05 = Liquidação Sem Registro: Identifica a liquidação de título da modalidade "SEM REGISTRO";
			06 = Liquidação Normal: Identificar a liquidação de título de modalidade "REGISTRADA", com exceção dos títulos que forem liquidados em cartório (Cód. de movimento 15=Liquidação em Cartório);
			09 = Baixa de Titulo: Identificar as baixas de títulos, com exceção da baixa realizada com o cód. de movimento 10 (Baixa - Pedido Beneficiário);
			10 = Baixa Solicitada (Baixa - Pedido Beneficiário): Identificar as baixas de títulos comandadas a pedido do Beneficiário;
			11 = Títulos em Ser: Identifica os títulos em carteira, que estiverem com a situação "em abarto" (vencidos e a vencer).
			14 = Alteração de Vencimento;
			15 = Liquidação em Cartório: Identifica as liquidações dos títulos ocorridas em cartórios de protesto;
			23 = Encaminhado a Protesto: Identifica o recebimento da instrução de protesto
			27 = Confirmação Alteração Dados.
			48 = Confirmação de instrução de transferência de carteira/modalidade de cobrança
			*/
            
            $codigomovimento = trim(substr($linha, 108, 2));
            $processa = false;
            
	        switch ($codigomovimento) {
		    case 02: // Entrada Confirmada (verificar motivo na posição 319 a 328 )
		        $processa = true;
		        break;
		    case 03: // Entrada Rejeitada ( verificar motivo na posição 319 a 328)
		        $processa = true;
		        break;
		    case 05:
		        $processa = true;
		        break;
		    case 06: // Liquidação normal (sem motivo)
		        $processa = true;
		        break;
		    case 15: // Liquidação em Cartório (sem motivo)
		        $processa = true;
		        break;
	        case 09: // Baixado Automat. via Arquivo (verificar motivo posição 319 a 328)
		        $processa = true;
		        break;
	        case 10: // Baixado conforme instruções da Agência(verificar motivo pos.319 a 328)
		        $processa = true;
		        break;
		}

            
            if($processa && $Registro=='102' || $Registro=='101') {
            	
            	$datapagto = '20'.trim(substr($linha, 114, 2)).'-'.trim(substr($linha, 112, 2)).'-'.trim(substr($linha, 110, 2)).' 00:00:00';
            	$valor = trim(substr($linha, 154, 11));
            	$nrotitulobanco = trim(substr($linha, 116, 10));
            	$cpfcnpj = trim(substr($linha, 342, 14));
            	$valor = number_format($valor, 2, '.', '') / 100;
            	
            	//$mes = date( 'm', strtotime( $datapagto ) );
            	//$ano = date( 'Y', strtotime( $datapagto ) );
            	
            	$mes = trim(substr($linha, 148, 2));
            	$ano = '20'.trim(substr($linha, 150, 2));
            	
            	$mesano = $ano.$mes;
            	
				// Apago no arquivo retorno todos os registro da data de pagamento
            	if($dataprocesso == '1900-01-01 00:00:00') {
            		$querydelete = "delete from retornobanco where datapagto='".$datapagto."'";
					$resultadoDel = mysql_query($querydelete) or die('Erro na Query :'.$querydelete);
					$dataprocesso = $datapagto;
            	}

				// 1 = ID Retorno Codigo/carne_titular.ID
            	// 2 = Nro Contrato carne_titular.nrocarne
				if($idretornobanco == 1) {

					// Pego aqui o ID do cliente conforme o CPF ou CNPJ informado no arquivo retorno
					$qryl = "SELECT id,nometitular,cpf FROM carne_titular WHERE id='".$nrotitulobanco."'";
										
				} else {

					// Pego aqui o ID do cliente conforme o CPF ou CNPJ informado no arquivo retorno
					$qryl = "SELECT id,nometitular,cpf FROM carne_titular WHERE cpf=".$cpfcnpj;
					
					
				}
            	
				$resultado = mysql_query($qryl) or die('Erro na Query :'.$qryl);
				$linhas = mysql_num_rows($resultado);

				if ($linhas > 0)
				{
					while ($row = mysql_fetch_array($resultado)) {
						
					$idcliente = $row['id']; 
						
						// Verifico se ja foi registrado o pagamento
						$query = "SELECT databaixa FROM carne_pagamentos WHERE idcliente='".$idcliente."' and databaixa ='".$datapagto."' and localpagto = ".$localpagto." and nrotitulobanco = '".$nrotitulobanco."'";
						$pagtos = mysql_query($query) or die('Erro na Query :'.$query);
						$registro = mysql_num_rows($pagtos);
						
						IF($registro>0) {
							
							$historico = 'PAGTO JA REGISTRADO';

						} else {
							
							$historico = 'PAGTO REGISTRADO';
							
							$insert = "insert into carne_pagamentos (idcliente,mesano,databaixa,localpagto,vlrcalculado,vlrpago,taxa,unidade,usuario,nrotitulobanco) ".
							"values (".$idcliente.",'".$mesano."','".$datapagto."',".$localpagto.",".$valor.",".$valor.",".$multa.",".$_SESSION['s_local'].",".$_SESSION['s_uid'].",'".$nrotitulobanco."')";
							$registrapagtos = mysql_query($insert) or die('Erro na Query :'.$insert);
							
							$nfse = 0;
							if(!empty($_SESSION['nfseprefeitura'])){
								$nfse = 1;
							}
							
							// Gravo no arquivo de Retorno do Banco
							$insertretorno = "insert into retornobanco (idcliente,cpfcnpj,datapagto,valor,multa,desconto,historico,dataprocessamento,usuario,nrotitulobanco,nfse) "." values (".$idcliente.",'".$cpfcnpj."','".$datapagto."',".$valor.",0,0,'".$historico."','".date('Y-m-d H:i:s')."',".$_SESSION['s_uid'].",'".$nrotitulobanco."',".$nfse.")";
							$registrapagtos = mysql_query($insertretorno) or die('Erro na Query :'.$insertretorno);
							
						}
						
						
					}					

				} else {
					
					// Quando nao localiza o CPF ou CNPJ informado no arquivo retorno
					$insertretorno = "insert into retornobanco (idcliente,cpfcnpj,datapagto,valor,multa,desconto,historico,dataprocessamento,usuario,nrotitulobanco) ".
					"values (0,'".$cpfcnpj."','".$datapagto."',".$valor.",0,0,'".$historico."','".date('Y-m-d H:i:s')."',".$_SESSION['s_uid'].",'".$nrotitulobanco."')";
					$registrapagtos = mysql_query($insertretorno) or die('Erro na Query :'.$insertretorno);
					
				}
            	
            }
            
            $itens = array(trim(substr($linha, 0, 60)), trim(substr($linha, 60, 70)));
            //Inserir($itens, $Pdo);
        }
    }

    
} else {
  // Não foi possível fazer o upload, provavelmente a pasta está incorreta
  echo "Não foi possível enviar o arquivo, tente novamente";
  exit;
  
}

	// 1 - Siccob
	
	$Banco = $_POST['banco'];
	
	$ArquivRetono= $_POST['file1'];
	
	$tiporel = "Analitico"; 


	// Inicio Dados Cabecalho	
	$lcBorda.="<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000000;'>
	<tr>
	<td align='right'>Data do Pagamento:</TD>
	<td align='left'>".date('d/m/Y', strtotime($datapagto))."</TD>
	<td align='right'>Data Emiss&atilde;o:</TD>
	<td align='left'>".date('d/m/Y H:i:s')."</TD>
	<td align='right'>Arquivo Retorno:</TD>
	<td align='left'>".$lcNomeArquivo."</TD>
	</tr><tr>";
	

	$sql="SELECT nome FROM usuarios where codigo = ".$_SESSION['s_coduser']." ";
	$commit = mysql_query($sql);
	$row = mysql_fetch_array($commit);
	
	$lcBorda.="<td align='right'>Usu&aacute;rio:</TD>
	<td align='left'>".retira_acentos_UTF8($row['nome'])."</TD>";

		$lcBorda.= "<td align='right'>Relat&oacute;rio:</TD>
		<td align='left'>".$tiporel."</TD>";

	$lcBorda.= "</tr>
	</table>";

	// Fim Dados Cabecalho
		
     
	// Cabeçalho do regisrtos encontrados
	$lcString.= "<table width='800' border='1' cellspacing='1' cellpadding='1' align='center'>
	<tr>
	<th scope='col' align='center'>Nome do Cliente</th>
	<th scope='col' align='center'>CPF/CNPJ</th>
	<th scope='col' align='center'>Nro do Cedente</th>
	<th scope='col' align='center'>Data Pagto</th>
	<th scope='col' align='center'>Valor Pago</th>
	<th scope='col' align='center'>Historico</th>
	<th scope='col' align='center'>Usuario</th>
	</tr>";


$query = "SELECT c.nometitular,r.cpfcnpj,r.nrotitulobanco,r.datapagto,r.valor,r.historico,r.dataprocessamento,u.nome
FROM retornobanco r left join carne_titular c on c.id = r.idcliente
left join usuarios u on u.codigo = r.usuario 
where r.datapagto = '".$datapagto."' order by historico,nometitular";

	
    $resultado = mysql_query($query) or die('ERRO NA QUERY !'.$query);
	$i=0;
	$lntotalpg = 0.00;
	
	while($row = mysql_fetch_array($resultado)){
		
		$dtpagto = str_replace('/','',substr(converte_datacomhora($row['r.datapagto']),0,10));

		//		<td align='left'>".retira_acentos_UTF8($row['descricao'])."</TD>
		
		$lcString.= "<tr>
		<td align='left'>".retira_acentos_UTF8($row['nometitular'])."</TD>
		<td align='center'>".$row['cpfcnpj']."</TD>
		<td align='center'>".$row['nrotitulobanco']."</TD>
		<td align='center'>".date('d/m/Y', strtotime($row['datapagto']))."</TD>
		<td align='right'>".$row['valor']."</TD>
		<td align='center'>".$row['historico']."</TD>
		<td align='center'>".$row['nome']."</TD>
		</tr>";
		
		$lntotalpg+=$row['valor'];
		
		$i++;
		
	}
	
	$lcString.= "</table>";
	
	//<p>&nbsp;</p>";
	
	// Resumo
	$lcString.= "<table width='100%' border='0'>
  	<tr>
    <th align='center'>RESUMO</th>
    </tr>
  	<tr>
    <td align='left'>Total Geral</td>
    <td align='right'>".number_format($lntotalpg,2,",",".")."</td>    
    </tr>
  	<tr>
    <td align='left'>Total Registros listados</td>
    <td align='right'>".$i."</td>    
    </tr>
	</table>
    </table>";

	
	$query = "select historico,count(*) as qtde,sum(valor) as total from retornobanco where datapagto = '".$datapagto."' group by historico";
    $resultado = mysql_query($query) or die('ERRO NA QUERY !'.$query);

    $lcString.= "<br><table width='100%' border='0'>
  	<tr>
    <th align='center'>QUANTITATIVO DO HIST&Oacute;RICO</th>
    </tr>
  	<tr>";
    
    while($row = mysql_fetch_array($resultado)){
    $lcString.="<td align='right'>".$row['historico']."</td>
    <td align='right'> Qtde: ".$row['qtde']."</td>
    <td align='right'> Total: ".$row['total']."</td><tr>";
	}
	
	
    $lcString.="</tr>
    </table>";
	
//$mpdf=new mPDF('en-x','A4','','',32,25,47,47,10,10); 
$mpdf=new mPDF('en-x','A4','','',12,12,40,45,1,5);

$mpdf->mirrorMargins = 1;	// Use different Odd/Even headers and footers and mirror margins
$mpdf->useSubstitutions = false;

$date = date("d/m/Y g:i a");


$header = "<table width='100%' style='border-bottom: 1px solid #000000; vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000088;'><tr>
<td width='33%'>".$date."</span></td>
<td width='33%' align='center'><img src='../../logo.png' width='126px' /></td>
<td width='33%' style='text-align: right;'><span style='font-weight: bold;'>Pag. <span style='font-size:11pt;'>{PAGENO}</span></td>
</tr>
</table>
<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 14pt; color: #000000;'><tr>
<td width='33%' align='center'>Relat&oacute;rio Recebimentos Banc&aacute;rios - Arquivo Retorno</td>
</tr>
</table>".$lcBorda."";

$headerE = "<table width='100%' style='border-bottom: 1px solid #000000; vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000088;'><tr>
<td width='33%'>".$date."</span></td>
<td width='33%' align='center'><img src='../../logo.png' width='126px' /></td>
<td width='33%' style='text-align: right;'><span style='font-weight: bold;'>Pag. <span style='font-size:11pt;'>{PAGENO}</span></td>
</tr>
</table>
<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 14pt; color: #000000;'><tr>
<td width='33%' align='center'>Relat&oacute;rio Recebimentos Banc&aacute;rios - Arquivo Retorno</td>
</tr>
</table>".$lcBorda."";


$footer = "<table width='100%' style='border-top: 1px solid #000000; vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000000;'><tr>
<td width='33%' align='center'>
<div align='center'><span style='font-size:9pt;'>MCJ - Assessoria Hosp. & Inf. LTDA  Rua da Bahia, 570 - Conj. 902 - Centro - 30.160-010  Belo Horizonte-MG  Fone (31)3214-0600</a></span></div>
</td>
</table>";


$footerE = "<table width='100%' style='border-top: 1px solid #000000; vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000000;'><tr>
<td width='33%' align='center'>
<div align='center'><span style='font-size:9pt;'>MCJ - Assessoria Hosp. & Inf. LTDA  Rua da Bahia, 570 - Conj. 902 - Centro - 30.160-010  Belo Horizonte-MG  Fone (31)3214-0600</a></span></div>
</td>
</table>";

//echo $header.$lcString.$footer;


$mpdf->StartProgressBarOutput();
$mpdf->mirrorMargins = 1;
$mpdf->SetDisplayMode('fullpage','two');
$mpdf->useGraphs = true;
$mpdf->list_number_suffix = ')';
$mpdf->hyphenate = true;
$mpdf->debug  = true;

$mpdf->SetHTMLHeader($header);
$mpdf->SetHTMLHeader($headerE,'E');
$mpdf->SetHTMLFooter($footer);
$mpdf->SetHTMLFooter($footerE,'E');

/*
$html = '
<h1>mPDF</h1>
<h2>Headers & Footers Method 2</h2>
<h3>Odd / Right page</h3>
<p>Nulla felis erat, imperdiet eu, ullamcorper non, nonummy quis, elit. Suspendisse potenti. Ut a eros at ligula vehicula pretium. Maecenas feugiat pede vel risus. Nulla et lectus. Fusce eleifend neque sit amet erat. Integer consectetuer nulla non orci. Morbi feugiat pulvinar dolor. Cras odio. Donec mattis, nisi id euismod auctor, neque metus pellentesque risus, at eleifend lacus sapien et risus. Phasellus metus. Phasellus feugiat, lectus ac aliquam molestie, leo lacus tincidunt turpis, vel aliquam quam odio et sapien. Mauris ante pede, auctor ac, suscipit quis, malesuada sed, nulla. Integer sit amet odio sit amet lectus luctus euismod. Donec et nulla. Sed quis orci. </p>
<pagebreak />
<h3>Even / Left page</h3>
<p>Nulla felis erat, imperdiet eu, ullamcorper non, nonummy quis, elit. Suspendisse potenti. Ut a eros at ligula vehicula pretium. Maecenas feugiat pede vel risus. Nulla et lectus. Fusce eleifend neque sit amet erat. Integer consectetuer nulla non orci. Morbi feugiat pulvinar dolor. Cras odio. Donec mattis, nisi id euismod auctor, neque metus pellentesque risus, at eleifend lacus sapien et risus. Phasellus metus. Phasellus feugiat, lectus ac aliquam molestie, leo lacus tincidunt turpis, vel aliquam quam odio et sapien. Mauris ante pede, auctor ac, suscipit quis, malesuada sed, nulla. Integer sit amet odio sit amet lectus luctus euismod. Donec et nulla. Sed quis orci. </p>
';
*/

$mpdf->WriteHTML($lcString);

$mpdf->Output();
exit;

    
?>
