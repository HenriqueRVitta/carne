<?php
/*      Copyright 2015 MCJ Assessoria Hospitalar e Inform�tica LTDA

        Desenvolvedor: Carlos Henrique R Vitta
		Data: 03/02/2015 13:00

		* M�dulo Carn� *

		Relat�rio dos pagamentos registrados

*/

	session_start();

// Defini��es da barra de progresso
//==============================================================
define("_JPGRAPH_PATH", '../../includes/mpdf54/'); // must define this before including mpdf.php file
$JpgUseSVGFormat = true;

define('_MPDF_URI','../../includes/mpdf54/'); 	// must be  a relative or absolute URI - not a file system path
//==============================================================
	
	include("../../includes/mpdf54/mpdf.php");	
	include ("../../includes/include_geral_III.php");

	$conec = new conexao;
	$conec->conecta('MYSQL');
	
	$dtinicial = Fdate($_POST['datainicio']);
	$dtfinal = Fdate($_POST['datafim']);
	$localpagto = $_POST['localpagto'];
	$pcwhere	= "";

		if($localpagto<> -1 ) {
			$pcwhere.=" and k.localpagto =".$localpagto;
		}
		

	// Inicio Dados Cabecalho	
	$lcBorda.="<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000000;'>
	<tr>
	<td align='right'>Data Inicial:</TD>
	<td align='left'>".$_POST['datainicio']."</TD>
	<td align='right'>Data Final:</TD>
	<td align='left'>".$_POST['datafim']."</TD>";

	
	$lcBorda.="</tr>
	<tr>";
	
	
	if(isset($_POST['localpagto'])) {

		$sql="SELECT descricao FROM carne_localpagto where id = ".$_POST['localpagto']." ";
		$commit = mysqli_query($conec->con,$sql);
		$i=0;
			while($row = mysqli_fetch_array($commit)){

				$lcBorda.= "<td align='right'>Local Pagamento:</TD>
				<td align='left'>".$row['descricao']."</TD>";
				
				$i++;
			}
		
	}
	
	$lcBorda.= "</tr>
	</table>";

	
	if($_POST['separacao'] <> -1 ) {

	$nsepara = $_POST['separacao'];
	$lcBorda.= "<table border='0' cellspacing='2' cellpadding='2'>";
	
		switch ( $nsepara ){
		  case 1:
			$lcBorda.= "<td align='right'>Separa&ccedil;&atilde;o:</TD>
			<td align='left'>Cidade</TD>";
			break;
		  case 2:
			$lcBorda.= "<td align='right'>Separa&ccedil;&atilde;o:</TD>
			<td align='left'>M&ecirc;s/Ano</TD>";
		  	break;
		  case 3:
			$lcBorda.= "<td align='right'>Separa&ccedil;&atilde;o:</TD>
			<td align='left'>Usu&aacute;rio</TD>";
		  	break;
		  case 4:
			$lcBorda.= "<td align='right'>Separa&ccedil;&atilde;o:</TD>
			<td align='left'>Local Pagto</TD>";
		  	break;
		  default:
			$lcBorda.= "<td align='right'>Separa&ccedil;&atilde;o:</TD>
			<td align='left'>Cidade</TD>";
		}

		$lcBorda.= "</table>";
			
	}
	// Fim Dados Cabecalho

		$pcwhere	= "";
	
		if($_POST['localpagto'] <> -1) {
			$pcwhere = " and k.localpagto = ".$_POST['localpagto']."";
		}
	

		$pcordem	= " order by l.descricao";
		
		$pcgroup	= " group by l.descricao";
		$lcColumn	= " l.descricao";
	
       $query = "SELECT".$lcColumn." as filtro, count(*) as qtde, sum(vlrpago) as total FROM carne_titular c
		left Join carne_contratos p on p.idtitular = c.id
		left Join carne_tipoplano q on q.id = p.plano
		left Join carne_competenciaplano d on d.idplano = p.plano
		left Join carne_pagamentos k on k.idcliente = c.id
		left Join carne_localpagto l on l.id = k.localpagto
		left Join usuarios u on u.codigo = k.usuario
		Where p.status = 0 and k.databaixa between '".$dtinicial."' and '".$dtfinal."' ".$pcwhere." ".$pcgroup." ".$pcordem;
               
      //print_r($query);
      //break;
      
	// Cabe�alho do regisrtos encontrados
	$lcString.= "<table width='800' border='1' cellspacing='1' cellpadding='1'>
	<tr>
	<th scope='col' align='center'>Local de Pagamento</th>
	<th scope='col' align='center'>Qtde</th>
	<th scope='col' align='center'>Vlr Total</th>
	</tr>";
       
    $resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
	$i=0;
	$lntotalpg = 0.00;
	$lnqtde = 0.00;
	$dtpagto = str_replace('-', '', substr($dtinicial,0,10));

	$sequencia = 1;
	
		if($_POST['localpagto'] <> -1) {
			$sequencia = $_POST['localpagto'];
		}
	
			
	while($row = mysqli_fetch_array($resultado)) {
		

			// Se Selecionado Exportar para o Financeiro e Filtrado um Local de Pagamento	
			if($_POST['processarpara'] == 2 && $_POST['localpagto'] <> -1) {
				
				// Alterado em 01/02/2016 para gravar depois de apagar com delete from
				$queryFin = "delete from financeiro.contasreceber where nrodoc ='".$dtpagto.$sequencia."' and unidade=".$_SESSION['s_local']." and dtbaixa = '1900-01-01 00:00:00'";
				$resultadoFin = mysqli_query($conec->con,$queryFin) or die('Erro no Delete do CONTASRECEBER '.$queryFin);
				
			}
		
		// Tratamento para quando for Mes/Ano
		if($_POST['separacao'] <> 2) {
			$lcCampo = retira_acentos_UTF8($row['filtro']);
		} else {
			$lcCampo = invertecomp($row['filtro'],1);
		}
		
		$lcString.= "<tr>
		<td align='left'>".$lcCampo."</TD>
		<td align='right'>".number_format($row['qtde'],2,",",".")."</TD>
		<td align='right'>".number_format($row['total'],2,",",".")."</TD>
		</tr>";
		

			// Se Selecionado Exportar para o Financeiro e Filtrado um Local de Pagamento
			if($_POST['processarpara'] == 2 && $_POST['localpagto'] <> -1) {
				// Alterado em 01/02/2016 para gravar depois de apagar com delete from
				// Gravando na Tabela ContasReceber
				$queryFin = "INSERT INTO financeiro.contasreceber (dtemissao,dtvencto,nrodoc,codcliente,codcusto,valor,tpcliente,unidade,historico,codhistorico,codcontabil) ".
						" values ('".$dtinicial."','".$dtinicial."','".$dtpagto.$sequencia."',".$_SESSION['codcliente'].",".$_SESSION['centrocusto'].",".$row['total'].",1,".$_SESSION['s_local'].",'".$lcCampo."',".$_SESSION['historicopadrao'].",".$_SESSION['ctacontabil'].")";
				$resultadoFin = mysqli_query($conec->con,$queryFin) or die('Erro no Insert do FINANCEIRO '.$queryFin);
			}
	
		
			$lntotalpg+=$row['total'];
			
			$i++;
			$lnqtde+=$row['qtde'];
			$sequencia++;
	
		}
	
	$lcString.= "</table>";
	
	// Gravo nos registros da tabela carne_pagamentos que foram exportados para o Financeiro
	// e Filtrado um Local de Pagamento	
	if($lntotalpg > 0 && $_POST['processarpara'] == 2 && $_POST['localpagto'] <> -1) {

		$sequencia = 1;
	
		if($_POST['localpagto'] <> -1) {
			$sequencia = $_POST['localpagto'];
		}
		
       $query = "SELECT k.id FROM carne_titular c
		left Join carne_contratos p on p.idtitular = c.id
		left Join carne_tipoplano q on q.id = p.plano
		left Join carne_competenciaplano d on d.idplano = p.plano
		left Join carne_pagamentos k on k.idcliente = c.id
		left Join carne_localpagto l on l.id = k.localpagto
		left Join usuarios u on u.codigo = k.usuario
		Where p.status = 0 and k.databaixa between '".$dtinicial."' and '".$dtfinal."' ".$pcwhere;
		$resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
		
			while($row = mysqli_fetch_array($resultado)){
	
			// Gravando na Tabela carne_pagamentos o nro doc referente no Financeiro
			$queryFin = "Update carne_pagamentos set docfinanceiro = '".$dtpagto.$sequencia."' where id =".$row['id'];
			$resultadoFin = mysqli_query($conec->con,$queryFin) or die('Erro no Insert do FINANCEIRO '.$queryFin);
			
			}
			
	}

	
	//<p>&nbsp;</p>";
	
	// Resumo
	$lcString.= "<table width='100%' border='0'>
  	<tr>
    <th align='center'>RESUMO</th>
    </tr>
  	<tr>
    <td align='left'>Total Pago</th>
    <td align='right'>".number_format($lntotalpg,2,",",".")."</th>    
    </tr>
  	<tr>
    <td align='left'>Total Registros listados</th>
    <td align='right'>".$lnqtde."</th>    
    </tr>
	</table>
    </table>";

	
			// Quando Conferencia verifico se existe contrato de contribuinte como INATIVO	
			if($_POST['processarpara'] == 1 && $_POST['localpagto'] <> -1) {

				// Cabe�alho do regisrtos encontrados
				$lcStringInativo = "<br><h2 style='color:#069;'>CONTRATOS INATIVOS NO CADASTRO - FAVOR CORRIGIR</h2><br>
				<table width='800' border='1' cellspacing='1' cellpadding='1'>
				<tr>
				<th scope='col' align='center'>Nome do Contribuinte</th>
				<th scope='col' align='center'>STATUS DO CONTRATO</th>
				</tr>";
				
		       $query = "SELECT c.nometitular FROM carne_titular c
				left Join carne_contratos p on p.idtitular = c.id
				left Join carne_tipoplano q on q.id = p.plano
				left Join carne_competenciaplano d on d.idplano = p.plano
				left Join carne_pagamentos k on k.idcliente = c.id
				left Join carne_localpagto l on l.id = k.localpagto
				left Join usuarios u on u.codigo = k.usuario
				Where p.status = 1 and k.databaixa between '".$dtinicial."' and '".$dtfinal."' ".$pcwhere." order by c.nometitular";
				$resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
				$nInativos = 0;
				
					while($row = mysqli_fetch_array($resultado)){

						$lcStringInativo.= "<tr>
						<td align='left'>".$row['nometitular']."</TD>
						<td align='right'>INATIVO</TD>
						</tr>";
						$nInativos++;
					
					}
				
				$lcStringInativo.= "</table>";
				
				if($nInativos > 0){
					$lcString.=$lcStringInativo;
				}
				
			}
	

	$lcString.= "<br><br><table width='100%' border='0'>
  	<tr>
    <td align='left'>_______________________________________</td>
    <td align='left'>_______________________________________</td>
    </tr>
  	<tr>
    <td align='left'>Respons&aacute;vel Setor Carn&ecirc;</td>
    <td align='left'>Respons&aacute;vel Setor Financeiro</td>
    </tr>    
    </table>";
	
