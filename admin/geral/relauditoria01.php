<?php
/*      Copyright 2015 MCJ Assessoria Hospitalar e Inform�tica LTDA

        Desenvolvedor: Carlos Henrique R Vitta
		Data: 03/02/2015 13:00

		* M�dulo Carn� *

		Relat�rio dos pagamentos registrados

*/

	session_start();

	ini_set('memory_limit', '-1');
		
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
	
	$pcwhere	= "";
	$lcBorda = "";


		if(!empty($_POST['referencia'])) {
			$pcwhere.=" and a.documento like '%".$_POST['referencia']."%'"." or a.descricao like '%".$_POST['referencia']."%'";
		}

		if($_POST['usuario'] <> -1) {
			$pcwhere.=" and a.usuario = ".$_POST['usuario']."";
		}
		
	$tiporel = "Analitico"; 


	// Inicio Dados Cabecalho	
	$lcBorda.="<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000000;'>
	<tr>
	<td align='right'>Data Inicial:</TD>
	<td align='left'>".$_POST['datainicio']."</TD>
	<td align='right'>Data Final:</TD>
	<td align='left'>".$_POST['datafim']."</TD>";


	if($_POST['usuario'] <> -1) {
		$sql="SELECT nome FROM usuarios where codigo = ".$_POST['usuario']." ";
		$commit = mysqli_query($conec->con,$sql);
		$row = mysqli_fetch_array($commit);

		$lcBorda.="<td align='right'>Usuario:</TD>
	<td align='left'>".retira_acentos_UTF8($row['nome'])."</TD>";
	}
	
	$lcBorda.="</tr>
	<tr>";
	
	if(isset($_POST['referencia'])) {

				$lcBorda.= "<td align='right'>Referencia:</TD>
				<td align='left'>".$row['referencia']."</TD>";
	}

	
		$lcBorda.= "<td align='right'>Relat&oacute;rio:</TD>
		<td align='left'>".$tiporel."</TD>";

	$lcBorda.= "</tr>
	</table>";
	// Fim Dados Cabecalho
		

	$pcordem	= " order by a.data,a.hora";

	$lcgroup =  "";
	

	// Come�a aqui a listar os registros
       $query = "select a.data,a.hora,a.usuario,a.operacao,a.documento,a.descricao,u.nome from auditoria a Left Join usuarios u on u.codigo = a.usuario". 
       " Where a.data between '".$dtinicial."' and '".$dtfinal."'".$pcwhere." and a.detalhes = 'MODULO CARNE' ".$lcgroup." ".$pcordem."";

      
	// Cabe�alho do regisrtos encontrados
	$lcString.= "<table width='800' border='1' cellspacing='1' cellpadding='1'>
	<tr>
	<th scope='col' align='center'>Data</th>
	<th scope='col' align='center'>Hora</th>
	<th scope='col' align='center'>Usuario</th>
	<th scope='col' align='center'>Operacao</th>
	<th scope='col' align='center'>Documento</th>
	<th scope='col' align='center'>Descricao</th>
	</tr>";
       
    $resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
	$i=0;
	$vetorMenu = ArrayMenus();
	
	while($row = mysqli_fetch_array($resultado)){

		$data = str_replace('/','',substr(converte_datacomhora($row['data']),0,10));
		$operacao = $vetorMenu[$row['operacao']];
		

		$lcString.= "<tr>
		<td align='center'>".mask($data,'##/##/####')."</TD>
		<td align='center'>".$row['hora']."</TD>
		<td align='left'>".retira_acentos_UTF8($row['nome'])."</TD>
		<td align='center'>".$operacao."</TD>
		<td align='center'>".$row['documento']."</TD>
		<td align='left'>".retira_acentos_UTF8($row['descricao'])."</TD>
		</tr>";
		
	
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
    <td align='left'>Total Registros listados</td>
    <td align='right'>".$i."</td>    
    </tr>
	</table>
    </table>";


//$mpdf=new mPDF_('en-x','A4','','',32,25,47,47,10,10); 
$mpdf=new mPDF_('en-x','A4','','',12,12,40,45,1,5);

$mpdf->mirrorMargins = 1;	// Use different Odd/Even headers and footers and mirror margins
$mpdf->useSubstitutions = false;

date_default_timezone_set('America/Sao_Paulo');	
$date = date("d/m/Y H:i");


$header = "<table width='100%' style='border-bottom: 1px solid #000000; vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000088;'><tr>
<td width='33%'>".$date."</span></td>
<td width='33%' align='center'><img src='../../logo.png' width='126px' /></td>
<td width='33%' style='text-align: right;'><span style='font-weight: bold;'>Pag. <span style='font-size:11pt;'>{PAGENO}</span></td>
</tr>
</table>
<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 14pt; color: #000000;'><tr>
<td width='33%' align='center'>Relat&oacute;rio Auditoria do Carn&ecirc;</td>
</tr>
</table>";
//</table>".$lcBorda."";

$headerE = "<table width='100%' style='border-bottom: 1px solid #000000; vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000088;'><tr>
<td width='33%'>".$date."</span></td>
<td width='33%' align='center'><img src='../../logo.png' width='126px' /></td>
<td width='33%' style='text-align: right;'><span style='font-weight: bold;'>Pag. <span style='font-size:11pt;'>{PAGENO}</span></td>
</tr>
</table>
<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 14pt; color: #000000;'><tr>
<td width='33%' align='center'>Relat&oacute;rio Auditoria do Carn&ecirc;</td>
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

$mpdf->packTableData = true;	// required for cacheTables
$mpdf->simpleTables = false;  // Cannot co-exist with cacheTables

$mpdf->WriteHTML($lcString);

$mpdf->Output();
exit;
    
?>
