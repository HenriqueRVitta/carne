<?php
/*      Copyright 2015 MCJ Assessoria Hospitalar e Inform�tica LTDA

        Desenvolvedor: Carlos Henrique R Vitta
		Data: 13/03/2015 07:16

		* M�dulo Carn� *

		Relatorio de Carn�s parcelados com seus respectivos valores

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
	$plano = $_POST['plano'];
	$localpagto = $_POST['localpagto'];
	$cidade		= $_POST['cidade'];
	$pcwhere	= "";

		if($plano<> -1 ) {
			$pcwhere.=" and p.plano =".$plano;
		}

		if($localpagto<> -1 ) {
			$pcwhere.=" and k.localpagto =".$localpagto;
		}
		

		if($_POST['cidade'] <> -1) {
			$pcwhere.=" and c.cidade like '".trim($_POST['cidade'])."%'";
		}

	    $tiporel = "Analitico"; 


	// Inicio Dados Cabecalho	
	$lcBorda.="<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000000;'>
	<tr>
	<td align='right'>Data Inicial:</TD>
	<td align='left'>".$_POST['datainicio']."</TD>
	<td align='right'>Data Final:</TD>
	<td align='left'>".$_POST['datafim']."</TD>";

	if($_POST['cidade'] <> -1) {
	$lcBorda.="<td align='right'>Cidade:</TD>
	<td align='left'>".$_POST['cidade']."</TD>";
	}
	
	$lcBorda.="</tr>
	<tr>";
	
	if(isset($_POST['plano'])) {

		$sql="SELECT descricao FROM carne_tipoplano where id = ".$_POST['plano']." ";
		$commit = mysqli_query($conec->con,$sql);
		$i=0;
			while($row = mysqli_fetch_array($commit)){

				$lcBorda.= "<td align='right'>Tipo de Plano:</TD>
				<td align='left'>".$row['descricao']."</TD>";
				
				$i++;
			}
		
	}
	
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
	
		$lcBorda.= "<td align='right'>Relat&oacute;rio:</TD>
		<td align='left'>".$tiporel."</TD>";

	// Situa��o Pagos
	if($_POST['situacao']==2) {
		$lcBorda.= "<td align='right'>Situacao:</TD>
		<td align='left'>PAGOS</TD>";
	} 

	// Situa��o Em Aberto	
	if($_POST['situacao']==3) {
		$lcBorda.= "<td align='right'>Situacao:</TD>
		<td align='left'>EM ABERTO</TD>";
	}

	if(!empty($_POST['nrocarne'])) {
		$lcBorda.= "<td align='right'>Nro Carn&ecirc;:</TD>
		<td align='left'>".$_POST['nrocarne']."</TD>";
	}
	
	$lcBorda.= "</tr>
	</table>";
	
	// Fim Dados Cabecalho
		
	$pcordem	= " order by c.nometitular";

	$lcgroup = "";
	
	// Situa��o Pagos
	if($_POST['situacao']==2) {
		$pcwhere = " and k.databaixa > '1900-01-01'";
	} 

	// Situa��o Em Aberto	
	if($_POST['situacao']==3) {
		$pcwhere = " and k.databaixa = '1900-01-01'";
	}
	
	if(!empty($_POST['nrocarne'])) {
		$pcwhere = " and k.nrocarne = ".$_POST['nrocarne']."";
	}
	
	// Come�a aqui a listar os registros
       $query = "SELECT count(*) as qtde, c.id, c.nometitular, c.cidade, p.nrocontrato, p.plano, p.diavencto, p.datacontrato, q.descricao, q.percdesc, d.valor, d.compet_ini, d.compet_fim, ".
       " k.dtregistro, k.id as idpagto, k.nrocarne, k.mesano, k.databaixa, l.descricao as desclocal, k.localpagto, k.vlrmensal, k.vlrparcelado, u.nome FROM carne_titular c ".
       " Join carne_contratos p on p.idtitular = c.id ".
       " Join carne_tipoplano q on q.id = p.plano ".
       " Join carne_competenciaplano d on d.idplano = p.plano ".
       " Join carne_parcelamento k on k.idcliente = c.id ".
       " Left Join carne_localpagto l on l.id = k.localpagto ".
       " left Join usuarios u on u.codigo = k.usuario ".
       " Where k.dtregistro between '".$dtinicial."' and '".$dtfinal."' ".$pcwhere." group by k.nrocarne".$pcordem." limit 4000";
              
      //print_r($query);
      //print_r($_POST);
      //break;
      
	// Cabe�alho do regisrtos encontrados
	$lcString.= "<table width='800' border='1' cellspacing='1' cellpadding='1'>
	<tr>
	<th scope='col' align='center'>Nome do Cliente</th>
	<th scope='col' align='center'>Nro Carn&ecirc;</th>
	<th scope='col' align='center'>Data Inicio</th>
	<th scope='col' align='center'>Taxa %</th>
	<th scope='col' align='center'>Vlr Mensal</th>	
	<th scope='col' align='center'>Vlr Parcelado</th>	
	<th scope='col' align='center'>Parcelas</th>
	<th scope='col' align='center'>Total</th>	
	</tr>";
       
    $resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
	$i=0;
	$lntotalpg = 0.00;
	
	while($row = mysqli_fetch_array($resultado)){
		
		$dtregistro = str_replace('/','',substr(converte_datacomhora($row['dtregistro']),0,10));

		//		<td align='left'>".retira_acentos_UTF8($row['descricao'])."</TD>
		
		$lcString.= "<tr>
		<td align='left'>".retira_acentos_UTF8($row['nometitular'])."</TD>
		<td align='center'>".$row['nrocarne']."</TD>
		<td align='center'>".mask($dtregistro,'##/##/####')."</TD>
		<td align='center'>".$row['percdesc']."</TD>
		<td align='right'>".number_format($row['vlrmensal'],2,",",".")."</TD>
		<td align='right'>".number_format($row['vlrparcelado'],2,",",".")."</TD>
		<td align='center'>".$row['qtde']."</TD>
		<td align='right'>".number_format($row['vlrparcelado']*$row['qtde'],2,",",".")."</TD>
		
		</tr>";
		
		$lntotalpg+=$row['vlrparcelado']*$row['qtde'];
		
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


//$mpdf=new mPDF_('en-x','A4','','',12,12,40,45,5,5);
$mpdf=new mPDF_('en-x','A4','','',12,12,40,45,1,5);

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
<td width='33%' align='center'>Relat&oacute;rio de Carn&ecirc; Parcelados</td>
</tr>
</table>".$lcBorda."";

$headerE = "<table width='100%' style='border-bottom: 1px solid #000000; vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000088;'><tr>
<td width='33%'>".$date."</span></td>
<td width='33%' align='center'><img src='../../logo.png' width='126px' /></td>
<td width='33%' style='text-align: right;'><span style='font-weight: bold;'>Pag. <span style='font-size:11pt;'>{PAGENO}</span></td>
</tr>
</table>
<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 14pt; color: #000000;'><tr>
<td width='33%' align='center'>Relat&oacute;rio de Carn&ecirc; Parcelados</td>
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
