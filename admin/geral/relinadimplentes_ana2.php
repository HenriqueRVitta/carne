<?php
/*      Copyright 2015 MCJ Assessoria Hospitalar e Inform�tica LTDA

        Desenvolvedor: Carlos Henrique R Vitta
		Data: 03/02/2015 13:00

		* Modulo Carne *

		Relatório dos pagamentos registrados

*/

ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
error_reporting(E_ALL);


date_default_timezone_set('America/Sao_Paulo');

include ("../../includes/classes/conecta.class.php");
include ("../../includes/classes/auth.class.php");
include ("../../includes/classes/dateOpers.class.php");
include ("../../includes/config.inc.php");
include ("../../includes/functions/funcoes.inc");

ob_clean();
ob_start();

	$conec = new conexao;
	$conec->conecta('MYSQL');
	
	$lnCompet = substr($_POST['mesano'],3,4).substr($_POST['mesano'],0,2);
	$dtinicial = Fdate($_POST['datainicio']);
	$dtfinal = Fdate($_POST['datafim']);
	$titular = $_POST['titular'];
	$plano = $_POST['plano'];
	$localpagto = $_POST['localpagto'];
	$cidade		= $_POST['cidade'];
	$pcwhere	= "";
	$lcBorda = "";
	$lcString = "";
	
	

		if($titular<> -1 ) {
			$pcwhere.=" and c.id =".$titular;
		}

	if($_POST['tiporelatorio']==1) { $tiporel = "Analitico"; } else { $tiporel = "Sintetico"; } 


	// Inicio Dados Cabecalho	
	$lcBorda.="<table width='800' align='center' style='vertical-align: bottom; font-family: serif; font-size: 12pt; color: #000000;'>
	<tr>
	<td align='right'>Data Inicial:</TD>
	<td align='left'>".$_POST['datainicio']."</TD>
	<td align='right'>Data Final:</TD>
	<td align='left'>".$_POST['datafim']."</TD></tr><tr>
	<td align='right'>Inadimplentes a mais de:</TD>
	<td align='left'>".$_POST['nromeses']." Mes(es)</TD>";

    // Inativar no carne_titular
    if(isset($_POST['inativar']) && $_POST['inativar'] == 2){
    	
	$lcBorda.="<td align='right'>INATIVOU TODOS:</TD>
	<td align='left'>SIM</TD></TR>";
	$lcBorda.= "<tr>";
	
    }
    				
	// Nome do Paciente
	if($_POST['titular'] <> -1 ) {
		
		$sql="SELECT nometitular FROM carne_titular where id = ".$_POST['titular']." ";
		$commit = mysqli_query($conec->con,$sql);
		$i=0;
			while($row = mysqli_fetch_array($commit)){

				$lcBorda.= "<td align='right'>Cliente:</TD>
				<td align='left'>".$row['nometitular']."</TD>";
				
				$i++;
			}
	}

		if($_POST['grupo']<>-1) {
			
			$sql="SELECT descricao FROM carne_grupo where id = ".$_POST['grupo']." ";
			$commit = mysqli_query($conec->con,$sql);
			$row = mysqli_fetch_array($commit);
	
			$lcBorda.="<td align='right'>GRUPO:</TD>
			<td align='left'>".retira_acentos_UTF8($row['descricao'])."</TD>";
			
		}
	
		$lcBorda.= "<td align='right'>Relat&oacute;rio:</TD>
		<td align='left'>".$tiporel."</TD>";

	$lcBorda.= "</tr>
	</table>";	
	// Fim Dados Cabecalho
		

	$nordem = $_POST['ordem'];

	switch ( $nordem ){
	  case 1:
		$pcordem	= " order by c.nometitular";
	    break;
	  case 2:
		$pcordem	= " order by k.databaixa";
		break;
	  case 3:
		$pcordem	= " order by k.mesano";
	  	break;
	  case 4:
		$pcordem	= " order by q.descricao";
	    break;
	  case 5:
		$pcordem	= " order by l.descricao";
	    break;
	  case 7:
		$pcordem	= " order by c.nrocarne";
	    break;
	    default:
		$pcordem	= " order by c.nometitular";
	}

	$lcgroup = "";
	
	if(isset($_POST['separacao']) && $_POST['separacao'] <> -1 ) {

	
	
		switch ( $_POST['separacao'] ){
		  case 1:
			$lcgroup = " group by c.cidade";
		  	break;
		  case 2:
			$lcgroup = " group by k.mesano";
		  	break;
		  case 3:
			$lcgroup = " group by u.nome";
		  	break;
		  case 4:
			$lcgroup = " group by l.descricao";
		  	break;
		  default:
			$lcgroup = " group by c.nometitular";
		}

			
	}


		$pcwhere = '';
		if($_POST['grupo']<>-1) {
			$pcwhere.=" and a.grupo =".$_POST['grupo']."";
		}
	
	
		// Come�a aqui a listar os registros
		/*
       $query = "SELECT c.id, c.nometitular, c.registro, c.nrocarne, c.cidade, p.nrocontrato, p.plano, p.diavencto, p.datacontrato, q.descricao, q.percdesc, d.valor, d.compet_ini, d.compet_fim,
		space(1) as desclocal, space(1) as localpagto, space(10) as databaixa, 0.00 as vlrpago, space(1) as nome FROM carne_titular c
		Join carne_contratos p on p.idtitular = c.id
		Join carne_tipoplano q on q.id = p.plano
		Join carne_competenciaplano d on d.idplano = p.plano
		Where c.situacao = 'ATIVO' ".$pcwhere.$pcordem."";
       */

		$query = "select a.id,a.nometitular, a.datainicio Data_Inicio, d.valor as ValordoPlano,
		sum(b.vlrpago) TotalPago,
		TIMESTAMPDIFF(MONTH,a.datainicio,now()) TotalMeses,
		count(b.databaixa) MesesPagos, sum(b.vlrpago) TotalPago,
		(TIMESTAMPDIFF(MONTH,a.datainicio,now()) - count(b.databaixa)) MesesInadimplente,
		(d.valor * (TIMESTAMPDIFF(MONTH,a.datainicio,now()) - count(b.databaixa))) as TotalDebito
		from carne_titular a Join carne_pagamentos b on b.idcliente = a.id
		join carne_contratos c on c.idtitular = a.id
		join carne_competenciaplano d on d.idplano = c.plano
		and a.situacao = 'ATIVO' ".$pcwhere."  group by a.nometitular,b.idcliente";

      
	// Cabe�alho do regisrtos encontrados
	$lcString.= "<table width='800' align='center' align='center' border='1' cellspacing='1' cellpadding='1'>
	<tr>
	<th scope='col' align='center'>Nome do Cliente</th>
	<th scope='col' align='center'>Desde</th>	
	<th scope='col' align='center'>Nro Carn&ecirc;</th>
	<th scope='col' align='center'>Meses Inadim.</th>
	<th scope='col' align='center'>Vlr Plano</th>
	<th scope='col' align='center'>Vlr do Debito</th>
	</tr>";
       
    $resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
	$i=0;
	$qtdeIna = 0;
	
	$lntotalpg = 0.00;

		while($row = mysqli_fetch_array($resultado)){
				
    	$dtregistro = str_replace('/','',substr(converte_datacomhora($row['Data_Inicio']),0,10));


    	if($row['MesesInadimplente'] > 0 && $row['MesesInadimplente'] >= $_POST['nromeses']) {

    			// Inativar no carne_titular
    			if(isset($_POST['inativar']) && $_POST['inativar'] == 2){
    				$queryinativar = "Update carne_titular set situacao = 'Inativo' where id = ".$row['id'];
    		    	$inativar = mysqli_query($conec->con,$queryinativar) or die('ERRO NA QUERY !'.$queryinativar);
    			}
    		
				$lcString.= "<tr>
				<td align='left'>".retira_acentos_UTF8($row['nometitular'])."</TD>
				<td align='center'>".mask($dtregistro,'##/##/####')."</TD>
				<td align='center'>".$row['id']."</TD>
				<td align='center'>".$row['MesesInadimplente']."</TD>
				<td align='right'>".number_format($row['ValordoPlano'],2,",",".")."</TD>
				<td align='right'>".number_format($row['TotalDebito'],2,",",".")."</TD>
				</tr>";

								
				$lntotalpg+=$row['TotalDebito'];
				$qtdeIna++;
				    	   		
				
			$i++;
		
		}
		
		
	}	
	
	
	$lcString.= "</table>";
	
	//<p>&nbsp;</p>";
	
	// Resumo
	$lcString.= "<table width='800' align='center' border='0'>
  	<tr>
    <th align='center'>RESUMO</th>
    </tr>
  	<tr>
    <td align='left'>Total do Valor de Inadimplentes</th>
    <td align='right'>".number_format($lntotalpg,2,",",".")."</th>    
    </tr>
  	<tr>
    <td align='left'>Total de Inadimplentes listados</th>
    <td align='right'>".$qtdeIna."</th>    
    </tr>
	</table>";


