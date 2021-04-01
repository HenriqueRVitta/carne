<?php

ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', dirname(__FILE__) . '/error_log.txt');
//error_reporting(0); 
error_reporting(E_ALL);

//ob_clean();
//ob_start();

//session_start();

date_default_timezone_set('America/Sao_Paulo');

include ("../../includes/classes/conecta.class.php");
include ("../../includes/classes/auth.class.php");
include ("../../includes/classes/dateOpers.class.php");
include ("../../includes/config.inc.php");
include ("../../includes/functions/funcoes.inc");


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
	$lcString = '';

		if($plano<> -1 ) {
			$pcwhere.=" and p.plano =".$plano;
		}

		if($localpagto<> -1 ) {
			$pcwhere.=" and k.localpagto =".$localpagto;
		}
		
		if($titular<> -1 ) {
			$pcwhere.=" and c.id =".$titular;
		}

		if(!empty($_POST['mesano'])) {
			$pcwhere.=" and k.mesano =".$lnCompet;
		}

		if($_POST['cidade'] <> -1) {
			$pcwhere.=" and c.cidade like '".trim($_POST['cidade'])."%'";
		}
		
		if($_POST['usuario'] <> -1) {
			$pcwhere.=" and k.usuario = ".$_POST['usuario']."";
		}
		
	if($_POST['tiporelatorio']==1) { $tiporel = "Analitico"; } else { $tiporel = "Sintetico"; } 


	// Inicio Dados Cabecalho	
	$lcBorda.="<table width='100%' style='vertical-align: bottom; font-family: serif; font-size: 9pt; color: #000000;'>
	<tr>
	<td align='right'>Data Inicial:</TD>
	<td align='left'>".$_POST['datainicio']."</TD>
	<td align='right'>Data Final:</TD>
	<td align='left'>".$_POST['datafim']."</TD>";

	if(!empty($_POST['mesano'])) {
	$lcBorda.="<td align='right'>M&ecirc;s/Ano:</TD>
	<td align='left'>".$_POST['mesano']."</TD>";
	}

	if($_POST['cidade'] <> -1) {
	$lcBorda.="<td align='right'>Cidade:</TD>
	<td align='left'>".$_POST['cidade']."</TD>";
	}

	if(isset($_POST['usuario']) && $_POST['usuario'] <> -1) {
		$sql="SELECT nome FROM usuarios where codigo = ".$_POST['usuario']." ";
		$commit = mysqli_query($conec->con,$sql);
		$row = mysqli_fetch_array($commit);

		$lcBorda.="<td align='right'>Usu&aacute;rio:</TD>
	<td align='left'>".retira_acentos_UTF8($row['nome'])."</TD>";
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

	$lcBorda.= "</tr>
	</table>";


	// Nome do Paciente
	if(isset($_POST['titular']) && $_POST['titular'] <> -1 ) {
		$lcBorda.= "<table border='0' cellspacing='2' cellpadding='2'>";
		
		$sql="SELECT nometitular FROM carne_titular where id = ".$_POST['titular']." ";
		$commit = mysqli_query($conec->con,$sql);
		$i=0;
			while($row = mysqli_fetch_array($commit)){

				$lcBorda.= "<td align='right'>Cliente:</TD>
				<td align='left'>".$row['nometitular']."</TD>";
				
				$i++;
			}
		$lcBorda.= "</table>";
	}
	
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
		

	$nordem = $_POST['separacao'];

	switch ( $nordem ){
	  case 1:
		$pcordem	= " order by c.cidade";
		$pcgroup	= " group by c.cidade";
		$lcColumn	= " c.cidade";
		break;
	  case 2:
		$pcordem	= " order by k.mesano";
		$pcgroup	= " group by k.mesano";
		$lcColumn	= " k.mesano";
		break;
	  case 3:
		$pcordem	= " order by u.nome";
		$pcgroup	= " group by u.nome";
		$lcColumn	= " u.nome";
		break;
	  case 4:
		$pcordem	= " order by l.descricao";
		$pcgroup	= " group by l.descricao";
		$lcColumn	= " l.descricao";
		break;
	  default:
		$pcordem	= " order by c.cidade";
		$pcgroup	= " group by c.cidade";
		$lcColumn	= " c.cidade";				
	}
		
		
       $query = "SELECT".$lcColumn." as filtro, count(*) as qtde, sum(vlrpago) as total FROM carne_titular c
		left Join carne_contratos p on p.idtitular = c.id
		left Join carne_tipoplano q on q.id = p.plano
		left Join carne_competenciaplano d on d.idplano = p.plano
		Join carne_pagamentos k on k.idcliente = c.id
		left Join carne_localpagto l on l.id = k.localpagto
		left Join usuarios u on u.codigo = k.usuario
		Where k.databaixa between '".$dtinicial."' and '".$dtfinal."' ".$pcwhere." ".$pcgroup." ".$pcordem;
               
      //print_r($query);
      //break;
      
	// Cabecalho do regisrtos encontrados
	$lcString.= "<table width='800' border='1' cellspacing='1' cellpadding='1'>
	<tr>
	<th scope='col' align='center'>Filtro</th>
	<th scope='col' align='center'>Qtde</th>
	<th scope='col' align='center'>Vlr Total</th>
	</tr>";
       
    $resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
	$i=0;
	$lntotalpg = 0.00;
	$lnqtde = 0.00;
	
	while($row = mysqli_fetch_array($resultado)){

		//$dtpagto = str_replace('/','',substr(converte_datacomhora($row['databaixa']),0,10));

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
		
		$lntotalpg+=$row['total'];
		
		$i++;
		$lnqtde+=$row['qtde'];
	}
	
	$lcString.= "</table>";
	
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

// Se selecionado para Gerar EXCEL
if($_POST['gerarexecel'] == 2) {

	
$dadosXls = $header.$lcString.$footer;

// Definimos o nome do arquivo que ser� exportado  
$arquivo = "RecebimentoCarneSintetico".$date.".xls";  
// Configura��es header para for�ar o download  
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$arquivo.'"');
header('Cache-Control: max-age=0');
// Se for o IE9, isso talvez seja necess�rio
header('Cache-Control: max-age=1');
       
// Envia o conte�do do arquivo  
echo $dadosXls;
	

} else {

	$html = $header.$lcString.$footer;

	include("../../includes/mpdf/vendor/autoload.php");
	
	//$mpdf = new \Mpdf\Mpdf();
	$mpdf = new \Mpdf\Mpdf(['debug' => true]);
	$mpdf->WriteHTML($html);
	$mpdf->Output();
	exit;
}
    
?>
