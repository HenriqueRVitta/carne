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


ini_set("memory_limit","64M");
	

	include("../../includes/mpdf54/mpdf.php");	
	include ("../../includes/include_geral_III.php");
	$conec = new conexao;
	$conec->conecta('MYSQL');
	
	$lnCompet = substr($_POST['mesano'],3,4).substr($_POST['mesano'],0,2);

	$dtinicial = '1900-01-01';	
	$dtfinal = date('Y-m-d');
	$situacao = 'ATIVOS/INATIVOS';

	if(!empty($_POST['datapagto'])) {
		$dtinicial = Fdate($_POST['datapagto']);
	}		


		$pcwhere=" c.situacao = 'ATIVO'";
		$situacao = 'ATIVOS';

		if($_POST['grupo']<>-1) {
			$pcwhere.=" and c.grupo =".$_POST['grupo']."";
		}
		
		$tiporel = "Analitico"; 

	// Inicio Dados Cabecalho	
	$lcBorda.="<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000000;'>";

	if(!empty($_POST['datapagto'])) {
	
		$lcBorda.="<tr>
		<td align='right'>Data Pagto:</TD>
		<td align='left'>".$_POST['datapagto']."</TD>";
			
		$lcBorda.="</tr>
		<tr>";
	}	
	
		$lcBorda.= "<td align='right'>Relat&oacute;rio:</TD>
		<td align='left'>".$tiporel."</TD>";
		
		$lcBorda.= "<td align='right'>Situacao Cadastral:</TD>
		<td align='left'>".$situacao."</TD>";
		
	$lcBorda.= "</tr>
	</table>";
	// Fim Dados Cabecalho
		
	$pcordem	= " order by c.nometitular";

	// Come�a aqui a listar os registros
	$query = "SELECT c.id, c.nometitular, c.endereco, c.numero, c.bairro, c.cep, c.cidade, c.uf, c.registro, c.datanasc, 
	c.telefoneres, c.qtdefilhos, c.situacao, FLOOR(DATEDIFF(NOW(), c.datanasc) / 365) as idade, p.nrocontrato, c.nrocarne, p.plano, p.diavencto, p.datacontrato, q.descricao, q.percdesc, d.valor, 
	d.compet_ini, d.compet_fim FROM carne_titular c
	Join carne_contratos p on p.idtitular = c.id
	Join carne_tipoplano q on q.id = p.plano
	Join carne_competenciaplano d on d.idplano = p.plano
	Where ".$pcwhere." ".$pcordem;
              
    //print_r($query);
    //print_r($_POST);
    //break;
      
	// Cabe�alho do regisrtos encontrados
	$lcString.= "<table style='font-family: serif; font-size: 9pt; color: #000088;' width='800' border='1' cellspacing='1' cellpadding='1'>
	<tr>
	<th scope='col' align='center'>Nro Carn&ecirc;</th>
	<th scope='col' align='center'>Nome do Cliente</th>
	<th scope='col' align='center'>Plano</th>	
	<th scope='col' align='center'>Data Pagto</th>
	<th scope='col' align='center'>Mes/Ano</th>
	<th scope='col' align='center'>Vlr Plano</th>
	</tr>";
       
    $resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
	$i=0;
	$inativos = 0;
	$geral = 0;
	$vlrtotal = 0;
	
	while($row = mysqli_fetch_array($resultado)){
    		
		$dtreg = str_replace('/','',substr(converte_datacomhora($_POST['datapagto']),0,10));
		
		if($row['nrocarne'] > 0) { $nroreg = $row['nrocarne']; } else { $nroreg = $row['id']; }
		
		$lcString.= "<tr>
		<td align='center'>".retira_acentos_UTF8($nroreg)."</TD>
		<td align='left'>".retira_acentos_UTF8($row['nometitular'])."</TD>
		<td align='left'>".$row['descricao']."</TD>
		<td align='center'>".$_POST['datapagto']."</TD>
		<td align='right'>".substr($lnCompet,4,2)."/".substr($lnCompet,0,4)."</TD>
		<td align='right'>".$row['valor']."</TD>
		</tr>";

		$vlrtotal+=$row['valor'];

		$queryDelPagto = "delete from carne_pagamentos where nrocarne =".$row['nrocarne']." and mesano=".$lnCompet."";
	    $resultadoPagto = mysqli_query($conec->con,$queryDelPagto) or die('ERRO NA QUERY !'.$queryDelPagto);

	    if($_POST['excluir']==1) {
		$queryDelPagto = "INSERT INTO carne_pagamentos (idcliente,nrocarne,mesano,databaixa,localpagto,vlrcalculado,vlrpago,taxa,unidade,usuario)".
					" values (".$row['id'].",".$row['nrocarne'].",".$lnCompet.",'".$dtinicial."',".$_POST['localpagto'].",".$row['valor'].",".$row['valor'].",".$row['percdesc'].",".$_SESSION['s_local'].",".$_SESSION['s_uid'].")";		
	    $resultadoPagto = mysqli_query($conec->con,$queryDelPagto) or die('ERRO NA QUERY !'.$queryDelPagto);
	    }	    
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
$mpdf=new mPDF('s','A4','','',25,15,21,22,10,10); 
$mpdf->StartProgressBarOutput();
$mpdf->mirrorMargins = 1;
$mpdf->SetDisplayMode('fullpage','two');
$mpdf->useGraphs = true;
$mpdf->list_number_suffix = ')';
$mpdf->hyphenate = true;
$mpdf->debug  = true;
*/
		


//$mpdf=new mPDF('en-x','A4','','',32,25,47,47,10,10); 
$mpdf=new mPDF('en-x','A4','','',12,12,40,45,1,5);

$mpdf->mirrorMargins = 1;	// Use different Odd/Even headers and footers and mirror margins

$date = date("d/m/Y g:i a");


$header = "<table width='100%' style='border-bottom: 1px solid #000000; vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000088;'><tr>
<td width='33%'>".$date."</span></td>
<td width='33%' align='center'><img src='../../logo.png' width='126px' /></td>
<td width='33%' style='text-align: right;'><span style='font-weight: bold;'>Pag. <span style='font-size:11pt;'>{PAGENO}</span></td>
</tr>
</table>
<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 14pt; color: #000000;'><tr>
<td width='33%' align='center'>Registro de Pagamentos por GRUPO de Titular</td>
</tr>
</table>".$lcBorda."";

$headerE = "<table width='100%' style='border-bottom: 1px solid #000000; vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000088;'><tr>
<td width='33%'>".$date."</span></td>
<td width='33%' align='center'><img src='../../logo.png' width='126px' /></td>
<td width='33%' style='text-align: right;'><span style='font-weight: bold;'>Pag. <span style='font-size:11pt;'>{PAGENO}</span></td>
</tr>
</table>
<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 14pt; color: #000000;'><tr>
<td width='33%' align='center'>Registro de Pagamentos por GRUPO de Titular</td>
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

$mpdf->WriteHTML($lcString);

$mpdf->Output();
exit;
    
?>