$date = date("d/m/Y H:i");

$header = "<table width='100%' style='border-bottom: 1px solid #000000; vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000088;'><tr>
<td width='33%'>".$date."</span></td>
<td width='33%' align='center'><img src='../../logo.png' width='126px' /></td>
<td width='33%' style='text-align: right;'><span style='font-weight: bold;'>Pag. <span style='font-size:11pt;'>{PAGENO}</span></td>
</tr>
</table>
<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 14pt; color: #000000;'><tr>
<td width='33%' align='center'>Relat&oacute;rio Recebimentos de Carn&ecirc;</td>
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




$html = '
<h1>mPDF</h1>
<h2>Headers & Footers Method 2</h2>
<h3>Odd / Right page</h3>
<p>Nulla felis erat, imperdiet eu, ullamcorper non, nonummy quis, elit. Suspendisse potenti. Ut a eros at ligula vehicula pretium. Maecenas feugiat pede vel risus. Nulla et lectus. Fusce eleifend neque sit amet erat. Integer consectetuer nulla non orci. Morbi feugiat pulvinar dolor. Cras odio. Donec mattis, nisi id euismod auctor, neque metus pellentesque risus, at eleifend lacus sapien et risus. Phasellus metus. Phasellus feugiat, lectus ac aliquam molestie, leo lacus tincidunt turpis, vel aliquam quam odio et sapien. Mauris ante pede, auctor ac, suscipit quis, malesuada sed, nulla. Integer sit amet odio sit amet lectus luctus euismod. Donec et nulla. Sed quis orci. </p>
<pagebreak />
<h3>Even / Left page</h3>
<p>Nulla felis erat, imperdiet eu, ullamcorper non, nonummy quis, elit. Suspendisse potenti. Ut a eros at ligula vehicula pretium. Maecenas feugiat pede vel risus. Nulla et lectus. Fusce eleifend neque sit amet erat. Integer consectetuer nulla non orci. Morbi feugiat pulvinar dolor. Cras odio. Donec mattis, nisi id euismod auctor, neque metus pellentesque risus, at eleifend lacus sapien et risus. Phasellus metus. Phasellus feugiat, lectus ac aliquam molestie, leo lacus tincidunt turpis, vel aliquam quam odio et sapien. Mauris ante pede, auctor ac, suscipit quis, malesuada sed, nulla. Integer sit amet odio sit amet lectus luctus euismod. Donec et nulla. Sed quis orci. </p>
';