//$mpdf=new mPDF_('en-x','A4','','',32,25,47,47,10,10); 
$mpdf=new mPDF_('en-x','A4','','',12,12,40,45,1,5);

$mpdf->mirrorMargins = 1;	// Use different Odd/Even headers and footers and mirror margins

$date = date("d/m/Y g:i a");

$cTitulo = "Relat&oacute;rio Recebimentos de Carn&ecirc; PARA CONFER&Ecirc;NCIA";

// Se Selecionado Exportar para o Financeiro	
if($_POST['processarpara'] == 2) {
$cTitulo = "Relat&oacute;rio Recebimentos de Carn&ecirc; Exportados para o FINANCEIRO";
}

$header = "<table width='100%' style='border-bottom: 1px solid #000000; vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000088;'><tr>
<td width='33%'>".$date."</span></td>
<td width='33%' align='center'><img src='../../logo.png' width='126px' /></td>
<td width='33%' style='text-align: right;'><span style='font-weight: bold;'>Pag. <span style='font-size:11pt;'>{PAGENO}</span></td>
</tr>
</table>
<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 14pt; color: #000000;'><tr>
<td width='33%' align='center'>".$cTitulo."</td>
</tr>
</table>".$lcBorda."";

$headerE = "<table width='100%' style='border-bottom: 1px solid #000000; vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000088;'><tr>
<td width='33%'>".$date."</span></td>
<td width='33%' align='center'><img src='../../logo.png' width='126px' /></td>
<td width='33%' style='text-align: right;'><span style='font-weight: bold;'>Pag. <span style='font-size:11pt;'>{PAGENO}</span></td>
</tr>
</table>
<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 14pt; color: #000000;'><tr>
<td width='33%' align='center'>Relat&oacute;rio Recebimentos de Carn&ecirc;</td>
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


