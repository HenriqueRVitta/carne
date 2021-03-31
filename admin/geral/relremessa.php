<?php
/*      Copyright 2015 MCJ Assessoria Hospitalar e Inform�tica LTDA

        Desenvolvedor: Carlos Henrique R Vitta
		Data: 12/03/2021 16:25 GLPI 
		* Módulo Carnê *
		Relatório dos Contribuintes da Remessa Banco

*/

	session_start();

// Definições da barra de progresso
//==============================================================
define("_JPGRAPH_PATH", '../../includes/mpdf54/'); // must define this before including mpdf.php file
$JpgUseSVGFormat = true;

define('_MPDF_URI','../../includes/mpdf54/'); 	// must be  a relative or absolute URI - not a file system path
//==============================================================

date_default_timezone_set('America/Sao_Paulo');

ini_set('memory_limit', '-1');
	

	include("../../includes/mpdf54/mpdf.php");	
	include ("../../includes/include_geral_III.php");

	$conec = new conexao;
	$conec->conecta('MYSQL');
	
    $sql="SELECT a.data,a.banco,a.arquivo,b.nome FROM carne_lote a join usuarios b on b.codigo = a.usuario  where a.id = ".$_GET['lote']." ";
    $commit = mysqli_query($conec->con,$sql);
    $rowlote = mysqli_fetch_array($commit);

	// Inicio Dados Cabecalho	
	$lcBorda.="<table>";

		$lcBorda.="<tr>
		<td align='right'>Data/Hora:</TD>
		<td align='left'>".$rowlote['data']."</TD>
		<td align='right'>Banco:</TD>
		<td align='left'>".$rowlote['banco']."</TD>";

        $lcBorda.="<td align='right'>Usuário:</TD>
		<td align='left'>".$rowlote['nome']."</TD>";
		
		$lcBorda.="</tr>
		<tr>";
	
		$lcBorda.= "<td align='right'>Arquivo:</TD>
		<td align='left'>".$rowlote['arquivo']."</TD>";
		
		$lcBorda.= "<td align='right'>:</TD>
		<td align='left'></TD>";

		$lcBorda.="<td align='right'>:</TD>
		<td align='left'></TD>";
		
	$lcBorda.= "</tr>
	</table>";
	// Fim Dados Cabecalho

	$query = "SELECT a.idtitular, a.sacado, a.data_vencimento, a.valor_boleto, b.datacontrato
    FROM carne_remessaboleto a Join carne_contratos b on b.idtitular = a.idtitular where a.lote ='".$_GET['lote']."'";
              
      //print_r($query);
      //print_r($_POST);
      //break;
      
	// Cabeçaalho do regisrtos encontrados
	$lcString.= "<table style='font-family: serif; font-size: 9pt; color: #000088;' width='800' border='1' cellspacing='1' cellpadding='1'>
	<tr>
	<th scope='col' align='center'>Id Cliente</th>
	<th scope='col' align='center'>Nome do Cliente</th>
	<th scope='col' align='center'>Dt.Vencto</th>
	<th scope='col' align='center'>Valor</th>	
	<th scope='col' align='center'>Venc.Contrato</th>	
	</tr>";
       
    $resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
	$i=0;
	$geral = 0;
	$vlrtotal = 0;
	
	
	while($row = mysqli_fetch_array($resultado)){
		
		$dtvencto = str_replace('/','',substr(converte_datacomhora($row['data_vencimento']),0,10));
        $dtcontrato = str_replace('/','',substr(converte_datacomhora($row['datacontrato']),0,10));
		
		$nroreg = $row['idtitular'];
		
		$lcString.= "<tr>
		<td align='center'>".$nroreg."</TD>
		<td align='left'>".retira_acentos_UTF8($row['sacado'])."</TD>
		<td align='center'>".mask($dtvencto,'##/##/####')."</TD>
		<td align='center'>".number_format($row['valor_boleto'],2,",",".")."</TD>
		<td align='left'>".mask($dtcontrato,'##/##/####')."</TD>
		</tr>";
				
		$vlrtotal+=$row['valor_boleto'];
		

		$i++;		
		
	}
	
	$geral= $i;
	$lcString.= "</table>";
	
	//<p>&nbsp;</p>";
	
	// Resumo
	$lcString.= "<table width='100%' border='0'>
  	<tr>
    <th align='center'>RESUMO</th>
    </tr>
  	<tr>
    <td align='left'>Qtde Listados</td>
    <td align='right'>".$geral."</td>    
    </tr>
  	<tr>
    <td align='left'>Total Geral Valor Plano</td>
    <td align='right'>".number_format($vlrtotal,2,",",".")."</td>    
    </tr>
    
    </table>";
	
//print_r($lcString);
//break;

/*
$mpdf=new mPDF_('s','A4','','',25,15,21,22,10,10); 
$mpdf->StartProgressBarOutput();
$mpdf->mirrorMargins = 1;
$mpdf->SetDisplayMode('fullpage','two');
$mpdf->useGraphs = true;
$mpdf->list_number_suffix = ')';
$mpdf->hyphenate = true;
$mpdf->debug  = true;
*/
		


//$mpdf=new mPDF_('en-x','A4','','',32,25,47,47,10,10); 
$mpdf=new mPDF_('en-x','A4','','',12,12,40,45,1,5);

$mpdf->mirrorMargins = 1;	// Use different Odd/Even headers and footers and mirror margins

$date = date("d/m/Y g:i a");


$header = "<table width='100%' style='border-bottom: 1px solid #000000; vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000088;'><tr>
<td width='33%'>".$date."</span></td>
<td width='33%' align='center'><img src='../../logo.png' width='126px' /></td>
<td width='33%' style='text-align: right;'><span style='font-weight: bold;'>Pag. <span style='font-size:11pt;'>{PAGENO}</span></td>
</tr>
</table>
<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 14pt; color: #000000;'><tr>
<td width='33%' align='center'>Relat&oacute;rio de Remessa Banco SICOOB</td>
</tr></table>";

//.$lcBorda."";

$headerE = "<table width='100%' style='border-bottom: 1px solid #000000; vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000088;'><tr>
<td width='33%'>".$date."</span></td>
<td width='33%' align='center'><img src='../../logo.png' width='126px' /></td>
<td width='33%' style='text-align: right;'><span style='font-weight: bold;'>Pag. <span style='font-size:11pt;'>{PAGENO}</span></td>
</tr>
</table>
<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 14pt; color: #000000;'><tr>
<td width='33%' align='center'>Relat&oacute;rio dos Titulares de Carn&ecirc;</td>
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

// Se selecionado para Gerar EXCEL
if($_POST['gerarexecel'] == 2) {

	
$dadosXls = $header.$lcString.$footer;

// Definimos o nome do arquivo que ser� exportado  
$arquivo = "RelatorioCadastroCarne".$date.".xls";  
// Configura��es header para for�ar o download  
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$arquivo.'"');
header('Cache-Control: max-age=0');
// Se for o IE9, isso talvez seja necess�rio
header('Cache-Control: max-age=1');
       
// Envia o conte�do do arquivo  
echo $dadosXls;
	

} else {
	

//$lcString = $header.$lcString.$footer;
//print $lcString;


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

}

?>
