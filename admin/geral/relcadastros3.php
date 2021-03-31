<?php
/*      Copyright 2015 MCJ Assessoria Hospitalar e Inform�tica LTDA

        Desenvolvedor: Carlos Henrique R Vitta
		Data: 08/02/2015 09:55

		* M�dulo Carn� *

		Relat�rio dos Anal�tico do Cadastro de Titular

*/

	session_start();

// Defini��es da barra de progresso
//==============================================================
define("_JPGRAPH_PATH", '../../includes/mpdf54/'); // must define this before including mpdf.php file
$JpgUseSVGFormat = true;

define('_MPDF_URI','../../includes/mpdf54/'); 	// must be  a relative or absolute URI - not a file system path
//==============================================================


ini_set('memory_limit', '-1');
	

	include("../../includes/mpdf54/mpdf.php");	
	include ("../../includes/include_geral_III.php");

	$lnCompet = substr($_POST['mesano'],3,4).substr($_POST['mesano'],0,2);

	$dtinicial = '1900-01-01';	
	$dtfinal = date('Y-m-d');
	$situacao = 'ATIVOS/INATIVOS';

	if(!empty($_POST['datainicio'])) {
		$dtinicial = Fdate($_POST['datainicio']);
	}

	if(!empty($_POST['datafim'])) {
		$dtfinal = Fdate($_POST['datafim']);		
	}

	$plano = $_POST['plano'];
	$cidade	= $_POST['cidade'];


		if($plano<> -1 ) {
			$pcwhere.=" and p.plano =".$plano;
		}

		
		if($_POST['cidade'] <> -1) {
			$pcwhere.=" and c.cidade like '".trim($_POST['cidade'])."%'";
		}

		
		if($_POST['situacao']==2) {
			$pcwhere.=" and c.situacao = 'ATIVO'";
			$situacao = 'ATIVOS';
		}

		if($_POST['situacao']==3) {
			$pcwhere.=" and c.situacao = 'INATIVO'";
			$situacao = 'INATIVOS';
		}

		if($_POST['grupo']<>-1) {
			$pcwhere.=" and c.grupo =".$_POST['grupo']."";
		}
		
		if($_POST['tiporelatorio']==1) { $tiporel = "Analitico"; } else { $tiporel = "Sintetico"; } 


	// Inicio Dados Cabecalho	
	$lcBorda.="<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000000;'>";

	if(empty($_POST['datainicio'])) {
	
		$lcBorda.="<tr>
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
	}
		
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
	
	
		$lcBorda.= "<td align='right'>Relat&oacute;rio:</TD>
		<td align='left'>".$tiporel."</TD>";
		
		$lcBorda.= "<td align='right'>Situacao Cadastral:</TD>
		<td align='left'>".$situacao."</TD>";

		if($_POST['grupo']<>-1) {
		$lcBorda.="<td align='right'>GRUPO:</TD>
		<td align='left'>".$_POST['grupo']."</TD>";
		}
		
	$lcBorda.= "</tr>
	</table>";
	// Fim Dados Cabecalho
		

	$nordem = $_POST['ordem'];

	switch ( $nordem ){
	  case 1:
		$pcordem	= " order by c.id";
	    break;
	  case 2:
		$pcordem	= " order by c.nometitular";
		break;
	  case 3:
		$pcordem	= " order by c.registro";
	  	break;
	  case 4:
		$pcordem	= " order by c.cidade";
	  	break;
  	  default:
		$pcordem	= " order by c.nometitular";
	}

	// Come�a aqui a listar os registros
	$query = "SELECT c.id, c.nometitular, c.endereco, c.numero, c.bairro, c.cep, c.cidade, c.uf, c.registro, c.datainicio, c.datanasc, 
	c.telefoneres, c.qtdefilhos, c.situacao, FLOOR(DATEDIFF(NOW(), c.datanasc) / 365) as idade, p.nrocontrato, c.nrocarne, p.plano, p.diavencto, p.datacontrato, q.descricao, q.percdesc, d.valor, 
	d.compet_ini, d.compet_fim FROM carne_titular c
	left Join carne_contratos p on p.idtitular = c.id
	left Join carne_tipoplano q on q.id = p.plano
	left Join carne_competenciaplano d on d.idplano = p.plano
	Where c.registro between '".$dtinicial."' and '".$dtfinal."' ".$pcwhere." ".$pcordem;
              
      //print_r($query);
      //print_r($_POST);
      //break;
      
	// Cabe�alho do regisrtos encontrados
	$lcString.= "<table style='font-family: serif; font-size: 9pt; color: #000088;' width='800' border='1' cellspacing='1' cellpadding='1'>
	<tr>
	<th scope='col' align='center'>Nro Carn&ecirc;</th>
	<th scope='col' align='center'>Nome do Cliente</th>
	<th scope='col' align='center'>Cidade</th>
	<th scope='col' align='center'>Telefone</th>	
	<th scope='col' align='center'>Plano</th>	
	<th scope='col' align='center'>Data Inicio</th>
	<th scope='col' align='center'>Vlr Plano</th>
	</tr>";
       
    $resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
	$i=0;
	$inativos = 0;
	$geral = 0;
	$vlrtotal = 0;
	
	
	while($row = mysqli_fetch_array($resultado)){
		
		$dtreg = str_replace('/','',substr(converte_datacomhora($row['datainicio']),0,10));
		
		if($row['nrocarne'] > 0) { $nroreg = $row['nrocarne']; } else { $nroreg = $row['id']; }
		
		
		$lcString.= "<tr>
		<td align='center'>".retira_acentos_UTF8($nroreg)."</TD>
		<td align='left'>".retira_acentos_UTF8($row['nometitular'])."</TD>
		<td align='center'>".retira_acentos_UTF8($row['cidade'])."</TD>
		<td align='center'>".mask($row['telefoneres'],'(##)####-#####')."</TD>
		<td align='left'>".$row['descricao']."</TD>
		<td align='center'>".mask($dtreg,'##/##/####')."</TD>
		<td align='right'>".$row['valor']."</TD>
		</tr>";
				
		$vlrtotal+=$row['valor'];
		

		$i++;		
		
	}
	
	$geral= $i + $inativo;
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
<td width='33%' align='center'>Relat&oacute;rio dos Titulares de Carn&ecirc;</td>
</tr>
</table>".$lcBorda."";

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