$html = '
<h1>mPDF</h1>
<h2>Headers & Footers Method 2</h2>
<h3>Odd / Right page</h3>
<p>Nulla felis erat, imperdiet eu, ullamcorper non, nonummy quis, elit. Suspendisse potenti. Ut a eros at ligula vehicula pretium. Maecenas feugiat pede vel risus. Nulla et lectus. Fusce eleifend neque sit amet erat. Integer consectetuer nulla non orci. Morbi feugiat pulvinar dolor. Cras odio. Donec mattis, nisi id euismod auctor, neque metus pellentesque risus, at eleifend lacus sapien et risus. Phasellus metus. Phasellus feugiat, lectus ac aliquam molestie, leo lacus tincidunt turpis, vel aliquam quam odio et sapien. Mauris ante pede, auctor ac, suscipit quis, malesuada sed, nulla. Integer sit amet odio sit amet lectus luctus euismod. Donec et nulla. Sed quis orci. </p>
<pagebreak />
<h3>Even / Left page</h3>
<p>Nulla felis erat, imperdiet eu, ullamcorper non, nonummy quis, elit. Suspendisse potenti. Ut a eros at ligula vehicula pretium. Maecenas feugiat pede vel risus. Nulla et lectus. Fusce eleifend neque sit amet erat. Integer consectetuer nulla non orci. Morbi feugiat pulvinar dolor. Cras odio. Donec mattis, nisi id euismod auctor, neque metus pellentesque risus, at eleifend lacus sapien et risus. Phasellus metus. Phasellus feugiat, lectus ac aliquam molestie, leo lacus tincidunt turpis, vel aliquam quam odio et sapien. Mauris ante pede, auctor ac, suscipit quis, malesuada sed, nulla. Integer sit amet odio sit amet lectus luctus euismod. Donec et nulla. Sed quis orci. </p>
';

$mpdf->WriteHTML($lcString);

$mpdf->Output();
exit;
    
?>
