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
	$taxas		= $_POST['taxas'];
	
	$pcwhere	= "";
	$lcBorda = "";
	$lcString = '';
	

		if($taxas<>-1){
			$pcwhere.=" and k.idtaxas =".$taxas;
		}
	
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

		if($_POST['grupo']<>-1) {
			$pcwhere.=" and c.grupo =".$_POST['grupo']."";
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

	if($_POST['usuario'] <> -1) {
		$sql="SELECT nome FROM usuarios where codigo = ".$_POST['usuario']." ";
		$commit = mysqli_query($conec->con,$sql);
		$row = mysqli_fetch_array($commit);

		$lcBorda.="<td align='right'>Usu&aacute;rio:</TD>
	<td align='left'>".retira_acentos_UTF8($row['nome'])."</TD>";
	}

		if($_POST['grupo']<>-1) {
			
			$sql="SELECT descricao FROM carne_grupo where id = ".$_POST['grupo']." ";
			$commit = mysqli_query($conec->con,$sql);
			$row = mysqli_fetch_array($commit);
	
			$lcBorda.="<td align='right'>GRUPO:</TD>
			<td align='left'>".retira_acentos_UTF8($row['descricao'])."</TD>";
			
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
	
	if(isset($_POST['taxas'])) {

		$sql="SELECT descricao FROM carne_taxas where id = ".$_POST['taxas']." ";
		$commit = mysqli_query($conec->con,$sql);
		$i=0;
			while($row = mysqli_fetch_array($commit)){

				$lcBorda.= "<td align='right'>Outras TAXAS:</TD>
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
	if($_POST['titular'] <> -1 ) {
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
	
	// Fim Dados Cabecalho
		

	$nordem = $_POST['ordem'];

	switch ( $nordem ){
	  case 1:
		$pcordem	= " order by c.nometitular,k.databaixa";
	    break;
	  case 2:
		$pcordem	= " order by k.databaixa,c.nometitular";
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
	  case 6:
		$pcordem	= " order by k.id";
	    break;
	    default:
		$pcordem	= " order by c.nometitular";
	}

	$lcgroup =  " group by k.databaixa,c.nometitular";
	
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
			$lcgroup = " group by k.databaixa,c.nometitular";
		}

		$lcBorda.= "</table>";
			
	}
	
	// Come�a aqui a listar os registros
       $query = "SELECT c.id, c.cpf, c.nometitular, c.cidade, p.nrocontrato, p.plano, p.diavencto, p.datacontrato, q.descricao, q.percdesc, d.valor, d.compet_ini, d.compet_fim, ".
       " k.id as idpagto, k.nrocarne, k.mesano, k.databaixa, l.descricao as desclocal, k.localpagto, sum(k.vlrpago) as vlrpago, u.nome FROM carne_titular c ".
       " left Join carne_contratos p on p.idtitular = c.id ".
       " left Join carne_tipoplano q on q.id = p.plano ".
       " Join carne_competenciaplano d on d.idplano = p.plano ".
       " Join carne_pagamentos k on k.idcliente = c.id ".
       " Left Join carne_localpagto l on l.id = k.localpagto ".
       " left Join usuarios u on u.codigo = k.usuario ".
       " Where k.databaixa between '".$dtinicial."' and '".$dtfinal."' ".$pcwhere." ".$lcgroup." ".$pcordem."";

	// Cabecalho do regisrtos encontrados
	$lcString.= "<table width='100%' border='1' cellspacing='1' cellpadding='1' align='center'>
	<tr>
	<th scope='col' align='center'>Nome do Cliente</th>
	<th scope='col' align='center'>Taxa %</th>
	<th scope='col' align='center'>Local Pagto</th>
	<th scope='col' align='center'>CPF</th>
	<th scope='col' align='center'>Nro Carn&ecirc;</th>
	<th scope='col' align='center'>Compet.</th>
	<th scope='col' align='center'>Data Pagto</th>
	<th scope='col' align='center'>Vlr Plano</th>	
	<th scope='col' align='center'>Vlr Pago</th>	
	<th scope='col' align='center'>Usu&aacute;rio</th>
	</tr>";
       
    $resultado = mysqli_query($conec->con,$query) or die('ERRO NA QUERY !'.$query);
	$i=0;
	$lntotalpg = 0.00;
	
	while($row = mysqli_fetch_array($resultado)){
		
		$dtpagto = str_replace('/','',substr(converte_datacomhora($row['databaixa']),0,10));

		//		<td align='left'>".retira_acentos_UTF8($row['descricao'])."</TD>
		
		$lcString.= "<tr>
		<td align='left'>".retira_acentos_UTF8($row['nometitular'])."</TD>
		<td align='center'>".$row['percdesc']."</TD>
		<td align='left'>".retira_acentos_UTF8($row['desclocal'])."</TD>
		<td align='center'>".$row['cpf']."</TD>
		<td align='center'>".$row['nrocarne']."</TD>
		<td align='center'>".invertecomp($row['mesano'],1)."</TD>
		<td align='center'>".mask($dtpagto,'##/##/####')."</TD>
		<td align='right'>".number_format($row['valor'],2,",",".")."</TD>
		<td align='right'>".number_format($row['vlrpago'],2,",",".")."</TD>
		<td align='center'>".retira_acentos_UTF8($row['nome'])."</TD>
		</tr>";
		
		$lntotalpg+=$row['vlrpago'];
		
		$i++;
		
	}
	
	$lcString.= "</table>";
	
	$lcString.="<p>&nbsp;</p>";
	
	// Resumo
	$lcString.= "<table width='100%' border='1' cellspacing='1' cellpadding='1'>
  	<tr>
    <th align='center'>RESUMO</th>
    </tr>
  	<tr>
    <td align='left'>Total Pago</td>
    <td align='right'>".number_format($lntotalpg,2,",",".")."</td>    
    </tr>
  	<tr>
    <td align='left'>Total Registros listados</td>
    <td align='right'>".$i."</td>    
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
$arquivo = "RecebimentoCarneAnalitico".$date.".xls";  
// Configura��es header para for�ar o download  
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="'.$arquivo.'"');
header('Cache-Control: max-age=0');
// Se for o IE9, isso talvez seja necess�rio
header('Cache-Control: max-age=1');
       
// Envia o conteudo do arquivo  
echo $dadosXls;
	

} else {


$html = $header.$lcString.$footer;

include("../../includes/mpdf/vendor/autoload.php");

$mpdf = new \Mpdf\Mpdf(['debug' => true]);
$mpdf->WriteHTML($html);
$mpdf->Output();
exit;
}
?>