//$lcString = $header.$lcString.$footer;
//print $lcString;

// Se selecionado para Gerar EXCEL
if($_POST['gerarexecel'] == 2) {

	
$dadosXls = $header.$lcString.$footer;

// Definimos o nome do arquivo que ser� exportado  
$arquivo = "InadimplenentesCarne".$date.".xls";  
// Configura��es header para for�ar o download  
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$arquivo.'"');
header('Cache-Control: max-age=0');
// Se for o IE9, isso talvez seja necess�rio
header('Cache-Control: max-age=1');
       
// Envia o conte�do do arquivo  
echo $dadosXls;
	

} else {


$html = '
<h1>mPDF</h1>
<h2>Headers & Footers Method 2</h2>
<h3>Odd / Right page</h3>
<p>Nulla felis erat, imperdiet eu, ullamcorper non, nonummy quis, elit. Suspendisse potenti. Ut a eros at ligula vehicula pretium. Maecenas feugiat pede vel risus. Nulla et lectus. Fusce eleifend neque sit amet erat. Integer consectetuer nulla non orci. Morbi feugiat pulvinar dolor. Cras odio. Donec mattis, nisi id euismod auctor, neque metus pellentesque risus, at eleifend lacus sapien et risus. Phasellus metus. Phasellus feugiat, lectus ac aliquam molestie, leo lacus tincidunt turpis, vel aliquam quam odio et sapien. Mauris ante pede, auctor ac, suscipit quis, malesuada sed, nulla. Integer sit amet odio sit amet lectus luctus euismod. Donec et nulla. Sed quis orci. </p>
<pagebreak />
<h3>Even / Left page</h3>
<p>Nulla felis erat, imperdiet eu, ullamcorper non, nonummy quis, elit. Suspendisse potenti. Ut a eros at ligula vehicula pretium. Maecenas feugiat pede vel risus. Nulla et lectus. Fusce eleifend neque sit amet erat. Integer consectetuer nulla non orci. Morbi feugiat pulvinar dolor. Cras odio. Donec mattis, nisi id euismod auctor, neque metus pellentesque risus, at eleifend lacus sapien et risus. Phasellus metus. Phasellus feugiat, lectus ac aliquam molestie, leo lacus tincidunt turpis, vel aliquam quam odio et sapien. Mauris ante pede, auctor ac, suscipit quis, malesuada sed, nulla. Integer sit amet odio sit amet lectus luctus euismod. Donec et nulla. Sed quis orci. </p>
';

$html = $header.$lcString.$footer;

include("../../includes/mpdf/vendor/autoload.php");


$mpdf = new \Mpdf\Mpdf(['debug' => true]);
$mpdf->WriteHTML($html);
$mpdf->Output();

exit;
	
}
?>
